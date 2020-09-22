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
    'thumbnail'   => 'Application/views/out/img/AfterPay_logo_green.png',
    'version' => '2.0.0-dev',
    'author' => 'OXID eSales AG',
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
        \OxidEsales\Eshop\Application\Model\Order::class   => Arvato\AfterpayModule\Application\Model\Order::class
    ],
    'controllers' => [
        'OrderAfterpay' => Arvato\AfterpayModule\Application\Controller\Admin\OrderAfterpay::class
    ],
    'templates' => [
        'order_afterpay.tpl'                                     => 'arvato/afterpay/Application/views/admin/tpl/order_afterpay.tpl',
        'order_afterpay_item.tpl'                                => 'arvato/afterpay/Application/views/admin/tpl/order_afterpay_item.tpl',
        'flow/page/checkout/inc/payment_afterpayinstallment.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpayinstallment.tpl',
        'flow/page/checkout/inc/payment_afterpaydebitnote.tpl'   => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpaydebitnote.tpl',
        'flow/page/checkout/inc/payment_afterpayinvoice.tpl'     => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpayinvoice.tpl',
        'flow/page/checkout/inc/order_installmentplan_boxes.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/order_installmentplan_boxes.tpl',
        'flow/page/checkout/inc/afterpay_required_dynvalues.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/afterpay_required_dynvalues.tpl',
        'wave/page/checkout/inc/payment_afterpayinstallment.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpayinstallment.tpl',
        'wave/page/checkout/inc/payment_afterpaydebitnote.tpl'   => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpaydebitnote.tpl',
        'wave/page/checkout/inc/payment_afterpayinvoice.tpl'     => 'arvato/afterpay/Application/views/flow/page/checkout/inc/payment_afterpayinvoice.tpl',
        'wave/page/checkout/inc/order_installmentplan_boxes.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/order_installmentplan_boxes.tpl',
        'wave/page/checkout/inc/afterpay_required_dynvalues.tpl' => 'arvato/afterpay/Application/views/flow/page/checkout/inc/afterpay_required_dynvalues.tpl'
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

    'settings' => [
        [
            'group' => 'arvatoAfterpayGeneral',
            'name'  => 'arvatoAfterpayApiDefaultShippingCompany',
            'type'  => 'str',
            'value' => 'DHL UPS'
        ],
        [
            'group' => 'arvatoAfterpayGeneral',
            'name'  => 'arvatoAfterpayApiDefaultRefundDescription',
            'type'  => 'str',
            'value' => 'Rückerstattung - Refund'
        ],
        [
            'group' => 'arvatoAfterpayGeneral',
            'name'  => 'arvatoAfterpayRiskChannelType',
            'type'  => 'str',
            'value' => 'Internet'
        ],
        [
            'group' => 'arvatoAfterpayGeneral',
            'name'  => 'arvatoAfterpayRiskDeliveryType',
            'type'  => 'str',
            'value' => 'Normal'
        ],

        // API

        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiUrl',
            'type'  => 'str',
            'value' => 'https://api.afterpay.io/'
        ],

        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiKeyDE',
            'type'  => 'str',
            'value' => ''
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiKeyDEInstallment',
            'type'  => 'str',
            'value' => ''
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiKeyAT',
            'type'  => 'str',
            'value' => ''
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiKeyATInstallment',
            'type'  => 'str',
            'value' => ''
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiKeyCH',
            'type'  => 'str',
            'value' => ''
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiKeyNL',
            'type'  => 'str',
            'value' => ''
        ],

        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiSandboxMode',
            'type'  => 'bool',
            'value' => false
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiSandboxUrl',
            'type'  => 'str',
            'value' => 'https://sandboxapi.horizonafs.com/eCommerceServicesWebApi/api/v3/'
        ],

        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiSandboxKeyDE',
            'type'  => 'str',
            'value' => ''
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiSandboxKeyDEInstallment',
            'type'  => 'str',
            'value' => ''
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiSandboxKeyAT',
            'type'  => 'str',
            'value' => ''
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiSandboxKeyATInstallment',
            'type'  => 'str',
            'value' => ''
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiSandboxKeyCH',
            'type'  => 'str',
            'value' => ''
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiSandboxKeyNL',
            'type'  => 'str',
            'value' => ''
        ],
        [
            'group' => 'arvatoAfterpayApi',
            'name'  => 'arvatoAfterpayApiRequestLogging',
            'type'  => 'bool',
            'value' => false
        ],

        // Requirements

        [
            'group' => 'arvatoAfterpayRequiredFields',
            'name'  => 'arvatoAfterpayInvoiceRequiresBirthdate',
            'type'  => 'bool',
            'value' => true
        ],
        [
            'group' => 'arvatoAfterpayRequiredFields',
            'name'  => 'arvatoAfterpayDebitRequiresBirthdate',
            'type'  => 'bool',
            'value' => true
        ],
        [
            'group' => 'arvatoAfterpayRequiredFields',
            'name'  => 'arvatoAfterpayInstallmentsRequiresBirthdate',
            'type'  => 'bool',
            'value' => true
        ],

        [
            'group' => 'arvatoAfterpayRequiredFields',
            'name'  => 'arvatoAfterpayInvoiceRequiresSSN',
            'type'  => 'bool',
            'value' => false
        ],
        [
            'group' => 'arvatoAfterpayRequiredFields',
            'name'  => 'arvatoAfterpayDebitRequiresSSN',
            'type'  => 'bool',
            'value' => false
        ],
        [
            'group' => 'arvatoAfterpayRequiredFields',
            'name'  => 'arvatoAfterpayInstallmentsRequiresSSN',
            'type'  => 'bool',
            'value' => false
        ],

        [
            'group' => 'arvatoAfterpayRequiredFields',
            'name'  => 'arvatoAfterpayInvoiceRequiresFon',
            'type'  => 'bool',
            'value' => true
        ],
        [
            'group' => 'arvatoAfterpayRequiredFields',
            'name'  => 'arvatoAfterpayDebitRequiresFon',
            'type'  => 'bool',
            'value' => true
        ],
        [
            'group' => 'arvatoAfterpayRequiredFields',
            'name'  => 'arvatoAfterpayInstallmentsRequiresFon',
            'type'  => 'bool',
            'value' => true
        ],

        // Profile Tracking

        [
            'group' => 'arvatoAfterpayProfileTracking',
            'name'  => 'arvatoAfterpayProfileTrackingEnabled',
            'type'  => 'bool',
            'value' => false
        ],
        [
            'group' => 'arvatoAfterpayProfileTracking',
            'name'  => 'arvatoAfterpayProfileTrackingUrl',
            'type'  => 'str',
            'value' => 'uc8.tv'
        ],
        [
            'group' => 'arvatoAfterpayProfileTracking',
            'name'  => 'arvatoAfterpayProfileTrackingId',
            'type'  => 'str',
            'value' => ''
        ]
    ]
];
