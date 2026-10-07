<?php

namespace Interop\Container\Test;

use Interop\Container\Test\Fixtures\WitnessContainer;

class ContainerInterfaceTest extends \PHPUnit_Framework_TestCase
{
    public function testInterfaceExistsAndIsInterface()
    {
        $this->assertTrue(interface_exists('Interop\Container\ContainerInterface'));
        $this->assertFalse(class_exists('Interop\Container\ContainerInterface', false));

        $reflection = new \ReflectionClass('Interop\Container\ContainerInterface');
        $this->assertTrue($reflection->isInterface());

        $expectedFile = dirname(__DIR__) . '/src/Interop/Container/ContainerInterface.php';
        $this->assertSame(realpath($expectedFile), realpath($reflection->getFileName()));
    }

    public function testExtendsPsrContainerInterfaceOnly()
    {
        $reflection = new \ReflectionClass('Interop\Container\ContainerInterface');
        $interfaces = $reflection->getInterfaceNames();
        sort($interfaces);

        $this->assertSame(array('Psr\Container\ContainerInterface'), $interfaces);
    }

    public function testDeclaresNoMethodsOfItsOwn()
    {
        $reflection = new \ReflectionClass('Interop\Container\ContainerInterface');
        $methodNames = array();

        foreach ($reflection->getMethods() as $method) {
            $this->assertSame(
                'Psr\Container\ContainerInterface',
                $method->getDeclaringClass()->getName()
            );
            $methodNames[] = $method->getName();
        }

        sort($methodNames);
        $this->assertSame(array('get', 'has'), $methodNames);

        foreach ($reflection->getMethods() as $method) {
            $this->assertTrue($method->isPublic());
        }
    }

    public function testInheritedGetSignature()
    {
        $method = new \ReflectionMethod('Psr\Container\ContainerInterface', 'get');
        $parameters = $method->getParameters();

        $this->assertCount(1, $parameters);
        $this->assertSame('id', $parameters[0]->getName());
        $this->assertFalse($parameters[0]->isPassedByReference());
        $this->assertFalse($parameters[0]->isOptional());

        if (method_exists($parameters[0], 'hasType') && $parameters[0]->hasType()) {
            $this->assertSame('string', $this->parameterTypeName($parameters[0]));
        }

        if (method_exists($method, 'hasReturnType')) {
            $this->assertFalse($method->hasReturnType());
        }
    }

    public function testInheritedHasSignature()
    {
        $method = new \ReflectionMethod('Psr\Container\ContainerInterface', 'has');
        $parameters = $method->getParameters();

        $this->assertCount(1, $parameters);
        $this->assertSame('id', $parameters[0]->getName());
        $this->assertFalse($parameters[0]->isPassedByReference());
        $this->assertFalse($parameters[0]->isOptional());

        if (method_exists($parameters[0], 'hasType') && $parameters[0]->hasType()) {
            $this->assertSame('string', $this->parameterTypeName($parameters[0]));
        }

        if (method_exists($method, 'hasReturnType')) {
            $this->assertFalse($method->hasReturnType());
        }
    }

    public function testDocblock()
    {
        $reflection = new \ReflectionClass('Interop\Container\ContainerInterface');
        $docComment = $reflection->getDocComment();

        $this->assertContains(
            'Describes the interface of a container that exposes methods to read its entries.',
            $docComment
        );
    }

    public function testWitnessIsBothInteropAndPsrContainer()
    {
        $container = new WitnessContainer(array('foo' => 'bar'));

        $this->assertInstanceOf('Interop\Container\ContainerInterface', $container);
        $this->assertInstanceOf('Psr\Container\ContainerInterface', $container);
    }

    public function testMissingIdThrowsNotFoundHierarchy()
    {
        $container = new WitnessContainer();

        try {
            $container->get('missing');
            $this->fail('Expected an exception to be thrown.');
        } catch (\Exception $exception) {
            $this->assertInstanceOf('Interop\Container\Exception\NotFoundException', $exception);
            $this->assertInstanceOf('Interop\Container\Exception\ContainerException', $exception);
            $this->assertInstanceOf('Psr\Container\NotFoundExceptionInterface', $exception);
            $this->assertInstanceOf('Psr\Container\ContainerExceptionInterface', $exception);
            $this->assertInstanceOf('Exception', $exception);
        }
    }

    public function testKnownBrokenIdThrowsContainerExceptionThatIsNotNotFound()
    {
        $container = new WitnessContainer();

        $this->assertTrue($container->has('broken'));

        try {
            $container->get('broken');
            $this->fail('Expected an exception to be thrown.');
        } catch (\Exception $exception) {
            $this->assertInstanceOf('Interop\Container\Exception\ContainerException', $exception);
            $this->assertInstanceOf('Psr\Container\ContainerExceptionInterface', $exception);
            $this->assertNotInstanceOf('Interop\Container\Exception\NotFoundException', $exception);
            $this->assertNotInstanceOf('Psr\Container\NotFoundExceptionInterface', $exception);
        }
    }

    private function parameterTypeName(\ReflectionParameter $parameter)
    {
        $type = $parameter->getType();

        if (method_exists($type, 'getName')) {
            return $type->getName();
        }

        return (string) $type;
    }
}
