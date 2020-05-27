<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Infrastructure\Repository;

use CheckItOut\Exercise\BookStore\Domain\Book;
use CheckItOut\Exercise\BookStore\Domain\BookId;
use CheckItOut\Exercise\BookStore\Domain\BookTitle;
use CheckItOut\Exercise\BookStore\Domain\BookRepository;

class FileStorageRepository implements BookRepository
{
    private string $booksStoreFilePath;

    public function __construct(string $booksStoreFilePath)
    {
        $this->booksStoreFilePath = $booksStoreFilePath;
    }

    public function find(BookId $id): ?Book
    {
        $books = $this->findAll();

        if (!\array_key_exists($id->getValue(), $books)) {
            return null;
        }

        return Book::create(
            $id,
            new BookTitle($books[$id->getValue()])
        );
    }

    public function save(Book $book): void
    {
        $books = $this->findAll();
        $books[$book->getId()->getValue()] = $book->getTitle()->getValue();

        $resource = \fopen($this->booksStoreFilePath, 'rb+');

        if (\flock($resource, LOCK_EX)) {
            \ftruncate($resource, 0);
            \fwrite($resource, \json_encode($books));
            \fflush($resource);
            \flock($resource, LOCK_UN);
        } else {
            throw new \Exception();
        }

        \fclose($resource);
    }

    private function findAll(): array
    {
        $resource = \fopen($this->booksStoreFilePath, 'r');

        $size = \filesize($this->booksStoreFilePath);
        $fileSize = $size > 0 ? $size : 100;
        $fileContent = \fread($resource, $fileSize);
        \fclose($resource);
        $books = \json_decode($fileContent, true);

        if (null === $books) {
            return [];
        }

        return \json_decode($fileContent, true);
    }
}
