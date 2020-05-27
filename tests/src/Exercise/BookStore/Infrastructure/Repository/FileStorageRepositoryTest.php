<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\BookStore\Infrastructure\Repository;

use CheckItOut\Exercise\BookStore\Domain\BookId;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use CheckItOut\Exercise\BookStore\Domain\BookRepository;
use CheckItOut\Tests\Exercise\BookStore\Domain\BookMother;

class FileStorageRepositoryTest extends WebTestCase
{
    public const ID = '057eb536-7e06-45d3-91ac-1bb8bd14a13c';

    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();
    }

    public function testFindAndExistingBook(): void
    {
        $book = BookMother::newBookTest();
        $this->getRepository()->save($book);
        $bookId = new BookId(self::ID);
        $this->assertEquals($book, $this->getRepository()->find($bookId));
    }

    protected function getRepository()
    {
        return self::$container->get(BookRepository::class);
    }
}
