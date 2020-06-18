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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Core;

/**
 * Class EventsTest: Tests for arvatoAfterpayEvents.
 */
class EventsTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * setUp helper
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Testing method onActivate
     * The actual test is that there is no oxAdoDbException thrown,
     * i.e. the query works just fine.
     */
    public function testOnActivate()
    {
        $sutReturn = \OxidProfessionalServices\ArvatoAfterpayModule\Core\Events::onActivate();
        $this->assertTrue($sutReturn);
        // Must be idempotent - let's repeat
        $sutReturn = \OxidProfessionalServices\ArvatoAfterpayModule\Core\Events::onActivate();
        $this->assertTrue($sutReturn);
    }
}
