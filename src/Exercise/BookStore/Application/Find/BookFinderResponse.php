<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Application\Find;

use CheckItOut\Shared\Domain\Bus\Query\Response;

class BookFinderResponse implements Response
{
    private string $id;
    private string $title;

    public function __construct(string $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
