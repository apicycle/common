<?php

namespace Apicycle\Common\Tests\Unit\Transport\Http\Message;

use Apicycle\Common\Transport\Http\Message\StringMessageBody;
use Codeception\TestCase\Test;

/**
 * Tests all string message body functionality.
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Tests\Unit\Transport\Http\Message
 * @author  Etki <etki@etki.name>
 */
class StringMessageBodyTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @type string
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Apicycle\Common\Transport\Http\Message\StringMessageBody';

    /**
     * Returns empty instance.
     *
     * @return StringMessageBody
     * @since 0.1.0
     */
    public function getEmptyInstance()
    {
        $className = self::TESTED_CLASS;
        return new $className;
    }

    /**
     * Returns pre-filled instance.
     *
     * @return StringMessageBody
     * @since 0.1.0
     */
    public function getPreFilledInstance()
    {
        $className = self::TESTED_CLASS;
        return new $className(implode(' ', array_fill(0, 5, 'dummy')));
    }

    /**
     * Returns already closed instance of StringMessageBody.
     *
     * @return StringMessageBody
     * @since 0.1.0
     */
    public function getClosedInstance()
    {
        $instance = $this->getEmptyInstance();
        $instance->close();
        return $instance;
    }

    // data providers

    /**
     * Returns sample data for invalid seeking.
     *
     * @return array
     * @since 0.1.0
     */
    public function invalidSeekSamplesProvider()
    {
        $dummyString = 'dummy string';
        return array(
            array($dummyString, SEEK_SET, -1,),
            array($dummyString, SEEK_END, 1),
            array($dummyString, SEEK_CUR, 1),
            array($dummyString, SEEK_SET, strlen($dummyString) + 1),
        );
    }

    /**
     * Provides valid seek data in [dummyContent, seekType, offset,
     * expectedCursorPosition, expectedGetContentsOutput] format.
     *
     * @return array
     * @since 0.1.0
     */
    public function validSeekSamplesProvider()
    {
        $dummySample = 'dummy';
        $dummyString = str_repeat($dummySample, 2);
        return array(
            array(
                $dummyString,
                SEEK_SET,
                strlen($dummySample),
                strlen($dummySample),
                $dummySample
            ),
            array(
                $dummyString,
                SEEK_END,
                -1 * strlen($dummySample),
                strlen($dummySample),
                $dummySample,
            ),
            array(
                $dummyString,
                SEEK_CUR,
                -1 * strlen($dummySample),
                strlen($dummySample),
                $dummySample,
            )
        );
    }

    /**
     * Verifies that `getSize()` method returns correct results.
     *
     * @return void
     * @since 0.1.0
     */
    public function testGetSizeMethod()
    {
        $instance = $this->getEmptyInstance();
        $this->assertSame(0, $instance->getSize());
        $dummyString = 'dummy string';
        $instance->write($dummyString);
        $this->assertSame(strlen($dummyString), $instance->getSize());
    }

    /**
     * Verifies that cursor moves as reading and writing occurs.
     *
     * @return void
     * @since 0.1.0
     */
    public function testCursorMovement()
    {
        $instance = $this->getEmptyInstance();
        $this->assertSame(0, $instance->tell());
        $dummyString = 'dummy string';
        $instance->write($dummyString);
        $this->assertSame(strlen($dummyString), $instance->tell());
        $instance->seek(0);
        $readPoint = intval(strlen($dummyString) / 2);
        $instance->read($readPoint);
        $this->assertSame($readPoint, $instance->tell());
    }

    /**
     * Verifies that message reports itself as unseekable, unreadable and
     * unwritable after closing.
     *
     * @return void
     * @since 0.1.0
     */
    public function testClosing()
    {
        $instance = $this->getEmptyInstance();
        $this->assertTrue($instance->isSeekable());
        $this->assertTrue($instance->isWritable());
        $this->assertTrue($instance->isReadable());
        $instance->close();
        $this->assertFalse($instance->isSeekable());
        $this->assertFalse($instance->isWritable());
        $this->assertFalse($instance->isReadable());

        $instance = $this->getEmptyInstance();
        $instance->detach();
        $this->assertFalse($instance->isSeekable());
        $this->assertFalse($instance->isWritable());
        $this->assertFalse($instance->isReadable());
    }

    /**
     * Tests string conversion.
     *
     * @return void
     * @since 0.1.0
     */
    public function testStringConversion()
    {
        $dummyString = 'dummy string';
        $instance = $this->getEmptyInstance();
        $instance->write($dummyString);
        $this->assertSame($dummyString, (string)$instance);
    }

    /**
     * Verifies that reading produces correct results.
     *
     * @return void
     * @since 0.1.0
     */
    public function testRead()
    {
        $dummyString = 'dummy string';
        $instance = $this->getEmptyInstance();
        $instance->write($dummyString);
        $instance->seek(0);
        $breakpoint = 5;
        $this->assertSame(
            substr($dummyString, 0, $breakpoint),
            $instance->read($breakpoint)
        );
        $this->assertSame(
            substr($dummyString, $breakpoint),
            $instance->read(strlen($dummyString))
        );
    }

    /**
     * Tests pseudo-stream writing.
     *
     * @return void
     * @since 0.1.0
     */
    public function testWrite()
    {
        $dummyString = 'dummy string';
        $dummyReplacement = 'flummy';
        $expectedResult = substr(
            $dummyString,
            0,
            strlen($dummyString) - strlen($dummyReplacement)
        ) . $dummyReplacement;
        $instance = $this->getEmptyInstance();
        $instance->write($dummyString);
        $instance->seek(-strlen($dummyReplacement), SEEK_END);
        $this->assertSame(
            $instance->getSize() - strlen($dummyReplacement),
            $instance->tell()
        );
        $instance->write($dummyReplacement);
        $this->assertSame($expectedResult, (string) $instance);

        $breakpoint = 2;
        $expectedResult = substr($dummyString, 0, $breakpoint) .
            $dummyReplacement .
            substr($dummyString, $breakpoint + strlen($dummyReplacement));
        $instance = $this->getEmptyInstance();
        $instance->write($dummyString);
        $instance->seek($breakpoint);
        $instance->write($dummyReplacement);
        $this->assertSame($expectedResult, (string) $instance);
    }

    /**
     * Runs common seek scenarios that should produce no errors.
     *
     * @param string $dummyString            Dummy message content to use.
     * @param int    $seekType               Seek type.
     * @param int    $offset                 Offset to apply.
     * @param int    $expectedCursorPosition Expected cursor position after the
     *                                       seek.
     * @param string $expectedContents       Expected output for `getContents()`
     *                                       method (rest of the string from
     *                                       cursor till the end.
     *
     * @dataProvider validSeekSamplesProvider
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return void
     * @since 0.1.0
     */
    public function testSeek(
        $dummyString,
        $seekType,
        $offset,
        $expectedCursorPosition,
        $expectedContents
    ) {
        $instance = $this->getEmptyInstance();
        $instance->write($dummyString);
        $instance->seek($offset, $seekType);
        $this->assertSame($expectedCursorPosition, $instance->tell());
        $this->assertSame($expectedContents, $instance->getContents());
    }

    /**
     * Verifies that invalid seek input causes an exception.
     *
     * @param string $contents Body contents.
     * @param int    $seekType Seek type.
     * @param int    $offset   Seek offset.
     *
     * @dataProvider invalidSeekSamplesProvider
     * @expectedException \Apicycle\Common\Exception\Stream\InvalidSeekOffsetException
     *
     * @return void
     * @since 0.1.0
     */
    public function testInvalidSeekBreak($contents, $seekType, $offset)
    {
        $instance = $this->getEmptyInstance();
        $instance->write($contents);
        $instance->seek($offset, $seekType);
    }

    /**
     * Verifies that invalid seek type will throw an exception.
     *
     * @expectedException \Apicycle\Common\Exception\Stream\InvalidSeekTypeException
     *
     * @return void
     * @since 0.1.0
     */
    public function testInvalidSeekType()
    {
        $this->getPreFilledInstance()->seek(0, 12);
    }

    /**
     * Verifies that closed message won't let anyone seek the underlying stream.
     *
     * @expectedException \Apicycle\Common\Exception\Stream\UnseekableStreamException
     *
     * @return void
     * @since 0.1.0
     */
    public function testClosedMessageSeek()
    {
        $this->getClosedInstance()->seek(0);
    }

    /**
     * Verifies that closed message won't let anyone write.
     *
     * @expectedException \Apicycle\Common\Exception\Stream\UnwritableStreamException
     *
     * @return void
     * @since 0.1.0
     */
    public function testClosedMessageWrite()
    {
        $this->getClosedInstance()->write('dummy');
    }

    /**
     * Verifies that closed message won't let anyone write.
     *
     * @expectedException \Apicycle\Common\Exception\Stream\UnreadableStreamException
     *
     * @return void
     * @since 0.1.0
     */
    public function testClosedMessageGetContents()
    {
        $this->getClosedInstance()->getContents();
    }

    /**
     * Tests that `->eof()` method works correctly.
     *
     * @return void
     * @since 0.1.0
     */
    public function testEof()
    {
        $instance = $this->getPreFilledInstance();
        $this->assertTrue($instance->getSize() > 0);
        $instance->seek(0);
        $this->assertFalse($instance->eof());
        $instance->seek($instance->getSize());
        $this->assertTrue($instance->eof());
    }
}
