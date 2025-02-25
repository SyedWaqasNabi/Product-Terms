<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Resources\DownloadFile;
use App\Http\Resources\QueueJob;
use App\Http\Resources\UploadFile;
use App\Jobs\FileExport;
use App\Jobs\FileUploadDelete;
use App\Jobs\FileUploadStore;
use App\Jobs\TradeItemOfferStore;
use App\Models\TradeItemOffer;
use App\Services\Operation\GenerateFile;
use App\Services\Operation\ReadFile;
use App\Services\S3\FileStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Class FileTest
 * @package Tests\Feature\Http\Controllers\Api
 */
class FileTest extends TestCase
{
    use RefreshDatabase;

    private ReadFile $readFile;

    private array $tradeItemOfferArray;

    /**
     * Test file upload
     * @test
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function fileUploadStore()
    {
        Storage::fake(FileStorage::STORAGE_TYPE);
        Queue::fake();
        $content = $this->getFileContentImport();

        $file = UploadedFile::fake()->createWithContent(
            'test.csv',
            $content
        );

        $response = $this->json(
            'POST',
            self::TRADE_ITEM_OFFER_REST . ':import',
            [
                'csv_file' => $file
            ],
            $this->authenticationHeaders()
        );
        $response->assertStatus(UploadFile::STATUS_CODE_CREATED);
        $responseArray = json_decode($response->getContent(), true);
        $fileName      = $responseArray['data']['attributes']['name'];
        Storage::disk(FileStorage::STORAGE_TYPE)->assertExists($fileName);
        $this->pushToQueueFileImport($fileName);
        $tradeItemOfferArray = $this->readFile($fileName);
        $this->assertNotNull($tradeItemOfferArray);
        $fileUploadStore = new FileUploadStore($fileName);
        $fileUploadStore->importQueue($tradeItemOfferArray);
        $this->pushToQueueTradeItemOfferStore($tradeItemOfferArray[0]);
    }

    /**
     * Test file upload
     * @test
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function fileUploadDelete()
    {
        Storage::fake(FileStorage::STORAGE_TYPE);
        Queue::fake();
        $content = $this->getFileContentDelete();

        $file = UploadedFile::fake()->createWithContent(
            'test.csv',
            $content
        );

        $response = $this->json(
            'POST',
            self::TRADE_ITEM_OFFER_REST . ':delete',
            [
                'csv_file' => $file
            ],
            $this->authenticationHeaders()
        );
        $response->assertStatus(UploadFile::STATUS_CODE_CREATED);
        $responseArray = json_decode($response->getContent(), true);
        $fileName      = $responseArray['data']['attributes']['name'];
        Storage::disk(FileStorage::STORAGE_TYPE)->assertExists($fileName);
        $this->pushToQueueFileDelete($fileName);
        $tradeItemOfferArray = $this->readFile($fileName);
        $this->assertNotNull($tradeItemOfferArray);
    }

    /**
     * Test file export
     * @test
     */
    public function fileExport()
    {
        Queue::fake();

        $tradeItemOfferData = \factory(TradeItemOffer::class)->create();
        $tradeItemOfferArray = $tradeItemOfferData->toArray();
        $tradeItemOfferId = $tradeItemOfferArray['id'];

        $response = $this->json(
            'POST',
            self::TRADE_ITEM_OFFER_REST . ':export',
            [
                'selected_id' => [$tradeItemOfferId]
            ],
            $this->authenticationHeaders()
        );

        $response->assertStatus(QueueJob::STATUS_CODE_ACCEPTED);
        Queue::assertPushedOn(FileExport::FILE_EXPORT_QUEUE_NAME, FileExport::class);
        Queue::assertPushed(FileExport::class, function ($job) use ($tradeItemOfferId) {
            return $job->getExportableData()['selected_id'][0] == $tradeItemOfferId;
        });
    }

    /**
     * Test file export all
     * @test
     */
    public function fileExportAll()
    {
        Queue::fake();

        \factory(TradeItemOffer::class, 3)->create();

        $response = $this->json(
            'POST',
            self::TRADE_ITEM_OFFER_REST . ':export',
            [
                'export_all' => true
            ],
            $this->authenticationHeaders()
        );

        $response->assertStatus(QueueJob::STATUS_CODE_ACCEPTED);
        Queue::assertPushedOn(FileExport::FILE_EXPORT_QUEUE_NAME, FileExport::class);
    }

