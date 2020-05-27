<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Domain;

interface BookRepository
{
    public function find(BookId $id): ?Book;

    public function save(Book $book): void;
}
