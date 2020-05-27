<?php

declare(strict_types=1);

namespace CheckItOut\Shared\Infrastructure\Bus\Event;

use Symfony\Component\Messenger\Envelope;
use CheckItOut\Shared\Domain\Bus\Event\EventBus;
use CheckItOut\Shared\Domain\Bus\Event\DomainEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

class SyncSymfonyEventBus implements EventBus
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function dispatch(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch(
                (new Envelope($event))
                    ->with(new DispatchAfterCurrentBusStamp())
            );
        }
    }
}
