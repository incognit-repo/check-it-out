<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Domain;

class BookNotFound extends \Exception
{
    public function __construct()
    {
        parent::__construct('check_it_out.exercise.book_store.book_not_found');
    }
}
