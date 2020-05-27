<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Application\Create;

use CheckItOut\Exercise\BookStore\Domain\BookId;
use CheckItOut\Exercise\BookStore\Domain\BookTitle;

class CreateBookCommandHandler
{
    private BookCreator $bookCreator;

    public function __construct(BookCreator $bookCreator)
    {
        $this->bookCreator = $bookCreator;
    }

    public function __invoke(CreateBookCommand $command): void
    {
        $id = new BookId($command->getId());
        $title = new BookTitle($command->getTitle());

        $this->bookCreator->__invoke($id, $title);
    }
}
