<?php

namespace App\Jobs;

use App\Services\Operation\ReadFile;
use App\Services\S3\FileStorage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\LazyCollection;

/**
 * Class FileUploadStore
 * @package App\Jobs
 */
class FileUploadStore extends AbstractJob implements ShouldQueue
{

    const QUEUE_NAME = 'TIO_FILE_IMPORT';

    private ReadFile $readFile;

    protected string $fileName;

    /**
     * Create a new job instance.
     *
     * @param $fileName
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        $this->readFile = new ReadFile();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $fileStorage    = new FileStorage();
            $this->readFile = new ReadFile();
            $fileData       = $fileStorage->get($this->fileName);
            /** @var LazyCollection $records */
            $records = $this->readFile->readCsvFile($fileData);
            $records->each(function ($lines) {
                $rows = $this->readFile->getFormattedLines($lines);
                $this->importQueue($rows);
            });
        } catch (\Exception $e) {
            $this->failed($e);
        }
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param $lists
     */
    public function importQueue($lists)
    {
        foreach ($lists as $list) {
            if (!empty($list)) {
                unset(
                    $list['id'],
                    $list['stock_keeping_unit'],
                    $list['import_status'],
                    $list['created_at'],
                    $list['updated_at']
                );

                $tradeItemOfferStore = new TradeItemOfferStore($list);
                $importJobQueue      = $tradeItemOfferStore->onQueue(TradeItemOfferStore::IMPORT_QUEUE_NAME);
                dispatch($importJobQueue);
            }
        }
    }
}
