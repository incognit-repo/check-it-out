<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Infrastructure\Repository;

use Cron\CronExpression;
use Bcremer\LineReader\LineReader;
use CheckItOut\Exercise\CustomCron\Domain\CronJob;
use CheckItOut\Exercise\CustomCron\Domain\CronJobRepository;
use CheckItOut\Exercise\CustomCron\Domain\CronFileCanNotBeRead;

class LineReaderCronJobRepository implements CronJobRepository
{
    public function getCronJobsReady(string $path): array
    {
        try {
            $lines = LineReader::readLines($path);
            $cronJobs = [];
            foreach ($lines as $line) {
                $cronJob = CronJob::createFromCronFileLine($line);
                $cronExpression = CronExpression::factory($cronJob->getCronJobExpression()->getValue());
                if ($cronExpression->isDue()) {
                    $cronJobs[] = $cronJob;
                }
            }
        } catch (\InvalidArgumentException $exception) {
            throw new CronFileCanNotBeRead();
        }

        return $cronJobs;
    }
}
