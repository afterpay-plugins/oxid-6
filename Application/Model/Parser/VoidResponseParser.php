<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Parser;

/**
 * Class VoidResponseParser: Parser for the capture response.
 */
class VoidResponseParser extends \Arvato\AfterpayModule\Application\Model\Parser\Parser
{

    public function parse(\stdClass $object)
    {
        $this->fields = [
            'totalAuthorizedAmount',
            'totalCapturedAmount',
        ];
        return parent::parse($object);
    }
}
