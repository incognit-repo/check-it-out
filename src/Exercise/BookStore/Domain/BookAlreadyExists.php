<?php

declare(strict_types=1);

namespace CheckItOut\Exercise\BookStore\Domain;

class BookAlreadyExists extends \Exception
{
    public function __construct()
    {
        parent::__construct('check_it_out.exercise.book_store.book_already_exists');
    }
}
