<?php


namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

abstract class AbstractJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Handle the job
     */
    abstract protected function handle();

    /**
     * The job failed to process.
     *
     * @param \Exception $exception
     * @return string
     */
    public function failed(\Exception $exception)
    {
        Log::critical($exception->getMessage() . ' on ' . $exception->getFile());
    }
}
