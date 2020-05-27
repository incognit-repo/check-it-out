<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\BookStore\Domain;

use CheckItOut\Exercise\BookStore\Domain\Book;
use CheckItOut\Exercise\BookStore\Domain\BookId;
use CheckItOut\Exercise\BookStore\Domain\BookTitle;

class BookMother
{
    public const ID = '057eb536-7e06-45d3-91ac-1bb8bd14a13c';
    public const TITLE = 'book title';

    public static function newBookTest(): Book
    {
        return new Book(
            new BookId(self::ID),
            new BookTitle(self::TITLE)
        );
    }
}
