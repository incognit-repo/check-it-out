<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Domain;

class Book
{
    private BookId $id;
    private BookTitle $title;

    public function __construct(BookId $id, BookTitle $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public static function create(BookId $id, BookTitle $title): self
    {
        return new self($id, $title);
    }

    public function getId(): BookId
    {
        return $this->id;
    }

    public function getTitle(): BookTitle
    {
        return $this->title;
    }
}
