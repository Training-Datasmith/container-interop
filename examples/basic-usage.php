<?php

declare(strict_types=1);

/**
 * Example: Using container-interop interfaces with a PSR-11 compatible container.
 *
 * container-interop is a compatibility shim — its interfaces extend psr/container.
 * Any PSR-11 container also satisfies Interop\Container\ContainerInterface.
 */

use Interop\Container\Container_Interface;
use Interop\Container\Exception\Not_Found_Exception;

// A minimal PSR-11 / container-interop compatible container for demonstration
class Simple_Container implements Container_Interface
{
    /** @var array<string, mixed> */
    private array $bindings = [];

    /**
     * Register a value by id.
     *
     * @param string $id  Service identifier
     * @param mixed  $value  The resolved value or factory callable
     */
    public function bind(string $id, mixed $value): void
    {
        $this->bindings[$id] = $value;
    }

    /** {@inheritdoc} */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new class("No entry found for '{$id}'") extends \RuntimeException implements Not_Found_Exception {};
        }

        $value = $this->bindings[$id];
        return is_callable($value) ? $value() : $value;
    }

    /** {@inheritdoc} */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->bindings);
    }
}

// --- Usage ---

$container = new Simple_Container();
$container->bind('db.dsn', 'sqlite::memory:');
$container->bind('app.name', fn () => 'My Application');

echo $container->get('app.name') . PHP_EOL; // My Application
echo $container->get('db.dsn') . PHP_EOL;   // sqlite::memory:

// Type-checking against the interop interface
assert($container instanceof Container_Interface);
echo 'Container satisfies Interop\Container\ContainerInterface' . PHP_EOL;
