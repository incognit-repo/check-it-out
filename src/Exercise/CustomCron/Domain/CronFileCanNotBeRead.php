<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Domain;

class CronFileCanNotBeRead extends \Exception
{
    public function __construct(string $message = '')
    {
        parent::__construct(
            \sprintf(
                '%s %s',
                'check_it_out.exercise.custom_cron.cron_file_can_not_be_read',
                $message
            )
        );
    }
}
