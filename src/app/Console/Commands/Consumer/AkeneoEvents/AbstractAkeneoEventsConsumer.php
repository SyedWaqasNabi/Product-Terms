<?php

namespace App\Console\Commands\Consumer\AkeneoEvents;

use App\Services\Events\QueueMessageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

/**
 * Class AbstractAkeneoEventsConsumer
 * @package App\Console\Commands\Consumer\AkeneoEvents
 */
abstract class AbstractAkeneoEventsConsumer extends Command
{
    const SQS_QUEUE_CONFIGURATION = 'aws.sqs';

    /**
     * @var QueueMessageService $queueMessageService
     */
    protected QueueMessageService $queueMessageService;

    /**
     * AkeneoEventConsumerCommand constructor.
     * @param QueueMessageService $queueMessageService
     */
    public function __construct(QueueMessageService $queueMessageService)
    {
        $this->queueMessageService = $queueMessageService;

        parent::__construct();
    }

    /**
     * @param string $queueName
     * @return string
     */
    protected function getConfigQueueName(string $queueName): string
    {
        $queues = Config::get(self::SQS_QUEUE_CONFIGURATION);

        return $queues['akeneo-event'][$queueName];
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function decode($data)
    {
        return json_decode($data, true, 512);
    }
}
