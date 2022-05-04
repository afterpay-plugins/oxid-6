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
 * @author    ©2020 norisk GmbH
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2020
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = [
    'id' => 'arvatoafterpay',
    'title' => [
        'de' => 'ARVATO :: afterpay',
        'en' => 'ARVATO :: afterpay'
    ],
    'description' => [
        'de' => 'Standalone-Version des Arvato AfterPay-Moduls',
        'en' => 'standalone release of the Arvato AfterPay Module'
    ],
    'thumbnail'   => 'Application/views/out/img/AfterPay_logo_checkout.png',
    'version' => '2.1.0-beta.3',
    'author' => 'norisk GmbH',
    'url' => '',
    'email' => '',
    'events' => [
        'onActivate'   => 'Arvato\AfterpayModule\Core\Events::onActivate',
        'onDeactivate' => 'Arvato\AfterpayModule\Core\Events::onDeactivate'
    ],
    'extend' => [
        // Controller
        \OxidEsales\Eshop\Application\Controller\OrderController::class    => Arvato\AfterpayModule\Application\Controller\OrderController::class,
        \OxidEsales\Eshop\Application\Controller\UserController::class     => Arvato\AfterpayModule\Application\Controller\UserController::class,
        \OxidEsales\Eshop\Application\Controller\PaymentController::class  => Arvato\AfterpayModule\Application\Controller\PaymentController::class,
        // Model
        \OxidEsales\Eshop\Application\Model\Article::class => Arvato\AfterpayModule\Application\Model\Article::class,
        \OxidEsales\Eshop\Application\Model\Order::class   => Arvato\AfterpayModule\Application\Model\Order::class,
        // Core
        \OxidEsales\Eshop\Core\ViewConfig::class => Arvato\AfterpayModule\Core\ViewConfig::class,
        \OxidEsales\Eshop\Core\Utils::class => Arvato\AfterpayModule\Core\Afterpay_Utils::class,
    ],
    'controllers' => [
        'OrderAfterpay' => Arvato\AfterpayModule\Application\Controller\Admin\OrderAfterpay::class,
        // admin
        'AfterpayConfig'        => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayConfig::class,
        'AfterpayConfigList'    => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayConfigList::class,
        'AfterpayConfigTab'     => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayConfigTab::class,

        'AfterpayApi'       => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayApi::class,
        'AfterpayApiList'   => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayApiList::class,
        'AfterpayApiTab'    => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayApiTab::class,

        'AfterpayRequiredfields'        => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayRequiredfields::class,
        'AfterpayRequiredfieldsList'    => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayRequiredfieldsList::class,
        'AfterpayRequiredfieldsTab'     => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayRequiredfieldsTab::class,

        'AfterpayProfileTracking'       => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayProfileTracking::class,
        'AfterpayProfileTrackingList'   => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayProfileTrackingList::class,
        'AfterpayProfileTrackingTab'    => Arvato\AfterpayModule\Application\Controller\Admin\AfterpayProfileTrackingTab::class,
    ],
    'templates' => [
        'order_afterpay.tpl'                                     => 'arvato/afterpay/Application/views/admin/tpl/order_afterpay.tpl',
        'order_afterpay_item.tpl'                                => 'arvato/afterpay/Application/views/admin/tpl/order_afterpay_item.tpl',
        'flow/page/checkout/inc/payment_afterpayinstallment.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpayinstallment.tpl',
        'flow/page/checkout/inc/payment_afterpaydebitnote.tpl'   => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpaydebitnote.tpl',
        'flow/page/checkout/inc/payment_afterpayinvoice.tpl'     => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpayinvoice.tpl',
        'flow/page/checkout/inc/order_installmentplan_boxes.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/order_installmentplan_boxes.tpl',
        'flow/page/checkout/inc/afterpay_required_dynvalues.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/afterpay_required_dynvalues.tpl',
        'flow/page/checkout/inc/payment_tracking.tpl'            => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_tracking.tpl',
        'wave/page/checkout/inc/payment_afterpayinstallment.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpayinstallment.tpl',
        'wave/page/checkout/inc/payment_afterpaydebitnote.tpl'   => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpaydebitnote.tpl',
        'wave/page/checkout/inc/payment_afterpayinvoice.tpl'     => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpayinvoice.tpl',
        'wave/page/checkout/inc/order_installmentplan_boxes.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/order_installmentplan_boxes.tpl',
        'wave/page/checkout/inc/afterpay_required_dynvalues.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/afterpay_required_dynvalues.tpl',
        'wave/page/checkout/inc/payment_tracking.tpl'            => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_tracking.tpl',
        
        // admin
        'afterpay_config.tpl'                    => 'arvato/afterpay/Application/views/admin/tpl/afterpay_config.tpl',
        'afterpay_config_list.tpl'               => 'arvato/afterpay/Application/views/admin/tpl/afterpay_config_list.tpl',
        'afterpay_config_tab.tpl'                => 'arvato/afterpay/Application/views/admin/tpl/afterpay_config_tab.tpl',

        'afterpay_api.tpl'                    => 'arvato/afterpay/Application/views/admin/tpl/afterpay_api.tpl',
        'afterpay_api_list.tpl'               => 'arvato/afterpay/Application/views/admin/tpl/afterpay_api_list.tpl',
        'afterpay_api_tab.tpl'                => 'arvato/afterpay/Application/views/admin/tpl/afterpay_api_tab.tpl',

        'afterpay_requiredfields.tpl'                    => 'arvato/afterpay/Application/views/admin/tpl/afterpay_requiredfields.tpl',
        'afterpay_requiredfields_list.tpl'               => 'arvato/afterpay/Application/views/admin/tpl/afterpay_requiredfields_list.tpl',
        'afterpay_requiredfields_tab.tpl'                => 'arvato/afterpay/Application/views/admin/tpl/afterpay_requiredfields_tab.tpl',

        'afterpay_profiletracking.tpl'                    => 'arvato/afterpay/Application/views/admin/tpl/afterpay_profiletracking.tpl',
        'afterpay_profiletracking_list.tpl'               => 'arvato/afterpay/Application/views/admin/tpl/afterpay_profiletracking_list.tpl',
        'afterpay_profiletracking_tab.tpl'                => 'arvato/afterpay/Application/views/admin/tpl/afterpay_profiletracking_tab.tpl',

    ],
    'blocks' => [
        [
            'theme'    => 'flow',
            'template' => 'form/user_checkout_change.tpl',
            'block'    => 'user_checkout_change',
            'file'     => 'Application/views/blocks/flow/checkout_user_errors.tpl'
        ],
        [
            'theme'    => 'flow',
            'template' => 'page/checkout/order.tpl',
            'block'    => 'checkout_order_btn_confirm_bottom',
            'file'     => 'Application/views/blocks/flow/checkout_order_btn_confirm_bottom.tpl'
        ],
        [
            'theme'    => 'flow',
            'template' => 'page/checkout/order.tpl',
            'block'    => 'checkout_order_errors',
            'file'     => 'Application/views/blocks/flow/checkout_order_errors.tpl'
        ],
        [
            'theme'    => 'flow',
            'template' => 'page/checkout/order.tpl',
            'block'    => 'checkout_order_remark',
            'file'     => 'Application/views/blocks/flow/checkout_order_remark.tpl'
        ],
        [
            'theme'    => 'flow',
            'template' => 'page/checkout/inc/basketcontents.tpl',
            'block'    => 'checkout_basketcontents_grandtotal',
            'file'     => 'Application/views/blocks/flow/checkout_order_basketcontents_grandtotal.tpl'
        ],
        [
            'theme'    => 'flow',
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'checkout_payment_errors',
            'file'     => 'Application/views/blocks/flow/checkout_payment_errors.tpl'
        ],
        [
            'theme'    => 'flow',
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'select_payment',
            'file'     => 'Application/views/blocks/flow/select_payment.tpl'
        ],
        [
            'theme'    => 'flow',
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'checkout_payment_main',
            'file'     => 'Application/views/blocks/flow/checkout_payment_main.tpl'
        ],
        [
            'theme'    => 'wave',
            'template' => 'form/user_checkout_change.tpl',
            'block'    => 'user_checkout_change',
            'file'     => 'Application/views/blocks/wave/checkout_user_errors.tpl'
        ],
        [
            'theme'    => 'wave',
            'template' => 'page/checkout/order.tpl',
            'block'    => 'checkout_order_btn_confirm_bottom',
            'file'     => 'Application/views/blocks/wave/checkout_order_btn_confirm_bottom.tpl'
        ],
        [
            'theme'    => 'wave',
            'template' => 'page/checkout/order.tpl',
            'block'    => 'checkout_order_errors',
            'file'     => 'Application/views/blocks/wave/checkout_order_errors.tpl'
        ],
        [
            'theme'    => 'wave',
            'template' => 'page/checkout/order.tpl',
            'block'    => 'checkout_order_remark',
            'file'     => 'Application/views/blocks/wave/checkout_order_remark.tpl'
        ],
        [
            'theme'    => 'wave',
            'template' => 'page/checkout/inc/basketcontents.tpl',
            'block'    => 'checkout_basketcontents_grandtotal',
            'file'     => 'Application/views/blocks/wave/checkout_order_basketcontents_grandtotal.tpl'
        ],
        [
            'theme'    => 'wave',
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'checkout_payment_errors',
            'file'     => 'Application/views/blocks/wave/checkout_payment_errors.tpl'
        ],
        [
            'theme'    => 'wave',
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'select_payment',
            'file'     => 'Application/views/blocks/wave/select_payment.tpl'
        ],
        [
            'theme'    => 'wave',
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'checkout_payment_main',
            'file'     => 'Application/views/blocks/wave/checkout_payment_main.tpl'
        ],
        [
            'template' => 'article_main.tpl',
            'block'    => 'admin_article_main_form',
            'file'     => 'Application/views/blocks/admin_article_main_form.tpl'
        ],
        [
            'template' => 'include/category_main_form.tpl',
            'block'    => 'admin_category_main_form',
            'file'     => 'Application/views/blocks/admin_category_main_form.tpl'
        ]
    ],
];
