<?php

namespace Arvato\AfterpayModule\Tests\Unit\Model\DataProvider;

use Arvato\AfterpayModule\Application\Model\DataProvider\AuthorizePaymentDataProvider;
use Arvato\AfterpayModule\Application\Model\Entity\AuthorizePaymentEntity;
use Arvato\AfterpayModule\Application\Model\Entity\CheckoutCustomerEntity;
use Arvato\AfterpayModule\Application\Model\Entity\OrderEntity;
use Arvato\AfterpayModule\Application\Model\Entity\PaymentEntity;
use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\BasketItem;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Exception\ArticleInputException;
use OxidEsales\Eshop\Core\Exception\NoArticleException;
use OxidEsales\Eshop\Core\Exception\OutOfStockException;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\TestingLibrary\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class AuthorizePaymentDataProviderTest: Tests for AuthorizePaymentDataProvider.
 */
class AuthorizePaymentDataProviderTest extends UnitTestCase
{
    /**
     * Read DB Fixtures
     */
    public function setUp()
    {
        parent::setUp();
        $sql = file_get_contents(Registry::getConfig()->getConfigParam('sShopDir') . '/modules/arvato/afterpay/Tests/Fixtures/dataproviders_setUp.sql');
        foreach (explode(';', $sql) as $query) {
            $query = trim($query);
            if ($query) {
                DatabaseProvider::getDb()->execute($query);
            }
        }

        foreach (['oxarticles', 'oxcategories'] as $table) {
            if (!in_array('OXMAPID', array_keys(Registry::get(DbMetaDataHandler::class)->getFields($table)), true)) {
                // No auto_increment here: not necessary for our tests
                DatabaseProvider::getDb()->execute("ALTER TABLE $table ADD COLUMN OXMAPID BIGINT NOT NULL");
            }
        }
    }

    /**
     * Delete DB Fixtures
     */
    public function tearDown()
    {
        parent::tearDown();
        $sql = file_get_contents(Registry::getConfig()->getConfigParam('sShopDir') . '/modules/arvato/afterpay/Tests/Fixtures/dataproviders_tearDown.sql');
        foreach (explode(';', $sql) as $query) {
            $query = trim($query);
            if ($query) {
                DatabaseProvider::getDb()->execute($query);
            }
        }
    }

    /**
     * @throws ArticleInputException
     * @throws NoArticleException
     * @throws OutOfStockException
     */
    public function testGetDataObject()
    {
        /** @var AuthorizePaymentDataProvider|PHPUnit_Framework_MockObject_MockObject $sut */
        $sut = $this->getMockBuilder(AuthorizePaymentDataProvider::class)
                    ->disableOriginalConstructor()
                    ->setMethods(['getOrderSummeryByBasket', 'getCustomer', 'getPayment'])
                    ->getMock();

        $sut->method('getOrderSummeryByBasket')->willReturn(oxNew(OrderEntity::class));
        $sut->method('getPayment')->willReturn(oxNew(PaymentEntity::class));
        $sut->method('getCustomer')->willReturn(oxNew(CheckoutCustomerEntity::class));

        // Construction of in-session-basket incomplete.
        //There must be an easier way.

        $oxBasket = oxNew(Basket::class);

        $this->assertInstanceOf(BasketItem::class, $oxBasket->addToBasket('unitoxarticle', 1));

        $oxUser = oxNew(User::class);
        $oxUser->load('oxdefaultadmin');

        $oxSession = Registry::getSession();
        $oxSession->setVariable('afterpayOrderId', 'afterpayOrderId123');
        $oxSession->setVariable('deladrid', null);//'deladrid123');
        $oxSession->setBasket($oxBasket);
        $oxSession->setUser($oxUser);

        $oxLang = Registry::getLang();
        $oxLang->getBaseLanguage();
        $sutReturn = $sut->getDataObject($oxSession, $oxLang, oxNew(Order::class));
        $this->assertInstanceOf(AuthorizePaymentEntity::class, $sutReturn);
    }
}
