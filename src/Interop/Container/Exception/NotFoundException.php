<?php

declare (strict_types=1);
/**
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */
namespace Interop\Container\Exception;

use Psr\Container\Not_Found_Exception_Interface as PsrNotFoundException;
/**
 * No entry was found in the container.
 */
interface Not_Found_Exception extends Container_Exception, Psr_Not_Found_Exception
{
}