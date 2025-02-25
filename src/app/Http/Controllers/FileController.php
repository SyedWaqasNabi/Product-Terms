<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportFileRequest;
use App\Http\Requests\UploadFileRequest;
use App\Http\Resources\DownloadFile as DownloadFileResource;
use App\Http\Resources\QueueJob as QueueJobResource;
use App\Http\Resources\UploadFile as UploadFileResource;
use App\Jobs\FileUploadDelete;
use App\Jobs\FileUploadStore;
use App\Jobs\FileExport;
use App\Services\S3\FileStorage;
use Carbon\Carbon;

/**
 * Class FileController
 * @package App\Http\Controllers
 */
class FileController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/product/terms:import",
     *      operationId="Upload Csv File",
     *      tags={"Product Terms File"},
     *      summary="Upload Csv File",
     *      description="Upload Csv File",
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="Upload Csv file",
     *                     property="csv_file",
     *                     type="file",
     *                     format="file",
     *                 ),
     *                 required={"file"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      security= {{"basic": {}} },
     * )
     *
     * @param UploadFileRequest $request
     * @return UploadFileResource
     * @throws \Exception
     */
    public function store(UploadFileRequest $request)
    {
        $file        = $request->file('csv_file');
        $name        = $file->getClientOriginalName();
        $fileStorage = new FileStorage();
        $fileName    = $fileStorage->formatFileName($name);
        $filePath    = $fileStorage->getFolderPath() . $fileName;
        $response    = $fileStorage->upload($filePath, $file);
        if ($response) {
            $fileUploadJob         = new FileUploadStore($filePath);
            $fileUploadQueue       = $fileUploadJob->onQueue(FileUploadStore::QUEUE_NAME);
            $jobId                 = $this->dispatch($fileUploadQueue);
            $resourceArray['name'] = $fileName;
            $resourceArray['id']   = $jobId;

            \Log::info(
                sprintf('import job queue id: %s. Filename: %s,', $jobId, $fileName)
            );
            return new UploadFileResource((object)$resourceArray);
        }
    }

    /**
     * @OA\POST(
     *      path="/api/v1/product/terms:delete",
     *      operationId="Upload Csv File Delete",
     *      tags={"Product Terms File"},
     *      summary="Upload Csv File to Delete Items",
     *      description="Upload Csv File to Delete Items",
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="Upload Csv file",
     *                     property="csv_file",
     *                     type="file",
     *                     format="file",
     *                 ),
     *                 required={"file"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      security= {{"basic": {}} },
     * )
     *
     * @param UploadFileRequest $request
     * @return UploadFileResource
     * @throws \Exception
     */
    public function destroy(UploadFileRequest $request)
    {
        $file        = $request->file('csv_file');
        $name        = $file->getClientOriginalName();
        $fileStorage = new FileStorage();
        $name        = FileStorage::DELETE_FILE_PATH_APPEND . $name;
        $fileName    = $fileStorage->formatFileName($name);
        $filePath    = $fileStorage->getFolderPath() . $fileName;
        $response    = $fileStorage->upload($filePath, $file);
        if ($response) {
            $fileUploadJob         = new FileUploadDelete($filePath);
            $fileUploadQueue       = $fileUploadJob->onQueue(FileUploadDelete::QUEUE_NAME);
            $jobId                 = $this->dispatch($fileUploadQueue);
            $resourceArray['name'] = $fileName;
            $resourceArray['id']   = $jobId;
            \Log::info(
                sprintf('Removed job queue id: %s. Filename: %s,', $jobId, $fileName)
            );
            return new UploadFileResource((object)$resourceArray);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/v1/product/terms:export",
     *      operationId="Export Csv File",
     *      tags={"Product Terms File"},
     *      summary="Export Csv File",
     *      description="Export Csv File",
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="Export All",
     *                     property="export_all",
     *                     type="bool"
     *                 ),
     *                 @OA\Property(
     *                     description="Selected IDs array",
     *                     property="selected_id",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=202,
     *          description="Accepted",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      security= {{"basic": {}} },
     * )
     *
     * @param ExportFileRequest $request
     * @return QueueJobResource
     */
    public function export(ExportFileRequest $request)
    {
        $tradeItemOffersExport = new FileExport($request->request->all());
        $createJobQueue = $tradeItemOffersExport->onQueue(FileExport::FILE_EXPORT_QUEUE_NAME);

        return new QueueJobResource($this->getJob($createJobQueue));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/product/terms:download/{id}",
     *      operationId="Download Exported File",
     *      tags={"Product Terms File"},
     *      summary="Download Exported File",
     *      description="Download Exported File",
     *      @OA\Parameter(
     *          name="id",
     *          description="export job Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      security= {{"basic": {}} },
     * )
     *
     * @param int $id
     * @return DownloadFileResource
     */
    public function download(int $id)
    {
        $fileStorage = new FileStorage();
        $filePath = $fileStorage->formatFileNameExport($id);
        $expirationTime = Carbon::now()->addMinutes(20);

        $downloadLinkArray['link'] = $fileStorage->getTemporaryDownloadLink($filePath, $expirationTime);

        $downloadLinkArray['expirationTime'] = $expirationTime->format('Y-m-d H:i:s.u');

        \Log::info(
            sprintf('Download file link: %s. Expire Time: %s,', $downloadLinkArray['link'], $downloadLinkArray['expirationTime'])
        );
        return new DownloadFileResource((object)$downloadLinkArray);
    }

    /**
     * @param $jobQueue
     * @return object
     */
    private function getJob($jobQueue)
    {
        return (object) [
            'id'    => $this->dispatch($jobQueue),
            'queue' => $jobQueue->queue
        ];
    }
}
