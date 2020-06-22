<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class AuthorizePaymentResponseEntityTest: Tests for AuthorizePaymentResponseEntity.
 */
class AuthorizePaymentResponseEntityTest extends \Arvato\AfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
{
    /**
     * Testing all getters and Setters
     */
    public function testGetSetData()
    {
        $testData = [
            'outcome' => 111,
            'customer' => oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CustomerResponseEntity::class),
            'deliveryCustomer' => oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CustomerResponseEntity::class),
            'reservationId' => 444,
            'checkoutId' => 555,
            'riskCheckMessages' => 666
        ];

        $testObject = $this->getSUT();
        $this->testGetSet($testObject, $testData);
    }

    /**
     * SUT generator
     *
     * @return AuthorizePaymentResponseEntity
     */
    protected function getSUT()
    {
        return oxNew(\Arvato\AfterpayModule\Application\Model\Entity\AuthorizePaymentResponseEntity::class);
    }
}
