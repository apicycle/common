<?php

namespace Apicycle\Common\Utility\Transport\Http;

use Apicycle\Common\Utility\VariableTypeValidator;

/**
 * This class validates header names.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Utility\Transport\Http
 * @author  Etki <etki@etki.name>
 */
class HeaderNameValidator
{
    /**
     * Pattern that is used to match header names against.
     *
     * @type string
     * @since 0.1.0
     */
    const PATTERN = '~^[a-z\-_!\$]+$~i';

    /**
     * Validates header name and returns true or false for valid and invalid
     * headers.
     *
     * @param string $headerName Header name.
     *
     * @todo Current pattern does not conform with the spec, while it have to.
     *       Also don't forget to update the tests.
     *
     * @return bool True on valid header, false on invalid.
     * @since 0.1.0
     */
    public static function validateHeaderName($headerName)
    {
        return VariableTypeValidator::isCastableToString($headerName)
            && preg_match(self::PATTERN, trim((string) $headerName));
    }
}
