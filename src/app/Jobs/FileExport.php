<?php

namespace App\Jobs;

use App\Models\TradeItemOffer;
use App\Services\Operation\GenerateFile;
use App\Services\S3\FileStorage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FileExport
 * @package App\Jobs
 */
class FileExport extends AbstractJob implements ShouldQueue
{
    const FILE_EXPORT_QUEUE_NAME = 'TIO_FILE_EXPORT';

    protected array $exportableData;

    /**
     * Create a new job instance.
     *
     * FileExport constructor.
     * @param $exportableData
     */
    public function __construct($exportableData)
    {
        $this->exportableData = $exportableData;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $fileStorage = new FileStorage();
            $jobId = $this->job->getJobId();
            $filePath = $fileStorage->formatFileNameExport($jobId);
            $this->exportTradeItemOffers($filePath);
            $fileStorage->moveFromTemporary($filePath);
        } catch (\Exception $e) {
            $this->failed($e);
        }
    }

    /**
     * @return array
     */
    public function getExportableData()
    {
        return $this->exportableData;
    }

    /**
     * @param string $filePath
     * @throws \Exception
     */
    private function exportTradeItemOffers(string $filePath)
    {
        $tradeItemOffersModel = $this->getTradeItemOffersModel();
        $fileGenerator = new GenerateFile();
        $fileGenerator->generateCsvFromModel($tradeItemOffersModel, $filePath);
    }

    /**
     * @return Builder
     */
    private function getTradeItemOffersModel(): Builder
    {
        if (isset($this->exportableData['export_all']) && $this->exportableData['export_all']) {
            $tradeItemOffersModel = TradeItemOffer::select('id');
        } else {
            $tradeItemOffersModel = TradeItemOffer::whereIn('id', $this->exportableData['selected_id']);
        }

        return $tradeItemOffersModel;
    }
}
