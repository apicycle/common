<?php

namespace Apicycle\Common\Exception;

use RuntimeException as SplRuntimeException;

/**
 * Library base runtime exception.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Exception
 * @author  Etki <etki@etki.name>
 */
class RuntimeException extends SplRuntimeException implements
    ApicycleExceptionInterface
{
}
