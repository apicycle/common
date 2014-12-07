<?php

namespace Apicycle\Common\Exception\Transport\Http;

use Apicycle\Common\Exception\InvalidArgumentException;
use Exception;

/**
 * Designed to be thrown whenever invalid header name is encountered.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Exception\Transport\Http
 * @author  Etki <etki@etki.name>
 */
class InvalidHeaderNameException extends InvalidArgumentException
{
    /**
     * Vinovnik torzhestva.
     *
     * @type string
     * @since 0.1.0
     */
    protected $headerName;

    /**
     * Simple initializer.
     *
     * @param string|mixed $headerName Invalid header name. May be virtually of
     *                                 any type, though in correct code it won't
     *                                 be anything but string / object with
     *                                 `__toString()` method.
     * @param string|null  $message    Exception message. Will be generated
     *                                 automatically if omitted.
     * @param int          $code       Exception code.
     * @param Exception    $previous   Previous exception (if any).
     *
     * @return self New instance.
     * @since 0.1.0
     */
    public function __construct(
        $headerName,
        $message = null,
        $code = 0,
        Exception $previous = null
    ) {
        $this->headerName = $headerName;
        $message = $message ? $message : $this->composeMessage($headerName);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Retrieves invalid header name.
     *
     * @return string|mixed Invalid header name.
     * @since 0.1.0
     */
    public function getHeaderName()
    {
        return $this->headerName;
    }

    /**
     * Generates exception message using header name.
     *
     * @param string|mixed $headerName Header name. Since exception can be
     *                                 caused by virtually anything passed to
     *                                 method, header name may not be a string.
     *
     * @return string Message.
     * @since 0.1.0
     */
    protected function composeMessage($headerName)
    {
        $isConvertibleObject = is_object($headerName)
            && method_exists($headerName, '__toString');
        if (!is_string($headerName) || !$isConvertibleObject) {
            return sprintf(
                'Variable of type `%s` couldn\'t be converted to valid ' .
                'header name',
                gettype($headerName)
            );
        }
        return sprintf('`%s` is not a valid header name', $headerName);
    }
}
