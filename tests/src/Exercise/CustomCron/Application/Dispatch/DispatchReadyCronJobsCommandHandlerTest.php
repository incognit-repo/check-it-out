<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\CustomCron\Application\Dispatch;

use Mockery;
use CheckItOut\Exercise\CustomCron\Domain\CronJobDispatcher;
use CheckItOut\Exercise\CustomCron\Domain\CronJobRepository;
use CheckItOut\Tests\Exercise\CustomCron\Domain\CronJobMother;
use CheckItOut\Tests\Shared\Infrastructure\PhpUnit\PhpUnitTestCase;
use CheckItOut\Exercise\CustomCron\Application\Dispatch\DispatchReadyCronJobsCommand;
use CheckItOut\Exercise\CustomCron\Application\Dispatch\DispatchReadyCronJobsCommandHandler;

class DispatchReadyCronJobsCommandHandlerTest extends PhpUnitTestCase
{
    public const FILE_PATH = '/app/cron.txt';
    /**
     * @var CronJobRepository|Mockery\LegacyMockInterface|Mockery\MockInterface
     */
    private $repository;
    /**
     * @var CronJobDispatcher|Mockery\LegacyMockInterface|Mockery\MockInterface
     */
    private $dispatcher;

    private DispatchReadyCronJobsCommandHandler $handler;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(CronJobRepository::class);
        $this->dispatcher = Mockery::mock(CronJobDispatcher::class);
        $this->handler = new DispatchReadyCronJobsCommandHandler(
            $this->repository,
            self::FILE_PATH,
            $this->dispatcher
        );
    }

    public function testShouldDispatchReadyCronJobs(): void
    {
        $cronJob = CronJobMother::newCronJob(CronJobMother::CRON_JOB_LINE);

        $this->repository->shouldReceive('getCronJobsReady')->with(self::FILE_PATH)->andReturn([$cronJob]);
        $this->dispatcher->shouldReceive('dispatch')->with(...[$cronJob]);

        $this->handler->__invoke(new DispatchReadyCronJobsCommand());
    }

    public function testShouldNotDispatchCronJobs(): void
    {
        $this->repository->shouldReceive('getCronJobsReady')->with(self::FILE_PATH)->andReturn([]);
        $this->dispatcher->shouldReceive('dispatch');

        $this->handler->__invoke(new DispatchReadyCronJobsCommand());
    }
}
