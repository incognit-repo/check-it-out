<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\BookStore;

use Mockery\MockInterface;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use CheckItOut\Exercise\BookStore\Domain\BookRepository;

abstract class AbstractBookStore extends MockeryTestCase
{
    protected $repository;

    /** @return BookRepository|MockInterface */
    protected function getRepository(): MockInterface
    {
        return $this->repository = $this->repository ?: \Mockery::mock(BookRepository::class);
    }
}
