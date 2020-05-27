<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Infrastructure\Dispatcher\RabbitMq;

use AMQPQueue;
use CheckItOut\Exercise\CustomCron\Domain\CronJob;
use CheckItOut\Shared\Infrastructure\RabbitMq\RabbitMqConnection;
use CheckItOut\Exercise\Shared\Infrastructure\RabbitMq\RabbitMqExchange;

class RabbitMqCronJobConfigurer
{
    private RabbitMqConnection $connection;
    private RabbitMqExchange $exchange;

    public function __construct(RabbitMqConnection $connection, RabbitMqExchange $exchange)
    {
        $this->connection = $connection;
        $this->exchange = $exchange;
    }

    public function configure(CronJob ...$cronJobs): void
    {
        $this->declareExchange($this->exchange->getName());
        $this->declareExchange($this->exchange->getRetryExchangeName());
        $this->declareExchange($this->exchange->getDeadLetterExchangeName());

        foreach ($cronJobs as $cronJob) {
            $queueName = $cronJob->getCronJobName()->getValue();
            $retryQueueName = \sprintf('%s_%s', 'retry', $queueName);
            $deadLetterQueueName = \sprintf('%s_%s', 'dead', $queueName);

            $queue = $this->declareQueue($queueName);
            $retryQueue = $this->declareQueue($retryQueueName, $this->exchange->getName(), $queueName, 100);
            $deadLetterQueue = $this->declareQueue($deadLetterQueueName);

            $queue->bind($this->exchange->getName(), $cronJob->getCronJobName()->getValue());
            $retryQueue->bind($this->exchange->getRetryExchangeName(), $cronJob->getCronJobName()->getValue());
            $deadLetterQueue->bind($this->exchange->getDeadLetterExchangeName(), $cronJob->getCronJobName()->getValue());
        }
    }

    public function getConnection(): RabbitMqConnection
    {
        return $this->connection;
    }

    public function getExchange(): RabbitMqExchange
    {
        return $this->exchange;
    }

    private function declareExchange(string $exchangeName): void
    {
        $exchange = $this->connection->exchange($exchangeName);
        $exchange->setType(AMQP_EX_TYPE_TOPIC);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();
    }

    private function declareQueue(
        string $name,
        string $deadLetterExchange = null,
        string $deadLetterRoutingKey = null,
        int $messageTtl = null
    ): AMQPQueue {
        $queue = $this->connection->queue($name);

        if (null !== $deadLetterExchange) {
            $queue->setArgument('x-dead-letter-exchange', $deadLetterExchange);
        }

        if (null !== $deadLetterRoutingKey) {
            $queue->setArgument('x-dead-letter-routing-key', $deadLetterRoutingKey);
        }

        if (null !== $messageTtl) {
            $queue->setArgument('x-message-ttl', $messageTtl);
        }

        $queue->setFlags(AMQP_DURABLE);
        $queue->declareQueue();

        return $queue;
    }
}
