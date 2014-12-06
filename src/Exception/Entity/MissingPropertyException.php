<?php

namespace Apicycle\Common\Exception\Entity;

use Apicycle\Common\Exception\BadMethodCallException;
use Exception;

/**
 * Designed to be thrown when missing property is hit.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Exception\Entity
 * @author  Etki <etki@etki.name>
 */
class MissingPropertyException extends BadMethodCallException
{
    /**
     * Initializer.
     *
     * @param string    $className    Name of the class with missing property.
     * @param int       $propertyName Name of the missing property.
     * @param null      $message      Exception message.
     * @param int       $code         Exception code.
     * @param Exception $previous     Previous exception.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        $className,
        $propertyName,
        $message = null,
        $code = 0,
        Exception $previous = null
    ) {
        if (!$message) {
            $message = sprintf(
                'Tried to access missing method `%s::%s`',
                $className,
                $propertyName
            );
        }
        parent::__construct($message, $code, $previous);
    }
}
