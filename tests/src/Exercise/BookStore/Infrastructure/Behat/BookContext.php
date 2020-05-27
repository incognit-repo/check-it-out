<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\BookStore\Infrastructure\Behat;

use CheckItOut\Tests\Shared\Infrastructure\Behat\ApiContext;

class BookContext extends ApiContext
{
    /**
     * @BeforeScenario @fixtures
     */
    public function fixtures(): void
    {
        \file_put_contents('/app/books.json', '{"057eb536-7e06-45d3-91ac-1bb8bd14a13c":"book title"}');
    }

    /**
     * @BeforeScenario @removeFixtures
     */
    public function removeFixtures(): void
    {
        \file_put_contents('/app/books.json', '');
    }
}
