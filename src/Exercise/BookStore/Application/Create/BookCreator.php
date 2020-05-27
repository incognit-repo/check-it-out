<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Application\Create;

use CheckItOut\Exercise\BookStore\Domain\Book;
use CheckItOut\Exercise\BookStore\Domain\BookId;
use CheckItOut\Exercise\BookStore\Domain\BookTitle;
use CheckItOut\Exercise\BookStore\Domain\BookRepository;
use CheckItOut\Exercise\BookStore\Domain\BookAlreadyExists;

class BookCreator
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(BookId $bookId, BookTitle $bookTitle)
    {
        $book = $this->bookRepository->find($bookId);

        if (null !== $book) {
            throw new BookAlreadyExists();
        }

        $book = Book::create($bookId, $bookTitle);

        $this->bookRepository->save($book);
    }
}
