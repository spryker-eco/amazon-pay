<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\AmazonPay;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface AmazonPayConstants
{
    public const ACCESS_KEY_ID = 'AMAZONPAY:ACCESS_KEY_ID';
    public const CLIENT_ID = 'AMAZONPAY:CLIENT_ID';
    public const SELLER_ID = 'AMAZONPAY:SELLER_ID';
    public const SECRET_ACCESS_KEY = 'AMAZONPAY:SECRET_ACCESS_KEY';
    public const CLIENT_SECRET = 'AMAZONPAY:CLIENT_SECRET';
    public const SANDBOX = 'AMAZONPAY:SANDBOX';
    public const REGION = 'AMAZONPAY:REGION';
    public const STORE_NAME = 'AMAZONPAY:STORE_NAME';
    public const ERROR_REPORT_LEVEL = 'AMAZONPAY:ERROR_REPORT_LEVEL';
    public const CAPTURE_NOW = 'AMAZONPAY:CAPTURE_NOW';
    public const AUTH_TRANSACTION_TIMEOUT = 'AMAZONPAY:AUTH_TRANSACTION_TIMEOUT';

    public const PAYMENT_REJECT_ROUTE = 'AMAZONPAY:PAYMENT_REJECT_ROUTE';
    public const WIDGET_SCRIPT_PATH = 'AMAZONPAY:WIDGET_SCRIPT_PATH';
    public const WIDGET_SCRIPT_PATH_SANDBOX = 'AMAZONPAY:WIDGET_SCRIPT_PATH_SANDBOX';
    public const WIDGET_POPUP_LOGIN = 'AMAZONPAY:WIDGET_POPUP_LOGIN';

    public const WIDGET_BUTTON_TYPE = 'AMAZONPAY:WIDGET_BUTTON_TYPE';
    public const WIDGET_BUTTON_COLOR = 'AMAZONPAY:WIDGET_BUTTON_COLOR';
    public const WIDGET_BUTTON_SIZE = 'AMAZONPAY:WIDGET_BUTTON_SIZE';

    public const ENABLE_ISOLATE_LEVEL_READ = 'AMAZONPAY:ENABLE_ISOLATE_LEVEL_READ';

    /**
     * Specification:
     * - Defines URL for redirecting from Amazon pages in case MFA challenge is passed.
     *
     * @api
     */
    public const SUCCESS_PAYMENT_URL = 'AMAZONPAY:SUCCESS_PAYMENT_URL';

    /**
     * Specification:
     * - Defines URL for redirecting from Amazon pages in case MFA challenge is not passed.
     *
     * @api
     */
    public const FAILURE_PAYMENT_URL = 'AMAZONPAY:FAILURE_PAYMENT_URL';
}
