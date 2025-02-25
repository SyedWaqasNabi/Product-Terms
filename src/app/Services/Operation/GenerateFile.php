<?php


namespace App\Services\Operation;

use Illuminate\Support\Facades\Storage;

/**
 * Class GenerateFile
 * @package App\Services\Operation
 */
class GenerateFile
{
    const STORAGE_TEMPORARY = 'temporary';

    const FILE_MAPPING = 'trade-item-offer.file-mapping';

    /**
     * @param $builder
     * @param string $filePath
     * @throws \Exception
     */
    public function generateCsvFromModel($builder, string $filePath)
    {
        $storagePath = Storage::disk(self::STORAGE_TEMPORARY)->path($filePath);
        $this->checkDirOrMake($filePath);

        $handle = fopen($storagePath, "w");
        if (!$handle) {
            throw new \Exception(sprintf('Unable to open file for writing, filename: %s', $filePath));
        }

        /**
         * Attachment header fields of the CSV
         */
        $exportedColumns = array_keys(config(self::FILE_MAPPING));
        fputcsv($handle, $exportedColumns);

        $builder->addSelect($exportedColumns);

        foreach ($builder->cursor() as $key => $modelRow) {
            /**
             * Attachment content fields of the CSV
             */
            $rowArray = $modelRow->toArray();
            fputcsv($handle, $rowArray);
        };

        fclose($handle);
    }

    /**
     * @param string $storagePath
     */
    private function checkDirOrMake(string $storagePath)
    {
        $dirPath = substr($storagePath, 0, strrpos($storagePath, '/'));

        if (!Storage::disk(self::STORAGE_TEMPORARY)->exists($dirPath)) {
            Storage::disk(self::STORAGE_TEMPORARY)->makeDirectory($dirPath);
        }
    }
}
