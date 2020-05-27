<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Domain;

interface CronJobRepository
{
    public function getCronJobsReady(string $path): array;
}
