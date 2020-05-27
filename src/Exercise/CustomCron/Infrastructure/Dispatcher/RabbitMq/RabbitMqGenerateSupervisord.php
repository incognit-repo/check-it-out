<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Infrastructure\Dispatcher\RabbitMq;

use CheckItOut\Exercise\CustomCron\Domain\CronJob;

class RabbitMqGenerateSupervisord
{
    private const NUMBERS_OF_PROCESSES = 1;

    private string $supervisordFilePath;

    public function __construct(string $supervisordFilePath)
    {
        $this->supervisordFilePath = $supervisordFilePath;
    }

    public function generate(CronJob ...$cronJobs)
    {
        $fileContent = '';
        foreach ($cronJobs as $cronJob) {
            $fileContent .= \str_replace(
                [
                    '{subscriber_name}',
                    '{queue_name}',
                    '{processes}',
                ],
                [
                    $cronJob->getCronJobName()->getValue(),
                    $cronJob->getCronJobName()->getValue(),
                    self::NUMBERS_OF_PROCESSES,
                ],
                $this->template()
            );
        }

        $fp = \fopen('/app/supervisord.conf', 'a');
        \fwrite($fp, $fileContent);
        \fclose($fp);
    }

    private function template(): string
    {
        return <<<'EOF'
        
        
        [program:check-it-out_{queue_name}]
        command      = php /app/apps/exercise/bin/console check-it-out:rabbitmq:consumer --env=test {queue_name}
        process_name = %(program_name)s_%(process_num)02d
        numprocs     = {processes}
        startsecs    = 1
        startretries = 10
        exitcodes    = 2
        stopwaitsecs = 300
        autostart    = true
        stdout_logfile=/app/apps/exercise/var/log/supervisor_stdout.log
        stderr_logfile=/app/apps/exercise/var/log/supervisor_stderr.log
        EOF;
    }
}
