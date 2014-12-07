<?php
namespace Apicycle\Common\Tests\Unit\Utility;

use Apicycle\Common\Utility\VariableTypeValidator;
use Codeception\TestCase\Test;
use stdClass;
use Exception;

/**
 * Test for VariableTypeValidator type.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Tests\Unit\Utility
 * @author  Etki <etki@etki.name>
 */
class VariableTypeValidatorTest extends Test
{
    /**
     * Returns list of variables castable to string.
     *
     * @return array
     * @since 0.1.0
     */
    public function stringCastableVariableProvider()
    {
        return array(
            array(0,),
            array(0.0,),
            array('string',),

            /* first class with `__toString()` i've dug out */
            array(new Exception('string'),),
        );
    }

    /**
     * Returns list of variables not castable to string.
     *
     * @return array
     * @since 0.1.0
     */
    public function stringIncastableVariableProvider()
    {
        return array(
            array(new stdClass,),
            array(false,),
            array(array(),),
            array(null,),
        );
    }

    // tests
    /**
     *
     *
     * @param $variable
     *
     * @dataProvider stringCastableVariableProvider
     *
     * @return void
     * @since
     */
    public function testStringCastableVariablesValidation($variable)
    {
        $this->assertTrue(VariableTypeValidator::isCastableToString($variable));
    }

    /**
     * Verifies that string-incastable variable won't pass check.
     *
     * @param mixed $variable Incastable to string variable.
     *
     * @dataProvider stringIncastableVariableProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testStringIncastableVariablesValidation($variable)
    {
        $this->assertFalse(
            VariableTypeValidator::isCastableToString($variable)
        );
    }

}