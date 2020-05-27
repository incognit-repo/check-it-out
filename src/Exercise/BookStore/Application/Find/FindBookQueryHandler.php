<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Application\Find;

use CheckItOut\Exercise\BookStore\Domain\BookId;

class FindBookQueryHandler
{
    private BookFinder $bookFinder;

    public function __construct(BookFinder $bookFinder)
    {
        $this->bookFinder = $bookFinder;
    }

    public function __invoke(FindBookQuery $query): BookFinderResponse
    {
        return $this->bookFinder->__invoke(new BookId($query->getId()));
    }
}
