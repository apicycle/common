<?php

namespace Apicycle\Common\Exception\Entity;

use Apicycle\Common\Exception\BadMethodCallException;
use Exception;

/**
 * Designed to be thrown whenever `__call` method is hit with method that is not
 * magically implemented.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Exception\Entity
 * @author  Etki <etki@etki.name>
 */
class MissingMethodException extends BadMethodCallException
{
    /**
     * Method that's gone missing.
     *
     * @type string
     * @since 0.1.0
     */
    protected $methodName;
    /**
     * Class name that misses particular method.
     *
     * @type string
     * @since 0.1.0
     */
    protected $className;

    /**
     * Initializer.
     *
     * @param string    $className  Name of the class where exception has been
     *                              thrown.
     * @param string    $methodName Name of the missing method.
     * @param string    $message    Message to set. Will be generated
     *                              automatically if omitted.
     * @param Exception $previous   Previous exception.
     * @param int       $code       Exception code, defaults to 0.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        $className,
        $methodName,
        $message = null,
        Exception $previous = null,
        $code = 0
    ) {
        $this->className = $className;
        $this->methodName = $methodName;
        if (!$message) {
            $message = sprintf(
                'Tried to access missing method `%s::%s`',
                $className,
                $methodName
            );
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns missing method name.
     *
     * @return string
     * @since 0.1.0
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * Returns class name that misses method.
     *
     * @return string
     * @since 0.1.0
     */
    public function getClassName()
    {
        return $this->className;
    }
}
