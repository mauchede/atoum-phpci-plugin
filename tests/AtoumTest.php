<?php

namespace Mauchede\PHPCI\Plugin\Tests;

use Mauchede\PHPCI\Plugin\Atoum;
use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Model\BuildError;
use Psr\Log\LoggerInterface;

class AtoumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $build;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $logger;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $phpci;

    /**
     * Tests the plugin.
     */
    public function testExecuteWithInvalidTests()
    {
        $this->phpci
            ->expects($this->any())
            ->method('findBinary')
            ->with('atoum')
            ->willReturn('/usr/local/bin/atoum');

        $this->phpci
            ->expects($this->any())
            ->method('getLastOutput')
            ->willReturn(file_get_contents(__DIR__.'/Fixtures/error/tap.txt'));

        $this->phpci
            ->expects($this->once())
            ->method('executeCommand')
            ->with(
                'php %s -c %s -ft -utr',
                '/usr/local/bin/atoum',
                __DIR__.'/Fixtures/error/atoum.php'
            )
            ->willReturn(false);

        $this->build
            ->expects($this->at(0))
            ->method('reportError')
            ->with(
                $this->phpci,
                'stage_test',
                'Test "testGetUri()" failed.',
                BuildError::SEVERITY_CRITICAL,
                'Tests\Units\Model\Atoum'
            );

        $this->build
            ->expects($this->at(1))
            ->method('reportError')
            ->with(
                $this->phpci,
                'stage_test',
                'Test "testAddContent()" failed.',
                BuildError::SEVERITY_CRITICAL,
                'Tests\Units\Model\Atoum'
            );

        $plugin = new Atoum(
            $this->phpci,
            $this->build,
            [
                'config' => __DIR__.'/Fixtures/error/atoum.php',
            ]
        );

        $this->assertFalse($plugin->execute());
    }

    /**
     * Tests the plugin with a missing configuration file.
     */
    public function testExecuteWithMissingConfigurationFile()
    {
        $plugin = new Atoum(
            $this->phpci,
            $this->build
        );

        $this->phpci
            ->expects($this->once())
            ->method('logFailure')
            ->with(
                sprintf(
                    'The Atoum config file "%s" is missing.',
                    'atoum.php'
                )
            );

        $this->assertFalse($plugin->execute());
    }

    /**
     * Tests the plugin with valid tests.
     */
    public function testExecuteWithValidTests()
    {
        $this->phpci
            ->expects($this->any())
            ->method('findBinary')
            ->with('atoum')
            ->willReturn('/usr/local/bin/atoum');

        $this->phpci
            ->expects($this->any())
            ->method('getLastOutput')
            ->willReturn(file_get_contents(__DIR__.'/Fixtures/success/tap.txt'));

        $this->phpci
            ->expects($this->once())
            ->method('executeCommand')
            ->with(
                'php %s -c %s -ft -utr',
                '/usr/local/bin/atoum',
                __DIR__.'/Fixtures/success/atoum.php'
            )
            ->willReturn(true);

        $plugin = new Atoum(
            $this->phpci,
            $this->build,
            [
                'config' => __DIR__.'/Fixtures/success/atoum.php',
            ]
        );

        $this->assertTrue($plugin->execute());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->build = $this->getMock(Build::class);
        $this->logger = $this->getMock(LoggerInterface::class);

        $this->phpci = $this
            ->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->phpci->buildPath = __DIR__;
    }
}
