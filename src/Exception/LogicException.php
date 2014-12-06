<?php

namespace Apicycle\Common\Exception;

use LogicException as SplLogicException;

/**
 * 'You-doing-it-wrong' base exception.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Exception
 * @author  Etki <etki@etki.name>
 */
class LogicException extends SplLogicException implements
    ApicycleExceptionInterface
{
}
