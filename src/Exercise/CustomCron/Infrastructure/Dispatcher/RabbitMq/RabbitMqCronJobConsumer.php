<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Infrastructure\Dispatcher\RabbitMq;

use AMQPQueue;
use AMQPEnvelope;
use AMQPQueueException;
use Symfony\Component\Process\Process;
use CheckItOut\Shared\Infrastructure\RabbitMq\RabbitMqConnection;
use CheckItOut\Exercise\Shared\Infrastructure\RabbitMq\RabbitMqExchange;

class RabbitMqCronJobConsumer
{
    private RabbitMqConnection $connection;
    private int $maxRetries;
    private RabbitMqExchange $exchange;

    public function __construct(RabbitMqConnection $connection, string $exchangeName, int $maxRetries)
    {
        $this->connection = $connection;
        $this->maxRetries = $maxRetries;
        $this->exchange = new RabbitMqExchange($exchangeName);
    }

    public function consume(string $queueName): void
    {
        try {
            $this->connection->queue($queueName)->consume($this->executeCommand());
        } catch (AMQPQueueException $error) {
        }
    }

    private function executeCommand(): callable
    {
        return function (AMQPEnvelope $envelope, AMQPQueue $queue) {
            $cronJobCommand = \unserialize($envelope->getBody());

            try {
                $process = Process::fromShellCommandline($cronJobCommand);
                $process->mustRun();
            } catch (\Throwable $error) {
                $this->handleConsumptionError($envelope, $queue);

                throw $error;
            }

            $queue->ack($envelope->getDeliveryTag());
        };
    }

    private function handleConsumptionError(AMQPEnvelope $envelope, AMQPQueue $queue): void
    {
        $this->hasBeenRedeliveredTooMuch($envelope)
            ? $this->sendToDeadLetter($envelope, $queue)
            : $this->sendToRetry($envelope, $queue);

        $queue->ack($envelope->getDeliveryTag());
    }

    private function hasBeenRedeliveredTooMuch(AMQPEnvelope $envelope): bool
    {
        $headers = $envelope->getHeaders();
        if (!isset($headers['redelivery_count'])) {
            return false;
        }

        return $headers['redelivery_count'] >= $this->maxRetries;
    }

    private function sendToDeadLetter(AMQPEnvelope $envelope, AMQPQueue $queue): void
    {
        $this->sendMessageTo($this->exchange->getDeadLetterExchangeName(), $envelope, $queue);
    }

    private function sendToRetry(AMQPEnvelope $envelope, AMQPQueue $queue): void
    {
        $this->sendMessageTo($this->exchange->getRetryExchangeName(), $envelope, $queue);
    }

    private function sendMessageTo(string $exchangeName, AMQPEnvelope $envelope, AMQPQueue $queue): void
    {
        $headers = $envelope->getHeaders();

        if (!isset($headers['redelivery_count'])) {
            $headers['redelivery_count'] = 0;
        }
        $redeliveryCount = $headers['redelivery_count'];
        $headers['redelivery_count'] = $redeliveryCount + 1;

        $this->connection->exchange($exchangeName)->publish(
            $envelope->getBody(),
            $queue->getName(),
            AMQP_NOPARAM,
            [
                'content_type' => $envelope->getContentType(),
                'content_encoding' => $envelope->getContentEncoding(),
                'headers' => $headers,
            ]
        );
    }
}
