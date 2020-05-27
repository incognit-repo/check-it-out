<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Domain;

class CronJob
{
    private CronJobCommand $cronJobCommand;
    private CronJobExpression $cronJobExpression;
    private CronJobName $cronJobName;

    public function __construct(CronJobCommand $cronJobCommand, CronJobExpression $cronJobExpression, CronJobName $cronJobName)
    {
        $this->cronJobCommand = $cronJobCommand;
        $this->cronJobExpression = $cronJobExpression;
        $this->cronJobName = $cronJobName;
    }

    public static function createFromCronFileLine(string $cronFileLine): self
    {
        $cronJobExpression = CronJobExpression::createFromCronFileLine($cronFileLine);
        $cronJobCommand = CronJobCommand::createFromCronFileLineAndCronJobExpression($cronFileLine, $cronJobExpression);
        $cronJobName = CronJobName::createFromCronFileLine($cronFileLine);

        return new self($cronJobCommand, $cronJobExpression, $cronJobName);
    }

    public function getCronJobCommand(): CronJobCommand
    {
        return $this->cronJobCommand;
    }

    public function getCronJobExpression(): CronJobExpression
    {
        return $this->cronJobExpression;
    }

    public function getCronJobName(): CronJobName
    {
        return $this->cronJobName;
    }
}
