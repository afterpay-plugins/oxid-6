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

namespace OxidProfessionalServices\ArvatoAfterpayModule\Tests\Unit\Model;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class ArticleTest: Tests for Article.
 */
class ArticleTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    public function testgetAfterpayProductGroupFoundbyarticle()
    {
        $ce = ($this->isEE() ? '' : 'ce');

        $sut = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $this->assertTrue($sut->load('unitoxarticle' . $ce));
        $this->assertEquals('ProductgroupByArticle', $sut->getAfterpayProductGroup());
    }

    public function testgetAfterpayProductGroupFoundbycat()
    {
        $ce = ($this->isEE() ? '' : 'ce');

        $sut = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $this->assertTrue($sut->load('unitoxarticle' . $ce));
        $sut->oxarticles__aapproductgroup = new \OxidEsales\Eshop\Core\Field(null);
        if ($this->isEE()) {
            $sut->assignToShop($this->isEE() ? '1' : 'oxbaseshop');
        }

        $cat = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $cat->load('unitoxcat' . $ce);
        $this->assertTrue($sut->inCategory('unitoxcat' . $ce), 'Product must be in unit category - assign failed, check fixture sql');

        $this->assertEquals('ProductgroupByCat', $sut->getAfterpayProductGroup());
    }

    protected function isEE()
    {
        return ('EE' == Registry::getConfig()->getEdition());
    }

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
        $sql = file_get_contents(Registry::getConfig()->getConfigParam('sShopDir') . '/modules/oxps/arvatoafterpay/Tests/Fixtures/generalTearDown.sql');
        foreach (explode(';', $sql) as $query) {
            $query = trim($query);
            if ($query) {
                DatabaseProvider::getDb()->execute($query);
            }
        }
    }
}
