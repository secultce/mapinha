<?php

declare(strict_types=1);

namespace App\Tests\Functional\Log;

use App\Log\Log;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class LogTest extends KernelTestCase
{
    private LoggerInterface $loggerMock;

    private string $logsDir;

    private const array FILES = [
        'critical' => 'errors_5xx.log',
        'error' => 'errors_4xx.log',
        'debug' => 'debug.log',
        'info' => 'info.log',
    ];

    protected function setUp(): void
    {
        self::bootKernel();

        $this->logsDir = sprintf('%s/%s', self::$kernel->getLogDir(), 'tests');

        $filesystem = new Filesystem();

        if ($filesystem->exists($this->logsDir)) {
            $filesystem->remove(glob($this->logsDir.'/*'));
        }

        $this->loggerMock = $this->createMock(LoggerInterface::class);
    }

    public function testLog(): void
    {
        $logger = self::getContainer()->get('monolog.logger');

        $logger->critical('critical message', ['foo' => 'bar']);
        $logger->error('error message', ['foo' => 'bar']);
        $logger->debug('debug message', ['foo' => 'bar']);
        $logger->info('info message', ['foo' => 'bar']);

        foreach (self::FILES as $file) {
            $this->assertFileExists($this->logsDir.'/'.$file);
        }

        foreach (self::FILES as $key => $file) {
            $log = file_get_contents($this->logsDir.'/'.$file);
            $this->assertStringContainsString($key, $log);
        }
    }

    public function testInitSetsLoggerCorrectly(): void
    {
        Log::init($this->loggerMock);

        $this->assertSame($this->loggerMock, $this->getStaticLogger());
    }

    public function testCriticalLogsCorrectly(): void
    {
        Log::init($this->loggerMock);

        $message = 'Critical error occurred';
        $context = ['error_code' => 500];

        $this->loggerMock->expects($this->once())
            ->method('critical')
            ->with($message, $context);

        Log::critical($message, $context);
    }

    public function testDebugLogsCorrectly(): void
    {
        Log::init($this->loggerMock);

        $message = 'Debug information';
        $context = ['data' => 'value'];

        $this->loggerMock->expects($this->once())
            ->method('debug')
            ->with($message, $context);

        Log::debug($message, $context);
    }

    public function testErrorLogsCorrectly(): void
    {
        Log::init($this->loggerMock);

        $message = 'Error occurred';
        $context = ['file' => 'example.php'];

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with($message, $context);

        Log::error($message, $context);
    }

    public function testInfoLogsCorrectly(): void
    {
        Log::init($this->loggerMock);

        $message = 'Informational message';
        $context = ['user' => 123];

        $this->loggerMock->expects($this->once())
            ->method('info')
            ->with($message, $context);

        Log::info($message, $context);
    }

    public function testGetLoggerThrowsExceptionWhenNotInitialized(): void
    {
        $reflection = new ReflectionClass(Log::class);
        $property = $reflection->getProperty('logger');
        $property->setValue(null, null);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Logger has not been initialized.');

        Log::critical('This should fail');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $filesystem = new Filesystem();

        if ($filesystem->exists($this->logsDir)) {
            $filesystem->remove(glob($this->logsDir.'/*'));
        }
    }

    private function getStaticLogger(): ?LoggerInterface
    {
        $reflection = new ReflectionClass(Log::class);
        $property = $reflection->getProperty('logger');
        $property->setAccessible(true);

        return $property->getValue();
    }
}
