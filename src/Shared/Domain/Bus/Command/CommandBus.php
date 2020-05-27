<?php

declare(strict_types=1);

namespace CheckItOut\Shared\Domain\Bus\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
