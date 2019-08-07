<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\AmazonPay;

use Spryker\Shared\Kernel\AbstractBundleConfig;
use Spryker\Shared\Kernel\Store;

class AmazonPayConfig extends AbstractBundleConfig implements AmazonPayConfigInterface
{
    public const WIDGET_BUTTON_TYPE_FULL = 'PwA';
    public const WIDGET_BUTTON_TYPE_SHORT = 'Pay';
    public const WIDGET_BUTTON_TYPE_SQUARE = 'A';
    public const WIDGET_BUTTON_COLOR_GOLD = 'Gold';
    public const WIDGET_BUTTON_COLOR_LIGHT_GRAY = 'LightGray';
    public const WIDGET_BUTTON_COLOR_DARK_GRAY = 'DarkGray';
    public const WIDGET_BUTTON_SIZE_SMALL = 'small';
    public const WIDGET_BUTTON_SIZE_MEDIUM = 'medium';
    public const WIDGET_BUTTON_SIZE_LARGE = 'large';
    public const WIDGET_BUTTON_SIZE_XLARGE = 'x-large';
    public const PROVIDER_NAME = 'Amazon Pay';

    public const OMS_STATUS_AUTH_OPEN = 'auth open';
    public const OMS_STATUS_AUTH_OPEN_WITHOUT_CANCEL = 'auth open without cancel';
    public const OMS_STATUS_CAPTURE_COMPLETED = 'capture completed';
    public const OMS_STATUS_CAPTURE_PENDING = 'capture pending';
    public const OMS_STATUS_CANCELLED = 'cancelled';

    public const OMS_STATUS_REFUND_PENDING = 'refund pending';
    public const OMS_STATUS_REFUND_COMPLETED = 'refund completed';
    public const OMS_STATUS_REFUND_DECLINED = 'refund declined';
    public const OMS_STATUS_REFUND_WAITING_FOR_STATUS = 'waiting for refund status';

    public const OMS_EVENT_UPDATE_AUTH_STATUS = 'update authorization status';
    public const OMS_EVENT_CANCEL = 'cancel';
    public const OMS_EVENT_UPDATE_CAPTURE_STATUS = 'update capture status';
    public const OMS_EVENT_UPDATE_REFUND_STATUS = 'update refund status';
    public const OMS_EVENT_CAPTURE = 'capture';
    public const OMS_EVENT_UPDATE_SUSPENDED_ORDER = 'update suspended order';

    public const OMS_FLAG_NOT_AUTH = 'not auth';
    public const OMS_FLAG_NOT_CAPTURED = 'not captured';

    public const STATUS_DECLINED = 'STATUS_DECLINED';//setIsDeclined
    public const STATUS_CANCELLED = 'STATUS_CANCELLED';//setIsCancelled
    public const STATUS_CLOSED = 'STATUS_CLOSED';//setIsClosed
    public const STATUS_SUSPENDED = 'STATUS_SUSPENDED';
    public const STATUS_PENDING = 'STATUS_PENDING';//setIsPending
    public const STATUS_OPEN = 'STATUS_OPEN';//setIsOpen
    public const STATUS_COMPLETED = 'STATUS_COMPLETED';//setIsCompleted
    public const STATUS_EXPIRED = 'STATUS_EXPIRED';//setIsReauthorizable
    public const STATUS_PAYMENT_METHOD_INVALID = 'STATUS_CODE_PAYMENT_METHOD_INVALID';//setIsPaymentMethodInvalid
    public const STATUS_AMAZON_CLOSED = 'STATUS_CODE_AMAZON_CLOSED';//setIsClosedByAmazon
    public const STATUS_TRANSACTION_TIMED_OUT = 'STATUS_CODE_TRANSACTION_TIMED_OUT';//setIsTransactionTimedOut
    public const STATUS_PAYMENT_METHOD_CHANGED = 'STATUS_PAYMENT_METHOD_CHANGED';//setIsTransactionTimedOut

