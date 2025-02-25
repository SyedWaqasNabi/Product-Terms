<?php

namespace App\Console\Commands\Consumer\AkeneoEvents;

use App\Services\Events\QueueMessageService;
use App\Services\ProductService;

/**
 * Class ProductUpdatedConsumerCommand
 * @package App\Console\Commands\Consumer\AkeneoEvents
 */
class ProductUpdatedConsumerCommand extends AbstractAkeneoEventsConsumer
{
    const QUEUE_NAME = 'akeneo-event-product_updated';

    /**
     * @var ProductService
     */
    private ProductService $productService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:consume:akeneo-product-updated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume Akeneo Product Updated Event';

    /**
     * ProductUpdatedConsumerCommand constructor.
     * @param ProductService $categoryService
     * @param QueueMessageService $queueMessageService
     */
    public function __construct(ProductService $productService, QueueMessageService $queueMessageService)
    {
        $this->productService = $productService;

        parent::__construct($queueMessageService);
    }

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        try {
            $queueName = $this->getConfigQueueName(self::QUEUE_NAME);
            $this->queueMessageService->innitSqsClient($queueName);

            $messages = $this->queueMessageService->getQueueMessage();

            foreach ($messages as $message) {
                if (!empty($message)) {
                    $messageBody = $this->decode($message['Body']);

                    if (!isset($messageBody['payload']) || empty($messageBody['payload'])) {
                        \Log::critical(sprintf(
                            'Error while consuming Akeneo product update event. Empty event message. Payload %s',
                            $message
                        ));

                        continue;
                    }

                    $this->productService->proceedSyncProductEvent($messageBody['payload']);

                    if ($this->queueMessageService->deleteMessage($message)) {
                        \Log::info(sprintf(
                            'Message for Product Updated Event was removed successfully. ReceiptHandle: %s.',
                            $message['ReceiptHandle']
                        ));
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage() . " " . $e->getLine());
        }
    }
}
