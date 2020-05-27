<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Application\Find;

use CheckItOut\Exercise\BookStore\Domain\BookId;
use CheckItOut\Exercise\BookStore\Domain\BookNotFound;
use CheckItOut\Exercise\BookStore\Domain\BookRepository;

class BookFinder
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(BookId $id): BookFinderResponse
    {
        $book = $this->bookRepository->find($id);

        if (null === $book) {
            throw new BookNotFound();
        }

        return new BookFinderResponse($book->getId()->getValue(), $book->getTitle()->getValue());
    }
}
