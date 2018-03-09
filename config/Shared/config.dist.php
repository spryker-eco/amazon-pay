<?php

use Spryker\Shared\Acl\AclConstants;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\Sales\SalesConstants;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Shared\AmazonPay\AmazonPayConstants;

$config[AmazonPayConstants::CLIENT_ID] = '';
$config[AmazonPayConstants::CLIENT_SECRET] = '';
$config[AmazonPayConstants::SELLER_ID] = '';
$config[AmazonPayConstants::ACCESS_KEY_ID] = '';
$config[AmazonPayConstants::SECRET_ACCESS_KEY] = '';
$config[AmazonPayConstants::REGION] = 'DE';
$config[AmazonPayConstants::STORE_NAME] = 'Demo shop';
$config[AmazonPayConstants::SANDBOX] = true;
$config[AmazonPayConstants::AUTH_TRANSACTION_TIMEOUT] = 1000;
$config[AmazonPayConstants::CAPTURE_NOW] = true;
$config[AmazonPayConstants::ERROR_REPORT_LEVEL] = 'ERRORS_ONLY';
$config[AmazonPayConstants::PAYMENT_REJECT_ROUTE] = 'cart';
$config[AmazonPayConstants::WIDGET_SCRIPT_PATH] = 'https://static-eu.payments-amazon.com/OffAmazonPayments/eur/lpa/js/Widgets.js';
$config[AmazonPayConstants::WIDGET_SCRIPT_PATH_SANDBOX] = 'https://static-eu.payments-amazon.com/OffAmazonPayments/eur/sandbox/lpa/js/Widgets.js';
$config[AmazonPayConstants::WIDGET_POPUP_LOGIN] = true;
$config[AmazonPayConstants::WIDGET_BUTTON_TYPE] = AmazonPayConfig::WIDGET_BUTTON_TYPE_FULL;
$config[AmazonPayConstants::WIDGET_BUTTON_SIZE] = AmazonPayConfig::WIDGET_BUTTON_SIZE_MEDIUM;
$config[AmazonPayConstants::WIDGET_BUTTON_COLOR] = AmazonPayConfig::WIDGET_BUTTON_COLOR_DARK_GRAY;
$config[AmazonPayConstants::ENABLE_ISOLATE_LEVEL_READ] = true; /* make sure to put `false` for test environment */

$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING][AmazonPayConfig::PROVIDER_NAME] =
    $config[AmazonPayConstants::CAPTURE_NOW] ? 'AmazonpayPaymentSync01' : 'AmazonpayPaymentAsync01';

$config[OmsConstants::ACTIVE_PROCESSES][] = 'AmazonpayPaymentAsync01';
$config[OmsConstants::ACTIVE_PROCESSES][] = 'AmazonpayPaymentSync01';

$config[AclConstants::ACL_USER_RULE_WHITELIST][] =
    [
        'bundle' => 'amazonpay',
        'controller' => 'ipn',
        'action' => 'endpoint',
        'type' => 'allow',
    ];
