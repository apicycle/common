<?php

namespace Apicycle\Common\Transport\Http;

use Apicycle\Common\Exception\InvalidArgumentTypeException;
use Apicycle\Common\Exception\Transport\Http\InvalidHeaderNameException;
use Apicycle\Common\Exception\Transport\Http\InvalidHeaderValueException;
use Apicycle\Common\Exception\Transport\Http\MissingHeaderException;
use Apicycle\Common\Utility\Transport\Http\HeaderNameNormalizer;
use Apicycle\Common\Utility\Transport\Http\HeaderNameValidator;
use Apicycle\Common\Utility\VariableTypeValidator;
use Psr\Http\Message\OutgoingRequestInterface;
use Psr\Http\Message\StreamableInterface;

/**
 * PSR-7 implementation.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Transport\Http
 * @author  Etki <etki@etki.name>
 */
class OutgoingRequest implements OutgoingRequestInterface
{
    /**
     * Used HTTP version.
     *
     * @type string
     * @since 0.1.0
     */
    protected $protocolVersion;
    /**
     * Request HTTP headers.
     *
     * @type string[][]
     * @since 0.1.0
     */
    protected $headers = array();
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
     * Request body.
     *
     * @type StreamableInterface
     * @since 0.1.0
     */
    protected $body;
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
     * Sets HTTP version.
     *
     * @param string $protocolVersion HTTP version.
     *
     * @return void
     * @since 0.1.0
     */
    public function setProtocolVersion($protocolVersion)
    {
        $this->protocolVersion = $protocolVersion;
    }

    /**
     * Retrieves used HTTP version.
     *
     * @return string
     * @since 0.1.0
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

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

    /**
     * Sets request body.
     *
     * @param StreamableInterface $body Request body.
     *
     * @return void
     * @since 0.1.0
     */
    public function setBody(StreamableInterface $body)
    {
        $this->body = $body;
    }

    /**
     * Returns request body (if any).
     *
     * @return null|StreamableInterface
     * @since 0.1.0
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets header value(s).
     *
     * @param string           $header Header name.
     * @param string|\string[] $values Header value(s).
     *
     * @return void
     * @since 0.1.0
     */
    public function setHeader($header, $values)
    {
        if ($this->hasHeader($header)) {
            $this->removeHeader($header);
        }
        $this->addHeader($header, $values);
    }

    /**
     * Adds header value(s).
     *
     * @param string          $header Header name.
     * @param string|string[] $value Header value(s).
     *
     * @return void
     * @since 0.1.0
     */
    public function addHeader($header, $value)
    {
        if (!VariableTypeValidator::isCastableToString($header)) {
            throw new InvalidArgumentTypeException('header', $header, 'string');
        }
        if (!HeaderNameValidator::validateHeaderName($header)) {
            throw new InvalidHeaderNameException($header);
        }
        $headerName = HeaderNameNormalizer::normalize($header);
        if (!isset($this->headers[$headerName])) {
            $this->headers[$headerName] = array();
        }
        $values = is_array($value) ? $value : array($value,);
        foreach ($values as $singleValue) {
            if (!VariableTypeValidator::isCastableToString($singleValue)) {
                throw new InvalidHeaderValueException($header, $singleValue);
            }
            $this->headers[$headerName][] = $singleValue;
        }
    }

    /**
     * Retrieves header value.
     *
     * @param string $header Header name.
     *
     * @return string Header value.
     * @since 0.1.0
     */
    public function getHeader($header)
    {
        if (!$this->hasHeader($header)) {
            throw new MissingHeaderException($header);
        }
        $headerName = HeaderNameNormalizer::normalize($header);
        return $this->headers[$headerName][0];
    }

    /**
     * Retrieves header values.
     *
     * @param string $header Header name.
     *
     * @return string[] Header values.
     * @since 0.1.0
     */
    public function getHeaderAsArray($header)
    {
        if (!$this->hasHeader($header)) {
            throw new MissingHeaderException($header);
        }
        $headerName = HeaderNameNormalizer::normalize($header);
        return $this->headers[$headerName];
    }

    /**
     * Tells if header has been set.
     *
     * @param string $header Header name.
     *
     * @return bool True if header exists, false otherwise.
     * @since 0.1.0
     */
    public function hasHeader($header)
    {
        if (!VariableTypeValidator::isCastableToString($header)) {
            throw new InvalidArgumentTypeException('header', $header, 'string');
        }
        if (!HeaderNameValidator::validateHeaderName($header)) {
            throw new InvalidHeaderNameException($header);
        }
        $headerName = HeaderNameNormalizer::normalize($header);
        return isset($this->headers[$headerName])
            && $this->headers[$headerName];
    }

    /**
     * Retrieves all headers.
     *
     * @return string[][] Headers in [headerName => [headerValues]] form.
     * @since 0.1.0
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Removes single header.
     *
     * @param string $header Header name.
     *
     * @return void
     * @since 0.1.0
     */
    public function removeHeader($header)
    {
        if (!VariableTypeValidator::isCastableToString($header)) {
            throw new InvalidArgumentTypeException('header', $header, 'string');
        }
        if (!HeaderNameValidator::validateHeaderName($header)) {
            throw new InvalidHeaderNameException($header);
        }
        $headerName = HeaderNameNormalizer::normalize($header);
        unset($this->headers[$headerName]);
    }
}
