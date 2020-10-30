<?php

namespace Arvato\AfterpayModule\Application\Model\Parser;

/**
 * Class VoidResponseParser: Parser for the capture response.
 */
class VoidResponseParser extends Parser
{
    protected $fields = ['totalAuthorizedAmount', 'totalCapturedAmount'];
}
