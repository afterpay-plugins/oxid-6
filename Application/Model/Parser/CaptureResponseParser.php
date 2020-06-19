<?php

/**
 *
*
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Parser;

/**
 * Class CaptureResponseParser: Parser for the capture response.
 */
class CaptureResponseParser extends \Arvato\AfterpayModule\Application\Model\Parser\Parser
{

    public function parse(\stdClass $object)
    {
        $this->fields = [
            'capturedAmount',
            'authorizedAmount',
            'remainingAuthorizedAmount',
            'captureNumber',
            'captureNumber',
        ];
        return parent::parse($object);
    }
}
