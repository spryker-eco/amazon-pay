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
    const WIDGET_BUTTON_TYPE_FULL = 'PwA';
    const WIDGET_BUTTON_TYPE_SHORT = 'Pay';
    const WIDGET_BUTTON_TYPE_SQUARE = 'A';
    const WIDGET_BUTTON_COLOR_GOLD = 'Gold';
    const WIDGET_BUTTON_COLOR_LIGHT_GRAY = 'LightGray';
    const WIDGET_BUTTON_COLOR_DARK_GRAY = 'DarkGray';
    const WIDGET_BUTTON_SIZE_SMALL = 'small';
    const WIDGET_BUTTON_SIZE_MEDIUM = 'medium';
    const WIDGET_BUTTON_SIZE_LARGE = 'large';
    const WIDGET_BUTTON_SIZE_XLARGE = 'x-large';
    const PROVIDER_NAME = 'Amazon Pay';

    const OMS_STATUS_AUTH_OPEN = 'auth open';
    const OMS_STATUS_AUTH_OPEN_WITHOUT_CANCEL = 'auth open without cancel';
    const OMS_STATUS_CAPTURE_COMPLETED = 'capture completed';
    const OMS_STATUS_CAPTURE_PENDING = 'capture pending';
    const OMS_STATUS_CANCELLED = 'cancelled';


    const OMS_STATUS_NEW = 'new';
    const OMS_STATUS_AUTHORIZED = 'authorized';
    const OMS_STATUS_DECLINED = 'declined';
    const OMS_STATUS_CAPTURED = 'captured';
    const OMS_STATUS_CLOSED = 'closed';

    const OMS_STATUS_AUTH_PENDING = 'auth pending';
    const OMS_STATUS_AUTH_DECLINED = 'auth declined';
    const OMS_STATUS_AUTH_SUSPENDED = 'auth suspended';
    const OMS_STATUS_MANUAL_AUTH_REQUIRED = 'manual auth requried';
    const OMS_STATUS_AUTH_TRANSACTION_TIMED_OUT = 'auth transaction timed out';
    const OMS_STATUS_AUTH_EXPIRED = 'auth expired';
    const OMS_STATUS_AUTH_CLOSED = 'auth closed';
    const OMS_STATUS_PAYMENT_METHOD_CHANGED = 'payment method changed';
    const OMS_STATUS_STATUS_CHANGED = 'status changed';

    const OMS_STATUS_CAPTURE_DECLINED = 'capture declined';
    const OMS_STATUS_CAPTURE_CLOSED = 'capture closed';

    const OMS_STATUS_REFUND_PENDING = 'refund pending';
    const OMS_STATUS_REFUND_DECLINED = 'refund declined';
    const OMS_STATUS_REFUND_COMPLETED = 'refund completed';

    const OMS_EVENT_UPDATE_ORDER_STATUS = 'update order status';
    const OMS_EVENT_UPDATE_AUTH_STATUS = 'update authorization status';
    const OMS_EVENT_UPDATE_CAPTURE_STATUS = 'update capture status';
    const OMS_EVENT_UPDATE_REFUND_STATUS = 'update refund status';
    const OMS_EVENT_CAPTURE = 'capture';
    const OMS_EVENT_UPDATE_SUSPENDED_ORDER = 'update suspended order';
    const OMS_EVENT_CLOSE = 'close';
    const OMS_EVENT_REFUND = 'refund';

    const OMS_FLAG_NOT_AUTH = 'not auth';
    const OMS_FLAG_NOT_CAPTURED = 'not captured';

    const STATUS_NEW = 'STATUS_NEW';
    const STATUS_DECLINED = 'STATUS_DECLINED';//setIsDeclined
    const STATUS_CANCELLED = 'STATUS_CANCELLED';//setIsCancelled
    const STATUS_CLOSED = 'STATUS_CLOSED';//setIsClosed
    const STATUS_SUSPENDED = 'STATUS_SUSPENDED';
    const STATUS_PENDING = 'STATUS_PENDING';//setIsPending
    const STATUS_OPEN = 'STATUS_OPEN';//setIsOpen
    const STATUS_COMPLETED = 'STATUS_COMPLETED';//setIsCompleted
    const STATUS_EXPIRED = 'STATUS_EXPIRED';//setIsReauthorizable
    const STATUS_PAYMENT_METHOD_INVALID = 'STATUS_CODE_PAYMENT_METHOD_INVALID';//setIsPaymentMethodInvalid
    const STATUS_AMAZON_CLOSED = 'STATUS_CODE_AMAZON_CLOSED';//setIsClosedByAmazon
    const STATUS_TRANSACTION_TIMED_OUT = 'STATUS_CODE_TRANSACTION_TIMED_OUT';//setIsTransactionTimedOut
    const STATUS_PAYMENT_METHOD_CHANGED = 'STATUS_PAYMENT_METHOD_CHANGED';//setIsTransactionTimedOut

    const IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE = 'PaymentAuthorize';
    const IPN_REQUEST_TYPE_PAYMENT_CAPTURE = 'PaymentCapture';
    const IPN_REQUEST_TYPE_PAYMENT_REFUND = 'PaymentRefund';
    const IPN_REQUEST_TYPE_ORDER_REFERENCE_NOTIFICATION = 'OrderReferenceNotification';

    const PREFIX_AMAZONPAY_PAYMENT_ERROR = 'amazonpay.payment.error.';

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
        return (bool)$this->get(AmazonPayConstants::SANDBOX);
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
        return (bool)$this->get(AmazonPayConstants::CAPTURE_NOW);
    }

    /**
     * @return int
     */
    public function getAuthTransactionTimeout()
    {
        return (int)$this->get(AmazonPayConstants::AUTH_TRANSACTION_TIMEOUT);
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
        return (bool)$this->get(AmazonPayConstants::WIDGET_POPUP_LOGIN);
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
}
