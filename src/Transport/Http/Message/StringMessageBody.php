<?php

namespace Apicycle\Common\Transport\Http\Message;

use Apicycle\Common\Exception\Stream\InvalidSeekOffsetException;
use Apicycle\Common\Exception\Stream\InvalidSeekTypeException;
use Apicycle\Common\Exception\Stream\UnreadableStreamException;
use Apicycle\Common\Exception\Stream\UnseekableStreamException;
use Apicycle\Common\Exception\Stream\UnwritableStreamException;
use Psr\Http\Message\StreamableInterface;

/**
 * Basic message body realization. Please note that it **doesn't** implement any
 * real streaming and might consume lots of memory if you operate with big
 * requests.
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Transport\Http
 * @author  Etki <etki@etki.name>
 */
class StringMessageBody implements StreamableInterface
{
    /**
     * The real message body.
     *
     * @type string
     * @since 0.1.0
     */
    protected $body;
    /**
     * Current cursor position.
     *
     * @type int
     * @since 0.1.0
     */
    protected $cursor;
    /**
     * Message length.
     *
     * @type int
     * @since 0.1.0
     */
    protected $length;

    /**
     * Initializer.
     *
     * @param string $body Initial body message to set.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct($body = '')
    {
        $this->body = $body;
        $this->length = strlen($body);
        $this->cursor = 0;
    }

    /**
     * Tells if current message is readable atm.
     *
     * @return bool
     * @since 0.1.0
     */
    public function isReadable()
    {
        return isset($this->body);
    }

    /**
     * Tells if current message is seekable.
     *
     * @return bool
     * @since 0.1.0
     */
    public function isSeekable()
    {
        return isset($this->body);
    }

    /**
     * Tells if current message is writable.
     *
     * @return bool
     * @since 0.1.0
     */
    public function isWritable()
    {
        return isset($this->body);
    }

    /**
     * Returns stream size.
     *
     * @throws UnreadableStreamException Thrown whenever this method is called
     *                                   on closed instance.
     *
     * @return int
     * @since 0.1.0
     */
    public function getSize()
    {
        $this->assertIsReadable();
        return $this->length;
    }

    /**
     * Returns whole stream contents.
     *
     * @throws UnreadableStreamException Thrown whenever this method is called
     *                                   on closed instance.
     *
     * @return string Stream contents.
     * @since 0.1.0
     */
    public function __toString()
    {
        $this->assertIsReadable();
        return $this->body;
    }

    /**
     * Closes stream.
     *
     * @return void
     * @since 0.1.0
     */
    public function close()
    {
        $this->body = null;
        $this->length = null;
    }

    /**
     * Detaches internal resources, has the same effect as `::close()`.
     *
     * @return null
     * @since 0.1.0
     */
    public function detach()
    {
        $this->close();
        return null;
    }

    /**
     * Presents current cursor position.
     *
     * @throws UnreadableStreamException Thrown whenever this method is called
     *                                   on closed instance.
     *
     * @return int Cursor position.
     * @since 0.1.0
     */
    public function tell()
    {
        $this->assertIsReadable();
        return $this->cursor;
    }

    /**
     * Tells if stream has been read to the end.
     *
     * @throws UnreadableStreamException Thrown whenever this method is called
     *                                   on closed instance.
     *
     * @return bool
     * @since 0.1.0
     */
    public function eof()
    {
        $this->assertIsReadable();
        return $this->length === $this->cursor;
    }

    /**
     * Moves cursor to specified offset.
     *
     * @param int $offset Offset value.
     * @param int $whence Seek type, should be one of the `SEEK_SET`, `SEEK_CUR`
     *                    and `SEEK_END` constants.
     *
     * @throws UnseekableStreamException  Thrown if underlying stream isn't
     *                                    seekable.
     * @throws InvalidSeekTypeException   Thrown if invalid seek type is
     *                                    provided.
     * @throws InvalidSeekOffsetException Thrown if invalid seek offset is
     *                                    provided.
     *
     * @return true Always returns true, because all erroneous calls cause an
     *              exception.
     * @since 0.1.0
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        $this->assertIsSeekable();
        switch ($whence) {
            case SEEK_SET:
                $position = $offset;
                break;
            case SEEK_CUR:
                $position = $this->cursor + $offset;
                break;
            case SEEK_END:
                $position = $this->length + $offset;
                break;
            default:
                throw new InvalidSeekTypeException('whence', $whence);
        }
        if ($position < 0 || $position > $this->length) {
            throw new InvalidSeekOffsetException(
                'offset',
                $offset,
                $this->length
            );
        }
        $this->cursor = $position;
    }

    /**
     * Writes string into stream at current position.
     *
     * @param string $string Data to write into stream.
     *
     * @return int Number of written bytes.
     * @since 0.1.0
     */
    public function write($string)
    {
        $this->assertIsWritable();
        $inputLength = strlen($string);
        $prefix = substr($this->body, 0, $this->cursor);
        $suffix = '';
        if ($this->cursor + $inputLength < $this->length) {
            $suffix = substr($this->body, $this->cursor + $inputLength);
        }
        $this->body = $prefix . $string . $suffix;
        $this->length = max($this->length, $this->cursor + $inputLength);
        $this->cursor += $inputLength;
        return $inputLength;
    }

    /**
     * Attempts to read `$length` bytes from cursor (reads smaller amount if
     * hits stream end during read).
     *
     * @param int $length Amount of bytes to read.
     *
     * @return string
     * @since 0.1.0
     */
    public function read($length)
    {
        $this->assertIsReadable();
        $data = substr($this->body, $this->cursor, $length);
        $this->cursor = min($this->length, $this->cursor + $length);
        return $data;
    }

    /**
     * Returns all what's left in the stream.
     *
     * @return string
     * @since 0.1.0
     */
    public function getContents()
    {
        $this->assertIsReadable();
        $contents = substr($this->body, $this->cursor);
        $this->cursor = $this->length - 1;
        return $contents;
    }

    /**
     * This method is intended to return stream metadata, but it just returns
     * null.
     *
     * @param null|string $key Metadata key.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @codeCoverageIgnore
     *
     * @return null Always null.
     * @since 0.1.0
     */
    public function getMetadata($key = null)
    {
        return null;
    }

    /**
     * Throws exception if stream isn't readable.
     *
     * @throws UnreadableStreamException
     *
     * @inline
     *
     * @return void
     * @since 0.1.0
     */
    protected function assertIsReadable()
    {
        if (!$this->isReadable()) {
            throw new UnreadableStreamException;
        }
    }

    /**
     * Throws exception if stream isn't writable.
     *
     * @throws UnwritableStreamException
     *
     * @inline
     *
     * @return void
     * @since 0.1.0
     */
    protected function assertIsWritable()
    {
        if (!$this->isWritable()) {
            throw new UnwritableStreamException;
        }
    }

    /**
     * Throws exception if stream isn't seekable.
     *
     * @throws UnseekableStreamException
     *
     * @inline
     *
     * @return void
     * @since 0.1.0
     */
    protected function assertIsSeekable()
    {
        if (!$this->isSeekable()) {
            throw new UnseekableStreamException;
        }
    }
}
