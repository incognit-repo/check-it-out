<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\CustomCron\Infrastructure\Dispatcher\RabbitMq;

use CheckItOut\Exercise\CustomCron\Domain\CronJob;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use CheckItOut\Exercise\CustomCron\Domain\CronJobDispatcher;
use CheckItOut\Tests\Exercise\CustomCron\Domain\CronJobMother;
use CheckItOut\Shared\Infrastructure\RabbitMq\RabbitMqConnection;
use CheckItOut\Exercise\Shared\Infrastructure\RabbitMq\RabbitMqExchange;
use CheckItOut\Exercise\CustomCron\Infrastructure\Dispatcher\RabbitMq\RabbitMqCronJobConsumer;

class RabbitMqCronJobConsumerTest extends WebTestCase
{
    private RabbitMqCronJobConsumer $consumer;
    private $connection;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        parent::setUp();
        $this->connection = $this->getConnection();
        $this->consumer = new RabbitMqCronJobConsumer(
            $this->connection,
            'exercise',
            1
        );
    }

    public function testShouldDispatchCronJobToRabbitMq(): void
    {
        $cronJob = CronJobMother::newCronJob(CronJobMother::CRON_JOB_LINE);
        $this->getDispatcher()->dispatch(...[$cronJob]);
        $this->consumer->consume($cronJob->getCronJobName()->getValue());
        $this->clearQueues($cronJob);
    }

    public function testShouldRetryAfterCronJobException(): void
    {
        $cronJob = CronJobMother::newCronJob(CronJobMother::CRON_JOB_EXCEPTION);
        $exchange = new RabbitMqExchange($cronJob->getCronJobName()->getValue());
        $this->getDispatcher()->dispatch(...[$cronJob]);
        $this->errorConsuming($exchange->getName());
        $this->clearQueues($cronJob);
    }

    public function testShouldGoToDeadQueueAfterMaxRetries(): void
    {
        $cronJob = CronJobMother::newCronJob(CronJobMother::CRON_JOB_EXCEPTION2);
        $this->getDispatcher()->dispatch(...[$cronJob]);

        $this->errorConsuming($cronJob->getCronJobName()->getValue());
        \sleep(5);
        $this->errorConsuming($this->getRetryQueueName($cronJob->getCronJobName()->getValue()));
        \sleep(2);
        $this->assertTotalMessagesInQueue(1, $this->getDeadQueueName($cronJob->getCronJobName()->getValue()));

        $this->clearQueues($cronJob);
    }

    protected function getDispatcher()
    {
        return self::$container->get(CronJobDispatcher::class);
    }

    protected function getConsumer()
    {
        return self::$container->get(RabbitMqCronJobConsumer::class);
    }

    protected function getConnection()
    {
        return self::$container->get(RabbitMqConnection::class);
    }

    private function errorConsuming(string $queue): void
    {
        try {
            $this->consumer->consume($queue);
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(\RuntimeException::class, $exception);
        }
    }

    private function assertTotalMessagesInQueue(int $expectedNumberOfEvents, string $queue): void
    {
        $totalEventsInDeadLetter = 0;

        while ($this->connection->queue($queue)->get(
            AMQP_AUTOACK
        )) {
            ++$totalEventsInDeadLetter;
        }

        $this->assertSame($expectedNumberOfEvents, $totalEventsInDeadLetter);
    }

    private function getRetryQueueName(string $queueName): string
    {
        return \sprintf('%s_%s', 'retry', $queueName);
    }

    private function getDeadQueueName(string $queueName): string
    {
        return \sprintf('%s_%s', 'dead', $queueName);
    }

    private function clearQueues(CronJob $cronJob): void
    {
        $queueName = $cronJob->getCronJobName()->getValue();
        $this->connection->queue($queueName)->delete();
        $this->connection->queue($this->getRetryQueueName($queueName))->delete();
        $this->connection->queue($this->getDeadQueueName($queueName))->delete();
    }
}
