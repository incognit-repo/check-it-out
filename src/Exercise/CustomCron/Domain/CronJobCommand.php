<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Domain;

class CronJobCommand
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function createFromCronFileLineAndCronJobExpression(
        string $cronFileLine,
        CronJobExpression $cronJobExpression
    ): self {
        return new self(\str_replace($cronJobExpression->getValue(), '', $cronFileLine));
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
