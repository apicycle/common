<?php

namespace Apicycle\Common\Exception;

use InvalidArgumentException as SplInvalidArgumentException;

/**
 * Designed to be thrown whenever invalid argument is provided.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Exception
 * @author  Etki <etki@etki.name>
 */
class InvalidArgumentException extends SplInvalidArgumentException implements
    ApicycleExceptionInterface
{
}
