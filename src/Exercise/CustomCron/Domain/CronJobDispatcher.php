<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Domain;

interface CronJobDispatcher
{
    public function dispatch(CronJob ...$cronJob): void;
}
