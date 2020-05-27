<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\Shared\Infrastructure\RabbitMq;

class RabbitMqExchange
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRetryExchangeName(): string
    {
        return \sprintf('%s_%s', 'retry', $this->getName());
    }

    public function getDeadLetterExchangeName(): string
    {
        return \sprintf('%s_%s', 'dead', $this->getName());
    }
}
