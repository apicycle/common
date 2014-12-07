<?php

namespace Apicycle\Common\Exception\Transport\Http;

use Apicycle\Common\Exception\RuntimeException;
use Exception;

/**
 * Designed to thrown when non-existing header is requested.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Exception\Transport\Http
 * @author  Etki <etki@etki.name>
 */
class MissingHeaderException extends RuntimeException
{
    /**
     * Header name.
     *
     * @type string
     * @since 0.1.0
     */
    protected $headerName;

    /**
     * Initializer.
     *
     * @param string    $headerName Header name.
     * @param null      $message    Exception message.
     * @param int       $code       Exception code.
     * @param Exception $previous   Previous exception.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        $headerName,
        $message = null,
        $code = 0,
        Exception $previous = null
    ) {
        $this->headerName = $headerName;
        if (!$message) {
            $message = sprintf('Header `%s` is missing', $headerName);
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns header name.
     *
     * @return string
     * @since 0.1.0
     */
    public function getHeaderName()
    {
        return $this->headerName;
    }
}
