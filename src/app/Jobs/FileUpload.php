<?php

namespace App\Jobs;

use App\Services\Operation\ReadFile;
use App\Services\S3\FileStorage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FileUpload extends AbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const  FILE_UPLOAD_QUEUE_NAME = 'TIO_FILE_UPLOAD';

    protected string $fileName;

    /**
     * Create a new job instance.
     *
     * @param $fileName
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $fileStorage = new FileStorage();
            $fileData    = $fileStorage->get($this->fileName);
            $readFile    = new ReadFile();
            $readFile->read($fileData);
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
}
