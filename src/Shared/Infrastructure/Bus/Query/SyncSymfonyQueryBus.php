<?php

declare(strict_types=1);

namespace CheckItOut\Shared\Infrastructure\Bus\Query;

use Symfony\Component\Messenger\HandleTrait;
use CheckItOut\Shared\Domain\Bus\Query\Query;
use CheckItOut\Shared\Domain\Bus\Query\QueryBus;
use CheckItOut\Shared\Domain\Bus\Query\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class SyncSymfonyQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $bus)
    {
        $this->messageBus = $bus;
    }

    public function get(Query $query): ?Response
    {
        return $this->handle($query);
    }
}
