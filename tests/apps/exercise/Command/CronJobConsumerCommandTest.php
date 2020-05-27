<?php


namespace apps\exercise\Command;


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CronJobConsumerCommandTest extends WebTestCase
{

    public function testShouldExecuteCronJobConsumerCommand(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('check-it-out:rabbitmq:consumer');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            [
                'command' => $command->getName(),
                'queue' => 'test_queue'
            ]
        );

        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}
