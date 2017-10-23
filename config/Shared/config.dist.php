<?php

use Spryker\Shared\Acl\AclConstants;
use Spryker\Shared\Sales\SalesConstants;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

$config[AmazonpayConstants::CLIENT_ID] = '';
$config[AmazonpayConstants::CLIENT_SECRET] = '';
$config[AmazonpayConstants::SELLER_ID] = '';
$config[AmazonpayConstants::ACCESS_KEY_ID] = '';
$config[AmazonpayConstants::SECRET_ACCESS_KEY] = '';
$config[AmazonpayConstants::REGION] = 'DE';
$config[AmazonpayConstants::STORE_NAME] = 'Demo shop';
$config[AmazonpayConstants::SANDBOX] = true;
$config[AmazonpayConstants::AUTH_TRANSACTION_TIMEOUT] = 1000;
$config[AmazonpayConstants::CAPTURE_NOW] = true;
$config[AmazonpayConstants::ERROR_REPORT_LEVEL] = 'ERRORS_ONLY';
$config[AmazonpayConstants::PAYMENT_REJECT_ROUTE] = 'cart';
$config[AmazonpayConstants::WIDGET_SCRIPT_PATH] = 'https://static-eu.payments-amazon.com/OffAmazonPayments/eur/lpa/js/Widgets.js';
$config[AmazonpayConstants::WIDGET_SCRIPT_PATH_SANDBOX] = 'https://static-eu.payments-amazon.com/OffAmazonPayments/eur/sandbox/lpa/js/Widgets.js';
$config[AmazonpayConstants::WIDGET_POPUP_LOGIN] = true;
$config[AmazonpayConstants::WIDGET_BUTTON_TYPE] = AmazonpayConfig::WIDGET_BUTTON_TYPE_FULL;
$config[AmazonpayConstants::WIDGET_BUTTON_SIZE] = AmazonpayConfig::WIDGET_BUTTON_SIZE_MEDIUM;
$config[AmazonpayConstants::WIDGET_BUTTON_COLOR] = AmazonpayConfig::WIDGET_BUTTON_COLOR_DARK_GRAY;

$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING][AmazonpayConstants::PAYMENT_METHOD] =
    $config[AmazonpayConstants::CAPTURE_NOW] ? 'AmazonpayPaymentSync01' : 'AmazonpayPaymentAsync01';

$config[AclConstants::ACL_USER_RULE_WHITELIST][] =
    [
        'bundle' => 'amazonpay',
        'controller' => 'ipn',
        'action' => 'endpoint',
        'type' => 'allow',
    ];
