<?php

namespace App\Jobs;

use App\Models\TradeItemOffer;
use App\Services\Operation\ReadFile;
use App\Services\S3\FileStorage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\LazyCollection;

/**
 * Class FileUploadDelete
 * @package App\Jobs
 */
class FileUploadDelete extends AbstractJob implements ShouldQueue
{

    const  QUEUE_NAME = 'TIO_FILE_DELETE';

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
            $fileData       = $fileStorage->get($this->fileName);
            $this->readFile = new ReadFile();
            /** @var LazyCollection $records */
            $records = $this->readFile->readCsvFile($fileData);
            $records->each(function ($lines) {
                $rows = $this->readFile->getFormattedLines($lines);
                $this->deleteQueue($rows);
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
     * @return TradeItemOfferDelete|void
     */
    public function deleteQueue($lists)
    {
        foreach ($lists as $list) {
            $tradeItemOffer       = TradeItemOffer::query()->findOrFail($list['id']);
            $tradeItemOfferDelete = new TradeItemOfferDelete($tradeItemOffer);
            $deleteJobQueue       = $tradeItemOfferDelete->onQueue(TradeItemOfferDelete::DELETE_QUEUE_NAME);
            dispatch($deleteJobQueue);
        }
    }
}
