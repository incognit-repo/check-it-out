<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\CustomCron\Infrastructure\Dispatcher\RabbitMq;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use CheckItOut\Exercise\CustomCron\Domain\CronJobDispatcher;
use CheckItOut\Tests\Exercise\CustomCron\Domain\CronJobMother;

class RabbitMqCronJobDispatcherTest extends WebTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();
    }

    public function testShouldDispatchCronJobToRabbitMq(): void
    {
        $cronJob = CronJobMother::newCronJob(CronJobMother::CRON_JOB_LINE);
        $this->getDispatcher()->dispatch($cronJob);
    }

    protected function getDispatcher()
    {
        return self::$container->get(CronJobDispatcher::class);
    }
}
