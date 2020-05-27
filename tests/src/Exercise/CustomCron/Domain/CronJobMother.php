<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\CustomCron\Domain;

use CheckItOut\Exercise\CustomCron\Domain\CronJob;

class CronJobMother
{
    public const CRON_JOB_LINE = '* * * * *  touch /app/cronJob.txt';
    public const CRON_JOB_LINE_EACH12_12_12_12_00 = '12 12 12 12 *  /app/cronJob2.txt';
    public const CRON_JOB_LINE_EACH11_11_11_11_00 = '11 11 11 11 *  /app/cronJob3.txt';
    public const CRON_JOB_EXCEPTION = '11 11 11 11 *  (int) "1"';
    public const CRON_JOB_EXCEPTION2 = '11 11 11 11 *  aa "2"';

    public static function newCronJob(string $cronJobLine): CronJob
    {
        return CronJob::createFromCronFileLine(
            $cronJobLine
        );
    }
}
