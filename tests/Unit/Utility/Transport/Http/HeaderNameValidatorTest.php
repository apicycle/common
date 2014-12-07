<?php
namespace Apicycle\Common\Tests\Unit\Utility\Transport\Http;

use Apicycle\Common\Utility\Transport\Http\HeaderNameValidator;
use Codeception\TestCase\Test;

/**
 * Tests HTTP header name validator.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Tests\Unit\Utility\Transport\Http
 * @author  Etki <etki@etki.name>
 */
class HeaderNameValidatorTest extends Test
{
    /**
     * Returns valid HTTP header names.
     *
     * @return string[][]
     * @since 0.1.0
     */
    public function validHeaderNamesProvider()
    {
        return array(
            array('Content-Type',),
            array('TEST_ME',),
            array('I-Am-A-$-Dollar!',),
        );
    }

    /**
     * Provides invalid HTTP header names.
     *
     * @return string[][]
     * @since 0.1.0
     */
    public function invalidHeaderNamesProvider()
    {
        return array(
            array('Invalid header',),
            array('Invalid(header)',),
            array('Invalid?',),
        );
    }

    // tests

    /**
     * Verifies that valid header names successfully pass checks.
     *
     * @param string $validHeaderName Header name.
     *
     * @dataProvider validHeaderNamesProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testValidHeaderNamesValidation($validHeaderName)
    {
        $this->assertTrue(
            HeaderNameValidator::validateHeaderName($validHeaderName)
        );
    }

    /**
     * Verifies that invalid header names don't pass the check.
     *
     * @param string $invalidHeaderName Invalid HTTP header name.
     *
     * @dataProvider invalidHeaderNamesProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testInvalidHeaderNamesValidation($invalidHeaderName)
    {
        $this->assertFalse(
            HeaderNameValidator::validateHeaderName($invalidHeaderName)
        );
    }
}
