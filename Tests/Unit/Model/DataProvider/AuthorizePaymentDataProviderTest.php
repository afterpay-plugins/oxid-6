<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Model\DataProvider;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class AuthorizePaymentDataProviderTest: Tests for AuthorizePaymentDataProvider.
 */
class AuthorizePaymentDataProviderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * Read DB Fixtures
     */
    public function setUp()
    {
        parent::setUp();
        $sql = file_get_contents(Registry::getConfig()->getConfigParam('sShopDir') . '/modules/oxps/arvatoafterpay/Tests/Fixtures/dataproviders_setUp.sql');
        foreach (explode(';', $sql) as $query) {
            $query = trim($query);
            if ($query) {
                DatabaseProvider::getDb()->execute($query);
            }
        }
    }

    /**
     * Delete DB Fixtures
     */
    public function tearDown()
    {
        parent::tearDown();
        $sql = file_get_contents(Registry::getConfig()->getConfigParam('sShopDir') . '/modules/oxps/arvatoafterpay/Tests/Fixtures/dataproviders_tearDown.sql');
        foreach (explode(';', $sql) as $query) {
            $query = trim($query);
            if ($query) {
                DatabaseProvider::getDb()->execute($query);
            }
        }
    }

    /**
     * Testing method getDataObject
     */
    public function testGetDataObject()
    {
        $sut = $this->getMockBuilder(\Arvato\AfterpayModule\Application\Model\DataProvider\AuthorizePaymentDataProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['getOrderSummeryByBasket','getCustomer', 'getPayment'])
            ->getMock();

        $sut->method('getOrderSummeryByBasket')->willReturn(oxNew(\Arvato\AfterpayModule\Application\Model\Entity\OrderEntity::class));
        $sut->method('getPayment')->willReturn(oxNew(\Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity::class));
        $sut->method('getCustomer')->willReturn(oxNew(\Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity::class));

        // Construction of in-session-basket incomplete.
        //There must be an easier way.

        $oxBasket = oxNew(\OxidEsales\Eshop\Application\Model\Basket::class);

        try {
            $this->assertTrue($oxBasket->addToBasket('unitoxarticle', 1) instanceof oxbasketitem);
        } catch (\OxidEsales\Eshop\Core\Exception\NoArticleException $e) {
            try {
                $this->assertTrue($oxBasket->addToBasket('unitoxarticlece', 1) instanceof oxbasketitem);
            } catch (\OxidEsales\Eshop\Core\Exception\NoArticleException $e) {
                // This particular test fails because fixture can't handle oxshop=oxbaseshop vs. oxshop=1)
                $this->markTestSkipped();
                return;
            }
        }

        $oxUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $oxUser->load('oxdefaultadmin');

        $oxSesssion = Registry::getSession();
        $oxSesssion->setVariable('afterpayOrderId', 'afterpayOrderId123');
        $oxSesssion->setVariable('deladrid', null);//'deladrid123');
        $oxSesssion->setBasket($oxBasket);
        $oxSesssion->setUser($oxUser);

        $oxLang = Registry::getLang();
        $oxLang->getBaseLanguage();
        $sutReturn = $sut->getDataObject($oxSesssion, $oxLang, oxNew(\OxidEsales\Eshop\Application\Model\Order::class));
        $this->assertInstanceOf(\Arvato\AfterpayModule\Application\Model\Entity\AuthorizePaymentEntity::class, $sutReturn);
    }
}
