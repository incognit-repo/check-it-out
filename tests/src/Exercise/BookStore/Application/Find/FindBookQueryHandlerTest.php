<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\BookStore\Application\Find;

use CheckItOut\Exercise\BookStore\Domain\BookNotFound;
use CheckItOut\Tests\Exercise\BookStore\AbstractBookStore;
use CheckItOut\Tests\Exercise\BookStore\Domain\BookMother;
use CheckItOut\Exercise\BookStore\Application\Find\BookFinder;
use CheckItOut\Exercise\BookStore\Application\Find\FindBookQuery;
use CheckItOut\Exercise\BookStore\Application\Find\FindBookQueryHandler;

class FindBookQueryHandlerTest extends AbstractBookStore
{
    public const ID = '057eb536-7e06-45d3-91ac-1bb8bd14a13c';

    public function testFindAnExistingBook(): void
    {
        $book = BookMother::newBookTest();
        $this->getRepository()->shouldReceive('find')->andReturn($book);
        $bookFinder = new BookFinder($this->repository);

        $handler = new FindBookQueryHandler($bookFinder);
        $response = BookFinderResponseMother::newBookFinderResponse();
        $result = $handler->__invoke(new FindBookQuery(self::ID));

        $this->assertEquals($response, $result);
    }

    public function testBookNotFound(): void
    {
        $this->getRepository()->shouldReceive('find')->andReturn(null);
        $bookFinder = new BookFinder($this->repository);
        $handler = new FindBookQueryHandler($bookFinder);

        $this->expectException(BookNotFound::class);
        $handler->__invoke(new FindBookQuery(self::ID));
    }
}
