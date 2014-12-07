<?php

namespace Apicycle\Common\Transport\Http\Message;

use Apicycle\Common\Exception\InvalidArgumentTypeException;
use Apicycle\Common\Exception\Transport\Http\InvalidHeaderNameException;
use Apicycle\Common\Exception\Transport\Http\InvalidHeaderValueException;
use Apicycle\Common\Exception\Transport\Http\MissingHeaderException;
use Apicycle\Common\Utility\Transport\Http\HeaderNameNormalizer;
use Apicycle\Common\Utility\Transport\Http\HeaderNameValidator;
use Apicycle\Common\Utility\VariableTypeValidator;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamableInterface;

/**
 * Abstract HTTP message class with basic functionality.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Transport\Http
 * @author  Etki <etki@etki.name>
 */
abstract class AbstractMessage implements MessageInterface
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
     * Request body.
     *
     * @type StreamableInterface
     * @since 0.1.0
     */
    protected $body;

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
     * @throws InvalidArgumentTypeException Thrown if invalid argument is
     *                                      provided as header name.
     * @throws InvalidHeaderNameException   Thrown if header name doesn't
     *                                      conform with HTTP spec.
     * @throws InvalidHeaderValueException  Thrown if invalid header value is
     *                                      provided.
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
     * @throws MissingHeaderException Thrown if provided header isn't present in
     *                                message headers.
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
     * @throws InvalidArgumentTypeException Thrown if invalid argument is
     *                                      provided as header name.
     * @throws InvalidHeaderNameException   Thrown if header name doesn't
     *                                      conform with HTTP spec.
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
     * @throws InvalidArgumentTypeException Thrown if invalid argument is
     *                                      provided as header name.
     * @throws InvalidHeaderNameException   Thrown if header name doesn't
     *                                      conform with HTTP spec.
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
