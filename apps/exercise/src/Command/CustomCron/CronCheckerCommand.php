<?php

declare(strict_types=1);

namespace CheckItOut\Apps\Exercise\Command\CustomCron;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use CheckItOut\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Console\Output\OutputInterface;
use CheckItOut\Exercise\CustomCron\Application\Dispatch\DispatchReadyCronJobsCommand;

class CronCheckerCommand extends Command
{
    protected static $defaultName = 'check-it-out:custom-cron:checker';
    /**
     * @var CommandBus
     */
    private CommandBus $bus;

    public function __construct(CommandBus $bus)
    {
        parent::__construct();
        $this->bus = $bus;
    }

    protected function configure(): void
    {
        $this->setDescription('Check if cron job is ready');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bus->dispatch(new DispatchReadyCronJobsCommand());

        return 0;
    }
}
