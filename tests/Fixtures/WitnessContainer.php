<?php

namespace Interop\Container\Test\Fixtures;

use Interop\Container\ContainerInterface;

class WitnessContainer implements ContainerInterface
{
    private $entries = array();

    public function __construct(array $entries = array())
    {
        $this->entries = $entries;
    }

    public function has($id)
    {
        if ($id === 'broken') {
            return true;
        }

        return array_key_exists($id, $this->entries);
    }

    public function get($id)
    {
        if ($id === 'broken') {
            throw new WitnessContainerException('broken entry');
        }

        if (!array_key_exists($id, $this->entries)) {
            throw new WitnessNotFoundException('not found: ' . $id);
        }

        return $this->entries[$id];
    }
}
