<?php

namespace Apicycle\Common\Utility\Transport\Http;

/**
 * This class helps with header name normalization.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Utility\Transport\Http
 * @author  Etki <etki@etki.name>
 */
class HeaderNameNormalizer
{
    /**
     * Private constructor, only static methods are allowed.
     *
     * @since 0.1.0
     */
    private function __construct()
    {
    }

    /**
     * Normalizes header name to `Header-Name` format.
     *
     * @param string $headerName Arbitrary header name.
     *
     * @return string Normalized header name.
     * @since 0.1.0
     */
    public static function normalize($headerName)
    {
        $hyphened = str_replace('_', '-', trim($headerName));
        $words = explode('-', $hyphened);
        $words = array_map('strtolower', $words);
        $words = array_map('ucfirst', $words);
        return implode('-', $words);
    }
}