    /**
     * Test file download
     * @test
     *
     * @throws \Illuminate\Contracts\Filesystem\FileExistsException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     */
    public function fileDownload()
    {
        Queue::fake();
        Storage::fake(FileStorage::STORAGE_TYPE);

        $fileStorage = new FileStorage();

        $tradeItemOfferData = \factory(TradeItemOffer::class)->create();
        $tradeItemOfferArray = $tradeItemOfferData->toArray();

        $tradeItemOfferId = $tradeItemOfferArray['id'];
        $jobId = 1;

        $filePath = $fileStorage->formatFileNameExport($jobId);
        $tradeItemOffersModel = TradeItemOffer::whereIn('id', [$tradeItemOfferId]);
        $fileGenerator = new GenerateFile();
        $fileGenerator->generateCsvFromModel($tradeItemOffersModel, $filePath);
        $fileStorage->moveFromTemporary($filePath);

        $response = $this->json(
            'GET',
            self::TRADE_ITEM_OFFER_REST . ':download' . '/' . $jobId,
            [],
            $this->authenticationHeaders()
        );

        $response->assertStatus(DownloadFile::STATUS_CODE_OK);
        $responseArray = json_decode($response->getContent(), true);
        $this->assertNotNull($responseArray['data']['links']['related']['href'] ?? null);
    }

    /**
     * @param $fileName
     * @return array|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function readFile($fileName)
    {
        $content        = Storage::disk(FileStorage::STORAGE_TYPE)->get($fileName);
        $this->readFile = new ReadFile();
        $collection     = $this->readFile->readCsvFile($content);
        $collection->each(function ($lines) {
            $this->tradeItemOfferArray = $this->readFile->getFormattedLines($lines);
        });

        return $this->tradeItemOfferArray;
    }

    /**
     * @return string
     */
    protected function getFileContentImport()
    {
        // @codingStandardsIgnoreLine
        $header = 'trade_item_id,supplier_id,customer_group_id,internal_name,supplier_trade_item_number,stock_keeping_unit,old_stock_keeping_unit,net_price,currency,sales_unit,minimum_delivery_time,maximum_delivery_time,minimum_order_quantity,maximum_order_quantity,delivery_time_unit,is_warehouse_item,import_status,is_active';
        $row    = '9,5,5,Dr. Christop Champlin Jr.,258,unit,unit,3,EUR,8,8,5,7,1,days,0,1,0';

        return implode("\n", [$header, $row]);
    }

    /**
     * @return string
     */
    protected function getFileContentDelete()
    {
        // @codingStandardsIgnoreLine
        $header = '1,trade_item_id,supplier_id,customer_group_id,internal_name,supplier_trade_item_number,stock_keeping_unit,old_stock_keeping_unit,net_price,currency,sales_unit,minimum_delivery_time,maximum_delivery_time,minimum_order_quantity,maximum_order_quantity,delivery_time_unit,is_warehouse_item,import_status,is_active';
        $row    = '1,9,5,5,Dr. Christop Champlin Jr.,258,unit,unit,3,EUR,8,8,5,7,1,days,0,1,0';

        return implode("\n", [$header, $row]);
    }

    /**
     * @param $fileName
     */
    protected function pushToQueueFileImport($fileName)
    {
        Queue::assertPushed(FileUploadStore::class, function ($job) use ($fileName) {
            return $job->getFileName() === $fileName;
        });
        Queue::assertPushedOn(FileUploadStore::QUEUE_NAME, FileUploadStore::class);
    }

    /**
     * @param $fileName
     */
    protected function pushToQueueFileDelete($fileName)
    {
        Queue::assertPushed(FileUploadDelete::class, function ($job) use ($fileName) {
            return $job->getFileName() === $fileName;
        });
        Queue::assertPushedOn(FileUploadDelete::QUEUE_NAME, FileUploadDelete::class);
    }

    /**
     * @param $tradeItemOfferArray
     */
    protected function pushToQueueTradeItemOfferStore($tradeItemOfferArray)
    {
        Queue::assertPushed(TradeItemOfferStore::class, function ($job) use ($tradeItemOfferArray) {
            return $job->getTradeItemId() === $tradeItemOfferArray['trade_item_id']
                && $job->getSupplierId() === $tradeItemOfferArray['supplier_id']
                && $job->getCustomerGroupId() === $tradeItemOfferArray['customer_group_id'];
        });
        Queue::assertPushedOn(TradeItemOfferStore::IMPORT_QUEUE_NAME, TradeItemOfferStore::class);
    }
}
