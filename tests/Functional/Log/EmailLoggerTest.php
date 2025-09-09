<?php

declare(strict_types=1);

namespace App\Tests\Functional\Log;

use App\Log\EmailLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EmailLoggerTest extends KernelTestCase
{
    private readonly EmailLogger $emailLogger;

    private string $logsDir;

    protected function setUp(): void
    {
        self::bootKernel();

        $logger = self::getContainer()->get('monolog.logger');
        $this->emailLogger = new EmailLogger($logger);

        $this->logsDir = sprintf('%s/%s', self::$kernel->getLogDir(), 'tests');
    }

    public function testLogEmailNotSent(): void
    {
        $this->emailLogger->logEmailNotSent('test@example.com', 'test error');
        $this->assertFileExists($this->logsDir.'/errors_4xx.log');
        $log = file_get_contents($this->logsDir.'/errors_4xx.log');
        $this->assertStringContainsString('Could not send email to user: test@example.com. Error: test error', $log);
    }
}
