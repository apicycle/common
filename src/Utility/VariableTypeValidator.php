<?php

namespace Apicycle\Common\Utility;

/**
 * This general class helps with type and casting options validation.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Utility
 * @author  Etki <etki@etki.name>
 */
class VariableTypeValidator
{
    /**
     * Private constructor, only static methods are allowed.
     *
     * @codeCoverageIgnore
     *
     * @since 0.1.0
     */
    private function __construct()
    {
    }
    /**
     * Tells if variable is castable to string.
     *
     * @param mixed $variable Variable to check.
     *
     * @return bool True if variable is castable to string, false otherwise.
     * @since 0.1.0
     */
    public static function isCastableToString($variable)
    {
        if (is_string($variable) || is_int($variable) || is_float($variable)) {
            return true;
        }
        if (is_object($variable) && method_exists($variable, '__toString')) {
            return true;
        }
        return false;
    }
}
