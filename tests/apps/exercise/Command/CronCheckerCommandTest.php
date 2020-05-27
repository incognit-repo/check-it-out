<?php


namespace apps\exercise\Command;


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CronCheckerCommandTest extends WebTestCase
{

    public function testShouldExecuteCronJobCheckerCommand(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('check-it-out:custom-cron:checker');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}
