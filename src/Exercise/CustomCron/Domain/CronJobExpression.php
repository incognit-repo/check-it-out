<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Domain;

class CronJobExpression
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function createFromCronFileLine(string $cronFileLine): self
    {
        $parts = \preg_split('/\s+/', $cronFileLine);

        return new self(\sprintf(
            '%s %s %s %s %s',
            $parts[0],
            $parts[1],
            $parts[2],
            $parts[3],
            $parts[4],
            )
        );
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
