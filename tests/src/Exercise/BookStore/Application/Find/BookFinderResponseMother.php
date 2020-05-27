<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\BookStore\Application\Find;

use CheckItOut\Tests\Exercise\BookStore\Domain\BookMother;
use CheckItOut\Exercise\BookStore\Application\Find\BookFinderResponse;

class BookFinderResponseMother
{
    public const ID = '057eb536-7e06-45d3-91ac-1bb8bd14a13c';
    public const TITLE = 'book title';

    public static function newBookFinderResponse(): BookFinderResponse
    {
        return new BookFinderResponse(
            BookMother::ID,
            self::TITLE
        );
    }
}
