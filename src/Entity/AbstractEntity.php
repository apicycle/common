<?php

namespace Apicycle\Common\Entity;

use Apicycle\Common\Exception\BadMethodCallException;
use Apicycle\Common\Exception\Entity\MissingMethodException;
use Apicycle\Common\Exception\Entity\MissingPropertyException;

/**
 * This class holds all basic functionality required by entity.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Entity
 * @author  Etki <etki@etki.name>
 */
abstract class AbstractEntity
{
    /**
     * Setter/getter magic caller.
     *
     * @param string $methodName Name of the method.
     * @param array  $args       Arguments  passed to method.
     *
     * @return $this
     * @since 0.1.0
     */
    public function __call($methodName, array $args)
    {
        if (strlen($methodName) < 4) {
            throw new MissingMethodException(get_class($this), $methodName);
        }
        $prefix = substr($methodName, 0, 3);
        $propertyName = strtolower(substr($methodName, 3, 1)) .
            substr($methodName, 4);
        if ($prefix !== 'get' && $prefix !== 'set') {
            throw new MissingMethodException(get_class($this), $methodName);
        }
        if (!property_exists($this, $propertyName)) {
            throw new MissingPropertyException(get_class($this), $propertyName);
        }
        if ($prefix === 'get') {
            return $this->$propertyName;
        }
        if (sizeof($args) === 0) {
            throw new BadMethodCallException(
                'Setters accepts exactly one argument, 0 received'
            );
        }
        $this->$propertyName = $args[0];
        return $this;
    }
}
