<?php

namespace Apicycle\Common\Exception;

use BadMethodCallException as SplBadMethodCallException;

/**
 * Serves same purpose as original BadMethodCallException, but can be caught as
 * a child to ApicycleException.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Exception
 * @author  Etki <etki@etki.name>
 */
class BadMethodCallException extends SplBadMethodCallException implements
    ApicycleExceptionInterface
{
}
