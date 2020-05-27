<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\BookStore\Application\Create;

use Mockery;
use PHPUnit\Framework\TestCase;
use CheckItOut\Exercise\BookStore\Domain\BookRepository;
use CheckItOut\Tests\Exercise\BookStore\Domain\BookMother;
use CheckItOut\Exercise\BookStore\Domain\BookAlreadyExists;
use CheckItOut\Exercise\BookStore\Application\Create\BookCreator;
use CheckItOut\Exercise\BookStore\Application\Create\CreateBookCommand;
use CheckItOut\Exercise\BookStore\Application\Create\CreateBookCommandHandler;

class CreateBookCommandHandlerTest extends TestCase
{
    public const ID = '057eb536-7e06-45d3-91ac-1bb8bd14a13c';
    public const TITLE = 'create book title';
    /**
     * @var BookRepository|Mockery\LegacyMockInterface|Mockery\MockInterface
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(BookRepository::class);
    }

    public function testShouldCreateABookIfNotExists(): void
    {
        $this->repository->shouldReceive('find')->andReturnNull();
        $this->repository->shouldReceive('save');
        $bookCreator = new BookCreator($this->repository);
        $handler = new CreateBookCommandHandler($bookCreator);

        $handler->__invoke(new CreateBookCommand(
           self::ID,
           self::TITLE,
       ));
    }

    public function testShouldThrowAnExceptionIfBookAlreadyExists(): void
    {
        $this->repository->shouldReceive('find')->andReturn(BookMother::newBookTest());
        $bookCreator = new BookCreator($this->repository);
        $handler = new CreateBookCommandHandler($bookCreator);
        $this->expectException(BookAlreadyExists::class);
        $handler->__invoke(new CreateBookCommand(
                               self::ID,
                               self::TITLE,
                           ));
    }
}
