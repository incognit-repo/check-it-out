<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Infrastructure\Dispatcher\RabbitMq;

use CheckItOut\Exercise\CustomCron\Domain\CronJob;
use CheckItOut\Exercise\CustomCron\Domain\CronJobDispatcher;

class RabbitMqCronJobDispatcher implements CronJobDispatcher
{
    private RabbitMqCronJobConfigurer $configurer;
    /**
     * @var RabbitMqGenerateSupervisord
     */
    private RabbitMqGenerateSupervisord $generateSupervisord;

    public function __construct(RabbitMqCronJobConfigurer $configurer, RabbitMqGenerateSupervisord $generateSupervisord)
    {
        $this->configurer = $configurer;
        $this->generateSupervisord = $generateSupervisord;
    }

    public function dispatch(CronJob ...$cronJobs): void
    {
        $this->configurer->configure(...$cronJobs);
        $this->generateSupervisord->generate(...$cronJobs);
        foreach ($cronJobs as $cronJob) {
            $this->publishCronJob($cronJob);
        }
    }

    private function publishCronJob(CronJob $cronJob): void
    {
        $body = \serialize($cronJob->getCronJobCommand()->getValue());
        $routingKey = $cronJob->getCronJobName()->getValue();

        $this->configurer->getConnection()->exchange($this->configurer->getExchange()->getName())->publish(
            $body,
            $routingKey,
            AMQP_NOPARAM,
            [
                'content_type' => 'application/json',
                'content_encoding' => 'utf-8',
            ]
        );
    }
}
