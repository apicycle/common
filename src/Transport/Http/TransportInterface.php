<?php

namespace Apicycle\Common\Transport\Http;

use Psr\Http\Message\OutgoingRequestInterface;
use Psr\Http\Message\IncomingResponseInterface;

/**
 * This interface standardizes transport.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Apicycle\Common\Transport\Http
 * @author  Etki <etki@etki.name>
 */
interface TransportInterface
{
    /**
     * Performs request and returns a response.
     *
     * @param OutgoingRequestInterface $outgoingRequest Request.
     *
     * @return IncomingResponseInterface Response.
     * @since 0.1.0
     */
    public function performRequest(OutgoingRequestInterface $outgoingRequest);
}
