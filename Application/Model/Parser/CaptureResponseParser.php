<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Application\Model\Parser;

use Arvato\AfterpayModule\Application\Model\Entity\CaptureEntity;
use Arvato\AfterpayModule\Application\Model\Entity\CaptureResponseEntity;
use OxidEsales\Eshop\Core\Exception\StandardException;
use stdClass;

/**
 * Class CaptureResponseParser: Parser for the capture response.
 */
class CaptureResponseParser extends \Arvato\AfterpayModule\Application\Model\Parser\Parser
{

    /**
     * parse
     * -----------------------------------------------------------------------------------------------------------------
     *
     *
     * @param stdClass $object
     * @throws StandardException
     * @return CaptureResponseEntity
     */
    public function parse(stdClass $object)
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
