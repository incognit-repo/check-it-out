<?php

declare(strict_types=1);

namespace CheckItOut\Shared\Domain\Bus\Query;

interface QueryBus
{
    public function get(Query $query): ?Response;
}
