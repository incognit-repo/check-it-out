<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\CustomCron\Domain;

class CronJobName
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function createFromCronFileLine(string $cronFileLine): self
    {
        $string = \trim($cronFileLine, ' ');
        $string = \preg_replace('/[^A-Za-z0-9\- ]/', '', $string);
        $string = \str_replace(' ', '_', $string);

        return new self(\preg_replace('/-+/', '-', $string));
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
