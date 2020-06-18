<?php

/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @category  module
 * @package   afterpay
 * @author    OXID Professional services
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2020
 */

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Entity;

/**
 * Class AuthorizePaymentResponseEntityTest: Tests for AuthorizePaymentResponseEntity.
 */
class AuthorizePaymentResponseEntityTest extends \OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model\Entity\EntityAbstract
{
    /**
     * Testing all getters and Setters
     */
    public function testGetSetData()
    {
        $testData = [
            'outcome' => 111,
            'customer' => oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CustomerResponseEntity::class),
            'deliveryCustomer' => oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\CustomerResponseEntity::class),
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
        return oxNew(\OxidProfessionalServices\ArvatoAfterpayModule\Application\Model\Entity\AuthorizePaymentResponseEntity::class);
    }
}