    public const IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE = 'PaymentAuthorize';
    public const IPN_REQUEST_TYPE_PAYMENT_CAPTURE = 'PaymentCapture';
    public const IPN_REQUEST_TYPE_PAYMENT_REFUND = 'PaymentRefund';
    public const IPN_REQUEST_TYPE_ORDER_REFERENCE_NOTIFICATION = 'OrderReferenceNotification';

    public const PREFIX_AMAZONPAY_PAYMENT_ERROR = 'amazonpay.payment.error.';

    public const DISPLAY_MODE_READONLY = 'Read';

    public const REASON_CODE_EXPIRED_UNUSED = 'ExpiredUnused';
    public const REASON_CODE_SELLER_CLOSED = 'SellerClosed';
    public const REASON_CODE_PAYMENT_METHOD_INVALID = 'InvalidPaymentMethod';
    public const REASON_CODE_AMAZON_CLOSED = 'AmazonClosed';
    public const REASON_CODE_TRANSACTION_TIMED_OUT = 'TransactionTimedOut';

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->get(AmazonPayConstants::CLIENT_ID);
    }

    /**
     * @return string
     */
    public function getAccessKeyId()
    {
        return $this->get(AmazonPayConstants::ACCESS_KEY_ID);
    }

    /**
     * @return string
     */
    public function getSellerId()
    {
        return $this->get(AmazonPayConstants::SELLER_ID);
    }

    /**
     * @return string
     */
    public function getSecretAccessKey()
    {
        return $this->get(AmazonPayConstants::SECRET_ACCESS_KEY);
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->get(AmazonPayConstants::CLIENT_SECRET);
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->get(AmazonPayConstants::REGION);
    }

    /**
     * @return string
     */
    public function getCurrencyIsoCode()
    {
        return Store::getInstance()->getCurrencyIsoCode();
    }

    /**
     * @return bool
     */
    public function isSandbox()
    {
        return (bool)$this->get(AmazonPayConstants::SANDBOX, true);
    }

    /**
     * @return string
     */
    public function getErrorReportLevel()
    {
        return $this->get(AmazonPayConstants::ERROR_REPORT_LEVEL);
    }

    /**
     * @return bool
     */
    public function getCaptureNow()
    {
        return (bool)$this->get(AmazonPayConstants::CAPTURE_NOW, false);
    }

    /**
     * @return int
     */
    public function getAuthTransactionTimeout()
    {
        return (int)$this->get(AmazonPayConstants::AUTH_TRANSACTION_TIMEOUT, 0);
    }

    /**
     * @return string
     */
    public function getWidgetScriptPath()
    {
        return $this->get(AmazonPayConstants::WIDGET_SCRIPT_PATH);
    }

    /**
     * @return string
     */
    public function getWidgetScriptPathSandbox()
    {
        return $this->get(AmazonPayConstants::WIDGET_SCRIPT_PATH_SANDBOX);
    }

    /**
     * @return bool
     */
    public function getPopupLogin()
    {
        return (bool)$this->get(AmazonPayConstants::WIDGET_POPUP_LOGIN, false);
    }

    /**
     * @return string
     */
    public function getButtonSize()
    {
        return $this->get(AmazonPayConstants::WIDGET_BUTTON_SIZE);
    }

    /**
     * @return string
     */
    public function getButtonColor()
    {
        return $this->get(AmazonPayConstants::WIDGET_BUTTON_COLOR);
    }

    /**
     * @return string
     */
    public function getButtonType()
    {
        return $this->get(AmazonPayConstants::WIDGET_BUTTON_TYPE);
    }

    /**
     * @return string
     */
    public function getPaymentRejectRoute()
    {
        return $this->get(AmazonPayConstants::PAYMENT_REJECT_ROUTE);
    }

    /**
     * @return bool
     */
    public function getEnableIsolateLevelRead()
    {
        return (bool)$this->get(AmazonPayConstants::ENABLE_ISOLATE_LEVEL_READ, false);
    }
}
