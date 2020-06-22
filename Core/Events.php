<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Core;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;

/**
 * Class ArvatoAfterpayEvents: Events for the afterpay module.
 */
class Events
{
    private static $createArvatoAfterPay = "CREATE TABLE IF NOT EXISTS `arvatoafterpayafterpayorder` (
              `OXID` varchar(32) NOT NULL COMMENT 'oxorder.oxorderid',
              `APRESERVATIONID` varchar(64) NOT NULL,
              `APCHECKOUTID` varchar(64) NOT NULL,
              `APCAPTURENO` varchar(64) NOT NULL,
              `APCAPTURETIMESTAMP` datetime,
              `APSTATUS` enum('authorized','authorizationvoided','captured','refunded') NOT NULL,
              `OXTIMESTAMP` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `APUSEDAPIKEY` VARCHAR(64) NOT NULL,
              PRIMARY KEY (`OXID`)
            ) ENGINE='InnoDB' DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

    /**
     * An array of SQL statements, that will be executed only at the first time of module installation.
     *
     * @var array
     */
    private static $replaceArvatoAfterPaySQLs = [
        "REPLACE INTO `oxpayments` (`OXID`, `OXACTIVE`, `OXDESC`, `OXADDSUM`, `OXADDSUMTYPE`, `OXADDSUMRULES`, `OXFROMBONI`, `OXFROMAMOUNT`, `OXTOAMOUNT`, `OXVALDESC`, `OXCHECKED`, `OXDESC_1`, `OXVALDESC_1`, `OXDESC_2`, `OXVALDESC_2`, `OXDESC_3`, `OXVALDESC_3`, `OXLONGDESC`, `OXLONGDESC_1`, `OXLONGDESC_2`, `OXLONGDESC_3`, `OXSORT`, `OXTIMESTAMP`) VALUES

              ('afterpaydebitnote',	1,	'AfterPay Lastschrift',	0,	'abs',	0,	0,	0,	1000000,	'apdebitbankaccount__@@apdebitbankcode__@@apbirthday__@@apphone__@@apssn__@@',	0,	'AfterPay Direct Debit',
              'apdebitbankaccount__@@apdebitbankcode__@@apbirthday__@@apphone__@@apssn__@@',	'',	'',	'',	'',	'','',	'',	'',	3,	'2017-11-08 11:48:51'),

              ('afterpayinvoice',	1,	'AfterPay Rechnung',	0,	'abs',	0,	0,	0,	1000000,	'apbirthday__@@apphone__@@apssn__@@',	0,	'AfterPay Invoice',	'apbirthday__@@apphone__@@apssn__@@',	'',	'',	'',	'',	'',	'',	'',		2,	'',	'2017-11-08 11:48:51'),

              ('afterpayinstallment',	1,	'AfterPay Ratenzahlung',	0,	'abs',	0,	0,	0,	1000000,	'apinstallmentbankaccount__@@apinstallmentbankcode__@@afterpayInstallmentProfileId__@@apbirthday__@@apphone__@@apssn__@@',	0,	'AfterPay Installment',
              'apinstallmentbankaccount__@@apinstallmentbankcode__@@afterpayInstallmentProfileId__@@apbirthday__@@apphone__@@apssn__@@',	'',	'',	'',	'','','','',	1,	'',	'2017-11-08 11:48:51');",

        "REPLACE INTO `oxobject2payment` (`OXID`, `OXPAYMENTID`, `OXOBJECTID`, `OXTYPE`, `OXTIMESTAMP`) VALUES
              ('apinv2standard',	'afterpayinvoice',	'oxidstandard',	'oxdelset',	'2017-11-08 11:48:51'),
              ('apdebit2standard',	'afterpaydebitnote',	'oxidstandard',	'oxdelset',	'2017-11-08 11:48:51'),
              ('apinstallment2standard',	'afterpayinstallment',	'oxidstandard',	'oxdelset',	'2017-11-08 11:48:51'),

              ('apinv2germany',	'afterpayinvoice',	'a7c40f631fc920687.20179984',	'oxcountry',	'2017-11-08 11:48:51'),
              ('apdebit2germany',	'afterpaydebitnote',	'a7c40f631fc920687.20179984',	'oxcountry',	'2017-11-08 11:48:51'),
              ('apinv2nl',	'afterpayinvoice',	'a7c40f632cdd63c52.64272623',	'oxcountry',	'2017-11-08 11:48:51'),
              ('apdebit2nl',	'afterpaydebitnote',	'a7c40f632cdd63c52.64272623',	'oxcountry',	'2017-11-08 11:48:51'),
              ('apinstallment2germany',	'afterpayinstallment',	'a7c40f631fc920687.20179984',	'oxcountry',	'2017-11-08 11:48:51');"
    ];

    /**
     * Check if field exists in table
     *
     * @param string $fieldName field name
     * @param string $tableName table name
     *
     * @return bool
     */
    protected static function fieldExists($fieldName, $tableName)
    {
        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class );

        return $dbMetaDataHandler->fieldExists($fieldName, $tableName);
    }

    /**
     * setUpModule
     * -----------------------------------------------------------------------------------------------------------------
     * Execute the sqlQueries at the first time of the module installation.
     *
     */
    private static function setUpModule() {

        // Check if the arvatoafterpayorder table was already created, if not create it.
        if (!self::tableExists('arvatoafterpayafterpayorder')) {
            self::executeSQL(self::$createArvatoAfterPay);
        }

        // Check if the tables oxpayments and oxobject2payment have all fields, if not replace them with the standard values
        foreach (self::$replaceArvatoAfterPaySQLs as $sqlQuery) {
            // execute the statements for oxpayments und oxobject2payment
            self::executeSQL($sqlQuery);
        }
    }

    /**
     * Check if table exists
     *
     * @param string $sTableName table name
     *
     * @return bool
     */
    protected static function tableExists($sTableName)
    {
        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class );

        return $dbMetaDataHandler->tableExists($sTableName);
    }

    /**
     * Executes given sql statement.
     *
     * @param string $sqlQuery sql query to execute.
     */
    private static function executeSQL($sqlQuery)
    {
        DatabaseProvider::getDb()->execute($sqlQuery);
    }

    /**
     * On module activation callback
     */
    public static function onActivate()
    {
        $db = DatabaseProvider::getDb();
        $logging = oxNew(\Arvato\AfterpayModule\Core\Logging::class);

        $selectPaymentTypes = "SELECT OXID FROM oxpayments WHERE OXID LIKE 'afterpay%'";
        $resPaymentTypes = $db->getAll($selectPaymentTypes);

        foreach($resPaymentTypes as $resPaymentType) {
            // check if the payment types are not in database and execute setupModule()
            if(count($resPaymentTypes) < 3) {
                self::setupModule();
            }
            else {
                // if the payment types are already in the database, then update the values
                if(count($resPaymentTypes) == 3) {
                    $paymentTable = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);

                    foreach($resPaymentType as $keyPay=>$payType) {
                        $paymentTable->load($payType);
                    }
                    $paymentTable->save();
                }
            }
        }

        // Get column if exist
        $colArticleProductGroupExist = $db->getOne(
            "SELECT 1
                   FROM information_schema.COLUMNS
                   WHERE TABLE_NAME = 'oxarticles'
                   AND COLUMN_NAME = 'AAPPRODUCTGROUP'"
        );

        $colCategoryProductGroupExist = $db->getOne(
            "SELECT 1
                   FROM information_schema.COLUMNS
                   WHERE TABLE_NAME = 'oxcategories'
                   AND COLUMN_NAME = 'AAPPRODUCTGROUP'"
        );

        // Insert columns if not exist
        if (!$colArticleProductGroupExist) {
            DatabaseProvider::getDb()->execute(
                "ALTER TABLE `oxarticles`  ADD `AAPPRODUCTGROUP` varchar(32) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Arvato Afterpay product group - leave empty for category default'"
            );
        }

        if (!$colCategoryProductGroupExist) {
            DatabaseProvider::getDb()->execute(
                "ALTER TABLE `oxcategories`  ADD `AAPPRODUCTGROUP` varchar(32) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'Arvato Afterpay product group'"
            );
        }

        $shops = DatabaseProvider::getDb()->getAll('SELECT oxid FROM oxshops');

        foreach (self::getShopSpecificSQLs() as $sql) {
            foreach ($shops as $shopId) {
                $shopId = reset($shopId);
                if (!$shopId) {
                    continue;
                }
                $sql = str_replace('#shop#', $shopId, $sql);

                try {
                    $db->execute($sql);
                } catch (\Exception $e) {
                     $logging->logInstallation('SQL-ERROR ON ACTIVATE: ' . $e->getMessage());
                }
            }
        }

        // Update the views
        $metaDataHandler = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);

        return $metaDataHandler->updateViews();
    }

    /**
     * On module deactivation callback
     * @codeCoverageIgnore empty method
     */
    public static function onDeactivate()
    {
    }


    protected static function getShopSpecificSQLs()
    {
        return [
            "REPLACE INTO `oxobject2group` (`OXID`, `OXSHOPID`, `OXOBJECTID`, `OXGROUPSID`, `OXTIMESTAMP`) VALUES
              ('afterpayInv1-#shop#', '#shop#', 'afterpayinvoice', 'oxidblacklist', '2017-11-08 11:48:51'),
              ('afterpayInv2-#shop#', '#shop#', 'afterpayinvoice', 'oxidsmallcust', '2017-11-08 11:48:51'),
              ('afterpayInv3-#shop#', '#shop#', 'afterpayinvoice', 'oxidmiddlecust', '2017-11-08 11:48:51'),
              ('afterpayInv4-#shop#', '#shop#', 'afterpayinvoice', 'oxidgoodcust', '2017-11-08 11:48:51'),
              ('afterpayInv5-#shop#', '#shop#', 'afterpayinvoice', 'oxidforeigncustomer', '2017-11-08 11:48:51'),
              ('afterpayInv6-#shop#', '#shop#', 'afterpayinvoice', 'oxidnewcustomer', '2017-11-08 11:48:51'),
              ('afterpayInv7-#shop#', '#shop#', 'afterpayinvoice', 'oxidpowershopper', '2017-11-08 11:48:51'),
              ('afterpayInv8-#shop#', '#shop#', 'afterpayinvoice', 'oxiddealer', '2017-11-08 11:48:51'),
              ('afterpayInv9-#shop#', '#shop#', 'afterpayinvoice', 'oxidnewsletter', '2017-11-08 11:48:51'),
              ('afterpayInv10-#shop#', '#shop#', 'afterpayinvoice', 'oxidadmin', '2017-11-08 11:48:51'),
              ('afterpayInv11-#shop#', '#shop#', 'afterpayinvoice', 'oxidpriceb', '2017-11-08 11:48:51'),
              ('afterpayInv12-#shop#', '#shop#', 'afterpayinvoice', 'oxidpricea', '2017-11-08 11:48:51'),
              ('afterpayInv13-#shop#', '#shop#', 'afterpayinvoice', 'oxidpricec', '2017-11-08 11:48:51'),
              ('afterpayInv14-#shop#', '#shop#', 'afterpayinvoice', 'oxidnotyetordered', '2017-11-08 11:48:51'),
              ('afterpayInv14-#shop#', '#shop#', 'afterpayinvoice', 'oxidcustomer', '2017-11-08 11:48:51'),

              ('afterpayDebit1-#shop#', '#shop#', 'afterpaydebitnote', 'oxidblacklist', '2017-11-08 11:48:51'),
              ('afterpayDebit2-#shop#', '#shop#', 'afterpaydebitnote', 'oxidsmallcust', '2017-11-08 11:48:51'),
              ('afterpayDebit3-#shop#', '#shop#', 'afterpaydebitnote', 'oxidmiddlecust', '2017-11-08 11:48:51'),
              ('afterpayDebit4-#shop#', '#shop#', 'afterpaydebitnote', 'oxidgoodcust', '2017-11-08 11:48:51'),
              ('afterpayDebit5-#shop#', '#shop#', 'afterpaydebitnote', 'oxidforeigncustomer', '2017-11-08 11:48:51'),
              ('afterpayDebit6-#shop#', '#shop#', 'afterpaydebitnote', 'oxidnewcustomer', '2017-11-08 11:48:51'),
              ('afterpayDebit7-#shop#', '#shop#', 'afterpaydebitnote', 'oxidpowershopper', '2017-11-08 11:48:51'),
              ('afterpayDebit8-#shop#', '#shop#', 'afterpaydebitnote', 'oxiddealer', '2017-11-08 11:48:51'),
              ('afterpayDebit9-#shop#', '#shop#', 'afterpaydebitnote', 'oxidnewsletter', '2017-11-08 11:48:51'),
              ('afterpayDebit10-#shop#', '#shop#', 'afterpaydebitnote', 'oxidadmin', '2017-11-08 11:48:51'),
              ('afterpayDebit11-#shop#', '#shop#', 'afterpaydebitnote', 'oxidpriceb', '2017-11-08 11:48:51'),
              ('afterpayDebit12-#shop#', '#shop#', 'afterpaydebitnote', 'oxidpricea', '2017-11-08 11:48:51'),
              ('afterpayDebit13-#shop#', '#shop#', 'afterpaydebitnote', 'oxidpricec', '2017-11-08 11:48:51'),
              ('afterpayDebit14-#shop#', '#shop#', 'afterpaydebitnote', 'oxidnotyetordered', '2017-11-08 11:48:51'),
              ('afterpayDebit15-#shop#', '#shop#', 'afterpaydebitnote', 'oxidcustomer', '2017-11-08 11:48:51'),

              ('afterpayInstallment1-#shop#', '#shop#', 'afterpayInstallment', 'oxidblacklist', '2017-11-08 11:48:51'),
              ('afterpayInstallment2-#shop#', '#shop#', 'afterpayInstallment', 'oxidsmallcust', '2017-11-08 11:48:51'),
              ('afterpayInstallment3-#shop#', '#shop#', 'afterpayInstallment', 'oxidmiddlecust', '2017-11-08 11:48:51'),
              ('afterpayInstallment4-#shop#', '#shop#', 'afterpayInstallment', 'oxidgoodcust', '2017-11-08 11:48:51'),
              ('afterpayInstallment5-#shop#', '#shop#', 'afterpayInstallment', 'oxidforeigncustomer', '2017-11-08 11:48:51'),
              ('afterpayInstallment6-#shop#', '#shop#', 'afterpayInstallment', 'oxidnewcustomer', '2017-11-08 11:48:51'),
              ('afterpayInstallment7-#shop#', '#shop#', 'afterpayInstallment', 'oxidpowershopper', '2017-11-08 11:48:51'),
              ('afterpayInstallment8-#shop#', '#shop#', 'afterpayInstallment', 'oxiddealer', '2017-11-08 11:48:51'),
              ('afterpayInstallment9-#shop#', '#shop#', 'afterpayInstallment', 'oxidnewsletter', '2017-11-08 11:48:51'),
              ('afterpayInstallment10-#shop#', '#shop#', 'afterpayInstallment', 'oxidadmin', '2017-11-08 11:48:51'),
              ('afterpayInstallment11-#shop#', '#shop#', 'afterpayInstallment', 'oxidpriceb', '2017-11-08 11:48:51'),
              ('afterpayInstallment12-#shop#', '#shop#', 'afterpayInstallment', 'oxidpricea', '2017-11-08 11:48:51'),
              ('afterpayInstallment13-#shop#', '#shop#', 'afterpayInstallment', 'oxidpricec', '2017-11-08 11:48:51'),
              ('afterpayInstallment14-#shop#', '#shop#', 'afterpayInstallment', 'oxidnotyetordered', '2017-11-08 11:48:51'),
              ('afterpayInstallment15-#shop#', '#shop#', 'afterpayInstallment', 'oxidcustomer', '2017-11-08 11:48:51');
            "
        ];
    }
}
