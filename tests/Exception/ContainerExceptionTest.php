<?php

namespace Interop\Container\Test\Exception;

use Interop\Container\Test\Fixtures\WitnessContainerException;

class ContainerExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInterfaceExistsAndIsInterface()
    {
        $this->assertTrue(interface_exists('Interop\Container\Exception\ContainerException'));
        $this->assertFalse(class_exists('Interop\Container\Exception\ContainerException', false));

        $reflection = new \ReflectionClass('Interop\Container\Exception\ContainerException');
        $this->assertTrue($reflection->isInterface());

        $expectedFile = dirname(dirname(__DIR__)) . '/src/Interop/Container/Exception/ContainerException.php';
        $this->assertSame(realpath($expectedFile), realpath($reflection->getFileName()));
    }

    public function testExtendsPsrContainerExceptionInterfaceOnly()
    {
        $reflection = new \ReflectionClass('Interop\Container\Exception\ContainerException');
        $interfaces = $reflection->getInterfaceNames();
        sort($interfaces);

        $this->assertSame(array('Psr\Container\ContainerExceptionInterface'), $interfaces);
    }

    public function testDeclaresNoMethods()
    {
        $reflection = new \ReflectionClass('Interop\Container\Exception\ContainerException');
        $this->assertSame(array(), $reflection->getMethods());
    }

    public function testDocblock()
    {
        $reflection = new \ReflectionClass('Interop\Container\Exception\ContainerException');
        $docComment = $reflection->getDocComment();

        $this->assertContains(
            'Base interface representing a generic exception in a container.',
            $docComment
        );
    }

    public function testGenericExceptionIsCatchableAndIsNotNotFound()
    {
        try {
            throw new WitnessContainerException('generic container failure');
        } catch (\Interop\Container\Exception\ContainerException $exception) {
            $this->assertInstanceOf('Psr\Container\ContainerExceptionInterface', $exception);
            $this->assertInstanceOf('Exception', $exception);
            $this->assertNotInstanceOf('Interop\Container\Exception\NotFoundException', $exception);
            $this->assertNotInstanceOf('Psr\Container\NotFoundExceptionInterface', $exception);
        }
    }
}
