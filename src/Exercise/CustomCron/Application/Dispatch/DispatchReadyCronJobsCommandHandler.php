<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Application\Dispatch;

use CheckItOut\Exercise\CustomCron\Domain\CronJobDispatcher;
use CheckItOut\Exercise\CustomCron\Domain\CronJobRepository;

class DispatchReadyCronJobsCommandHandler
{
    private CronJobRepository $cronJobRepository;
    private string $cronFilePath;
    private CronJobDispatcher $cronJobDispatcher;

    public function __construct(
        CronJobRepository $cronJobRepository,
        string $cronFilePath,
        CronJobDispatcher $cronJobDispatcher
    ) {
        $this->cronJobRepository = $cronJobRepository;
        $this->cronFilePath = $cronFilePath;
        $this->cronJobDispatcher = $cronJobDispatcher;
    }

    public function __invoke(DispatchReadyCronJobsCommand $command): void
    {
        $cronJobs = $this->cronJobRepository->getCronJobsReady($this->cronFilePath);

        $this->cronJobDispatcher->dispatch(...$cronJobs);
    }
}
