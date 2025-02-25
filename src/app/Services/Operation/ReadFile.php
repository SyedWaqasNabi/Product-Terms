<?php

namespace App\Services\Operation;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

/**
 * Class ReadFile
 * @package App\Services\Operation
 */
class ReadFile
{

    const CHUNK_SIZE_TO_SPLIT_DATA = 1000;

    const FILE_MAPPING = 'trade-item-offer.file-mapping';

    protected string $fileData;

    protected array $header = [];


    /**
     * Read csv file and put that in laravel lazy collection for iteration.
     * @param $fileData
     * @return mixed
     */
    public function readCsvFile($fileData)
    {
        try {
            $this->fileData = $fileData;

            return LazyCollection::make(function () {
                $csv = array_map('str_getcsv', explode("\n", $this->fileData));
                foreach ($csv as $line) {
                    yield $line;
                }
            })->chunk(self::CHUNK_SIZE_TO_SPLIT_DATA);
        } catch (\Exception $exception) {
            Log::critical($exception->getMessage() . ' on ' . $exception->getFile());
        }
    }

    /**
     * Get formatted lines
     * @param LazyCollection $lines
     * @return array
     */
    public function getFormattedLines(LazyCollection $lines): array
    {
        $formattedLines = [];
        foreach ($lines as $line) {
            if (!empty($line)) {
                if (empty($this->header)) {
                    $this->fileMapping($line);
                    continue;
                }

                $formattedLine = $this->formatLine($line);
                if ($formattedLine) {
                    array_push($formattedLines, $formattedLine);
                }
            }
        }

        return $formattedLines;
    }

    /**
     * Reading header and do the mapping
     * @param array $header
     */
    private function fileMapping(array $header): void
    {
        foreach ($header as $title) {
            $this->header[] = $this->retrieveKey($title);
        }
    }

    /** Format header and line
     * @param array $attributeLine
     * @return array|null
     */
    private function formatLine(array $attributeLine): ?array
    {
        if (count($this->header) == count($attributeLine)) {
            $attributeLine = array_combine($this->header, $attributeLine);

            return $attributeLine;
        }

        return null;
    }

    /**
     * Get the map key for csv header
     * @param string $title
     * @return int|mixed|string
     */
    private function retrieveKey(string $title)
    {
        $mapArray = config(self::FILE_MAPPING);
        foreach ($mapArray as $key => $valuesArray) {
            if (in_array(strtolower($title), array_map("strtolower", $valuesArray))) {
                return $key;
            }
        }

        return $title;
    }
}
