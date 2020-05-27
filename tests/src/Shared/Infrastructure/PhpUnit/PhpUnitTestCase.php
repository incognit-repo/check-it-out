<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Shared\Infrastructure\PhpUnit;

use Mockery;
use Mockery\MockInterface;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use CheckItOut\Shared\Domain\Bus\Event\EventBus;

abstract class PhpUnitTestCase extends MockeryTestCase
{
    private $eventBus;

    protected function mock(string $className): MockInterface
    {
        return Mockery::mock($className);
    }

    /** @return EventBus|MockInterface */
    protected function eventBus(): MockInterface
    {
        return $this->eventBus = $this->eventBus ?: $this->mock(EventBus::class);
    }
}
