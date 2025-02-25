<?php
namespace App\Services\Events;

use Aws\Credentials\Credentials;
use Aws\Exception\AwsException;
use Aws\Result;
use Aws\Sqs\SqsClient;
use Mockery\Exception;

/**
 * Class QueueMessageService
 * @package App\Services\Events
 */
class QueueMessageService
{
    private $queueUrl;

    /**
     * @param string $queueName
     */
    public function innitSqsClient(string $queueName): void
    {
        $client = $this->sqsClient();
        $this->queueUrl = $client->getQueueUrl([
            'QueueName' => $queueName,
            'QueueOwnerAWSAccountId' => config('queue.connections.sqs.prefix'),
        ])->get('QueueUrl');
    }

    /**
     * @return array
     */
    public function getQueueMessage()
    {
        $client = $this->sqsClient();
        $queueData = [];
        try {
            $result = $client->receiveMessage([
                'AttributeNames'        => ['SentTimestamp'],
                'MaxNumberOfMessages'   => 10,
                'MessageAttributeNames' => ['All'],
                'QueueUrl'              => $this->queueUrl,
                'WaitTimeSeconds'       => 10,
            ]);
            if ($result->get('Messages')) {
                foreach ($result->search('Messages[]') as $message) {
                    $queueData[$message['MessageId']] = [
                        'ReceiptHandle' => $message['ReceiptHandle'],
                        'Body' => $message['Body']
                    ];
                }
            }
        } catch (Exception $exception) {
            \Log::critical(sprintf(
                'An error occurred while fetching queue with url: %s. Error: %s.',
                $this->queueUrl,
                $exception->getMessage()
            ));
        }

        return $queueData;
    }

    /**
     * @param array $data
     * @return Result
     */
    public function deleteMessage(array $data)
    {
        $client = $this->sqsClient();

        return $client->deleteMessage([
            'QueueUrl' => $this->queueUrl,
            'ReceiptHandle' => $data['ReceiptHandle']
        ]);
    }

    /**
     * @return mixed
     */
    public function getQueueUrl()
    {
        return $this->getQueueUrl();
    }

    /**
     * @return SqsClient
     */
    private function sqsClient() : SqsClient
    {
        $credentials = new Credentials(config('queue.connections.sqs.key'), config('queue.connections.sqs.secret'), null);

        return new SqsClient([
            'credentials' => $credentials,
            'region'      => config('queue.connections.sqs.region'),
            'version'     => 'latest'
        ]);
    }
}
