<?php

namespace Interop\Container\Test\Exception;

use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;
use Interop\Container\Test\Fixtures\WitnessNotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInterfaceExistsAndIsInterface()
    {
        $this->assertTrue(interface_exists('Interop\Container\Exception\NotFoundException'));
        $this->assertFalse(class_exists('Interop\Container\Exception\NotFoundException', false));

        $reflection = new \ReflectionClass('Interop\Container\Exception\NotFoundException');
        $this->assertTrue($reflection->isInterface());

        $expectedFile = dirname(dirname(__DIR__)) . '/src/Interop/Container/Exception/NotFoundException.php';
        $this->assertSame(realpath($expectedFile), realpath($reflection->getFileName()));
    }

    public function testParentSet()
    {
        $reflection = new \ReflectionClass('Interop\Container\Exception\NotFoundException');
        $interfaces = $reflection->getInterfaceNames();
        sort($interfaces);

        $this->assertSame(
            array(
                'Interop\Container\Exception\ContainerException',
                'Psr\Container\ContainerExceptionInterface',
                'Psr\Container\NotFoundExceptionInterface',
            ),
            $interfaces
        );
    }

    public function testDeclaresNoMethods()
    {
        $reflection = new \ReflectionClass('Interop\Container\Exception\NotFoundException');
        $this->assertSame(array(), $reflection->getMethods());
    }

    public function testDocblock()
    {
        $reflection = new \ReflectionClass('Interop\Container\Exception\NotFoundException');
        $docComment = $reflection->getDocComment();

        $this->assertContains('No entry was found in the container.', $docComment);
    }

    public function testThrownNotFoundIsCatchableAsEveryParent()
    {
        try {
            throw new WitnessNotFoundException('entry missing');
        } catch (NotFoundException $exception) {
            $this->assertInstanceOf('Interop\Container\Exception\ContainerException', $exception);
            $this->assertInstanceOf('Psr\Container\NotFoundExceptionInterface', $exception);
            $this->assertInstanceOf('Psr\Container\ContainerExceptionInterface', $exception);
            $this->assertInstanceOf('Exception', $exception);
        }
    }
}
