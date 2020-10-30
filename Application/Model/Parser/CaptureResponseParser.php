<?php

namespace Arvato\AfterpayModule\Application\Model\Parser;

/**
 * Class CaptureResponseParser: Parser for the capture response.
 */
class CaptureResponseParser extends Parser
{
    protected $fields = [
        'capturedAmount',
        'authorizedAmount',
        'remainingAuthorizedAmount',
        'captureNumber',
        'captureNumber',
    ];
}
