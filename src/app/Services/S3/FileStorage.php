<?php

namespace App\Services\S3;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Adapter\Local;

/**
 * Class FileStorage
 * @package App\Services\S3
 */
class FileStorage
{
    const STORAGE_TYPE = 's3';
    const STORAGE_TEMPORARY = 'temporary';
    const DELETE_FILE_PATH_APPEND = '_delete_';

    /**
     * @param $filePath
     * @param $file
     * @return bool|false|string
     */
    public function upload($filePath, $file)
    {
        return Storage::disk(self::STORAGE_TYPE)->put($filePath, file_get_contents($file));
    }

    /**
     * @param $filePath
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileExistsException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function moveFromTemporary($filePath)
    {
        $result = Storage::disk(self::STORAGE_TYPE)->writeStream(
            $filePath,
            Storage::disk(self::STORAGE_TEMPORARY)->readStream($filePath)
        );
        $this->deleteTemporary($filePath);

        return $result;
    }

    /**
     * @param string $fileName
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get(string $fileName): string
    {
        return Storage::disk(self::STORAGE_TYPE)->get($fileName);
    }

    /**
     * @param string $fileName
     * @param \DateTimeInterface $expirationTime
     * @return string|null
     */
    public function getTemporaryDownloadLink(string $fileName, \DateTimeInterface $expirationTime): ?string
    {
        $result = null;
        if (Storage::disk(self::STORAGE_TYPE)->exists($fileName)) {
            $storage = Storage::disk(self::STORAGE_TYPE);
            if ($storage->getDriver()->getAdapter() instanceof Local) {
                $result = $storage->url($fileName);
            } else {
                $result = $storage->temporaryUrl(
                    $fileName,
                    $expirationTime
                );
            }
        }

        return $result;
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function delete(string $fileName): bool
    {
        return Storage::disk(self::STORAGE_TYPE)->delete($fileName);
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function deleteTemporary(string $fileName): bool
    {
        return Storage::disk(self::STORAGE_TEMPORARY)->delete($fileName);
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function formatFileName(string $fileName): string
    {
        return Carbon::now()->format("Y-m-d") . '/' . Carbon::now()->format("Y_m_d_h:i:s_A") . '_'
            . $this->getTimeZone() . '_' . $fileName;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function formatFileNameExport(string $fileName): string
    {
        $fileName = 'export/job_' . $fileName . '.csv';

        return $this->getFolderPath() . $fileName;
    }

    /**
     * @return string|null
     */
    public function getFolderPath(): ?string
    {
        return env("AWS_BUCKET_FOLDER");
    }

    /**
     * @return string
     */
    public function getTimeZone(): string
    {
        return str_replace("/", "_", config("app.timezone"));
    }
}
