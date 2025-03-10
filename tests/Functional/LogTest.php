<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Log\Log;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class LogTest extends KernelTestCase
{
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
    }

    public function testLogFile(): void
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

    public function testInitLogger(): void
    {
        $logger = $this->createMock(LoggerInterface::class);

        Log::init($logger);

        $this->assertInstanceOf(LoggerInterface::class, $this->getPrivateStaticProperty(Log::class, 'logger'));
    }

    public function testGetLoggerThrowsExceptionWhenNotInitialized(): void
    {
        $this->resetLogger();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Logger has not been initialized.');

        $this->invokePrivateStaticMethod(Log::class, 'getLogger');
    }

    public function testCriticalMethodCallsLogger(): void
    {
        $this->assertLogMethodCalled('critical');
    }

    public function testDebugMethodCallsLogger(): void
    {
        $this->assertLogMethodCalled('debug');
    }

    public function testErrorMethodCallsLogger(): void
    {
        $this->assertLogMethodCalled('error');
    }

    public function testInfoMethodCallsLogger(): void
    {
        $this->assertLogMethodCalled('info');
    }

    private function assertLogMethodCalled(string $method): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method($method)
            ->with(
                $this->equalTo('Test message'),
                $this->equalTo(['key' => 'value'])
            );

        Log::init($logger);
        Log::$method('Test message', ['key' => 'value']);
    }

    private function getPrivateStaticProperty(string $class, string $property): mixed
    {
        $reflection = new ReflectionClass($class);
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(true);

        return $prop->getValue();
    }

    private function invokePrivateStaticMethod(string $class, string $method): mixed
    {
        $reflection = new ReflectionClass($class);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invoke(null);
    }

    private function resetLogger(): void
    {
        $reflection = new ReflectionClass(Log::class);
        $prop = $reflection->getProperty('logger');
        $prop->setAccessible(true);
        $prop->setValue(null);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $filesystem = new Filesystem();

        if ($filesystem->exists($this->logsDir)) {
            $filesystem->remove(glob($this->logsDir.'/*'));
        }
    }
}
