<?php

declare(strict_types=1);

namespace CheckItOut\Apps\Exercise\Command\CustomCron;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CheckItOut\Exercise\CustomCron\Infrastructure\Dispatcher\RabbitMq\RabbitMqCronJobConsumer;

class CronJobConsumerCommand extends Command
{
    protected static $defaultName = 'check-it-out:rabbitmq:consumer';

    private RabbitMqCronJobConsumer $consumer;

    public function __construct(RabbitMqCronJobConsumer $consumer)
    {
        parent::__construct();
        $this->consumer = $consumer;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Start consumer for cron job')
            ->addArgument('queue', InputArgument::REQUIRED, 'Queue name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queueName = (string) $input->getArgument('queue');
        $this->consumer->consume($queueName);

        return 0;
    }
}
