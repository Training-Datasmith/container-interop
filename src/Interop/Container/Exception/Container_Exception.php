<?php

declare (strict_types=1);
/**
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */
namespace Interop\Container\Exception;

use Psr\Container\Container_Exception_Interface as PsrContainerException;
/**
 * Base interface representing a generic exception in a container.
 */
interface Container_Exception extends Psr_Container_Exception
{
}