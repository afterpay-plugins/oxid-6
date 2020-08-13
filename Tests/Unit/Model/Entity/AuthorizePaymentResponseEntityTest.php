<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

use Arvato\AfterpayModule\Application\Model\Entity\AuthorizePaymentResponseEntity;
use Arvato\AfterpayModule\Application\Model\Entity\CustomerResponseEntity;

/**
 * Class AuthorizePaymentResponseEntityTest: Tests for AuthorizePaymentResponseEntity.
 */
class AuthorizePaymentResponseEntityTest extends EntityAbstract
{
    /**
     * Testing all getters and Setters
     */
    public function testGetSetData()
    {
        $testData = [
            'outcome'           => 111,
            'customer'          => oxNew(CustomerResponseEntity::class),
            'deliveryCustomer'  => oxNew(CustomerResponseEntity::class),
            'reservationId'     => 444,
            'checkoutId'        => 555,
            'riskCheckMessages' => 666,
        ];

        $testObject = $this->getSUT();
        $this->getSet($testObject, $testData);
    }

    /**
     * SUT generator
     *
     * @return AuthorizePaymentResponseEntity
     */
    protected function getSUT()
    {
        return oxNew(AuthorizePaymentResponseEntity::class);
    }
}
