<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Application\Find;

use CheckItOut\Shared\Domain\Bus\Query\Query;

class FindBookQuery implements Query
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
