<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Exercise\CustomCron\Infrastructure\Repository;

use Cron\CronExpression;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use CheckItOut\Exercise\CustomCron\Domain\CronJobRepository;
use CheckItOut\Tests\Exercise\CustomCron\Domain\CronJobMother;
use CheckItOut\Exercise\CustomCron\Domain\CronFileCanNotBeRead;

class LineReaderCronJobRepositoryTest extends WebTestCase
{
    public const FILE_PATH = '/app/cron_test.txt';
    public const FILE_PATH_WRONG = '/app/fake.txt';

    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();
    }

    public function testShouldReadCronFileWithExistingCronJob(): void
    {
        \file_put_contents(self::FILE_PATH, CronJobMother::CRON_JOB_LINE);
        $cronJobs = $this->getRepository()->getCronJobsReady(self::FILE_PATH);
        $this->assertCount(1, $cronJobs);
    }

    public function testShouldReadCronFileWithoutCronJobs(): void
    {
        $cronJob = CronJobMother::newCronJob(CronJobMother::CRON_JOB_LINE_EACH12_12_12_12_00);
        if (CronExpression::factory($cronJob->getCronJobExpression()->getValue())->isDue()) {
            $cronJob = CronJobMother::newCronJob(CronJobMother::CRON_JOB_LINE_EACH11_11_11_11_00);
        }
        \file_put_contents(self::FILE_PATH, $cronJob->getCronJobExpression()->getValue());
        $cronJobs = $this->getRepository()->getCronJobsReady(self::FILE_PATH);

        $this->assertCount(0, $cronJobs);
    }

    public function testShouldThrowAndExceptionReadingFile(): void
    {
        $this->expectException(CronFileCanNotBeRead::class);
        $this->getRepository()->getCronJobsReady(self::FILE_PATH_WRONG);
    }

    protected function getRepository()
    {
        return self::$container->get(CronJobRepository::class);
    }
}
