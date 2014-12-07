<?php

namespace Apicycle\Common\Transport\Http;

use Apicycle\Common\Transport\Http\Message\AbstractMessage;
use Psr\Http\Message\OutgoingRequestInterface;

/**
 * PSR-7 implementation.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Transport\Http
 * @author  Etki <etki@etki.name>
 */
class OutgoingRequest extends AbstractMessage implements
    OutgoingRequestInterface
{
    /**
     * Request URL.
     *
     * @type string
     * @since 0.1.0
     */
    protected $url;
    /**
     * Request HTTP method.
     *
     * @type string
     * @since 0.1.0
     */
    protected $method;
    /**
     * Constant for HTTP `GET` method.
     *
     * @type string
     * @since 0.1.0
     */
    const METHOD_GET = 'GET';
    /**
     * Constant for HTTP `POST` method.
     *
     * @type string
     * @since 0.1.0
     */
    const METHOD_POST = 'POST';
    /**
     * Constant for HTTP `PUT` method.
     *
     * @type string
     * @since 0.1.0
     */
    const METHOD_PUT = 'PUT';
    /**
     * Constant for HTTP `PATCH` method.
     *
     * @type string
     * @since 0.1.0
     */
    const METHOD_PATCH = 'PATCH';
    /**
     * Constant for HTTP `DELETE` method.
     *
     * @type string
     * @since 0.1.0
     */
    const METHOD_DELETE = 'DELETE';
    /**
     * Constant for HTTP `CONNECT` method.
     *
     * @type string
     * @since 0.1.0
     */
    const METHOD_CONNECT = 'CONNECT';
    /**
     * Constant for HTTP `OPTIONS` method.
     *
     * @type string
     * @since 0.1.0
     */
    const METHOD_OPTIONS = 'OPTIONS';
    /**
     * Constant for HTTP `HEAD` method.
     *
     * @type string
     * @since 0.1.0
     */
    const METHOD_HEAD = 'HEAD';
    /**
     * Constant for HTTP `TRACE` method.
     *
     * @type string
     * @since 0.1.0
     */
    const METHOD_TRACE = 'TRACE';

    /**
     * Sets request HTTP method.
     *
     * @param string $method Method to set.
     *
     * @return void
     * @since 0.1.0
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Retrieves request HTTP method.
     *
     * @return string
     * @since 0.1.0
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets request url.
     *
     * @param string $url
     *
     * @return void
     * @since 0.1.0
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Returns request URL.
     *
     * @return string
     * @since 0.1.0
     */
    public function getUrl()
    {
        return $this->url;
    }
}
