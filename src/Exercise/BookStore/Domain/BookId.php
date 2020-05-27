<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Domain;

class BookId
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
