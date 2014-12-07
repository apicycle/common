<?php
namespace Apicycle\Common\Tests\Unit\Utility\Transport\Http;

use Apicycle\Common\Utility\Transport\Http\HeaderNameNormalizer;
use Codeception\TestCase\Test;

/**
 * Tests header normalizer.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Utility\Transport\Http
 * @author  Etki <etki@etki.name>
 */
class HeaderNameNormalizerTest extends Test
{
    /**
     * Provides non-normalized header names in pairs with expected
     * normalization result.
     *
     * @return string[][]
     * @since 0.1.0
     */
    public function headerNameProvider()
    {
        return array(
            array('X-Powered-By', 'X-Powered-By',),
            array('   Lame_header', 'Lame-Header',),
            array('SET_COOKIE', 'Set-Cookie',),
        );
    }

    // tests

    /**
     * Verifies that header name normalization works as expected.
     *
     * @param string $headerName     Header name.
     * @param string $expectedResult Expected normalization result.
     *
     * @dataProvider headerNameProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testHeaderNameNormalization($headerName, $expectedResult)
    {
        $this->assertSame(
            $expectedResult,
            HeaderNameNormalizer::normalize($headerName)
        );
    }

}