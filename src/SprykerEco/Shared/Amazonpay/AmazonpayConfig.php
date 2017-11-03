<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Amazonpay;

use Spryker\Shared\Kernel\AbstractBundleConfig;
use Spryker\Shared\Kernel\Store;

class AmazonpayConfig extends AbstractBundleConfig implements AmazonpayConfigInterface
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

    const ORDER_REFERENCE_STATUS_OPEN = 'Open';

    const OMS_STATUS_NEW = 'new';
    const OMS_STATUS_AUTHORIZED = 'authorized';
    const OMS_STATUS_DECLINED = 'declined';
    const OMS_STATUS_CAPTURED = 'captured';
    const OMS_STATUS_CANCELLED = 'cancelled';
    const OMS_STATUS_CLOSED = 'closed';

    const OMS_STATUS_AUTH_PENDING = 'auth pending';
    const OMS_STATUS_AUTH_DECLINED = 'auth declined';
    const OMS_STATUS_AUTH_SUSPENDED = 'auth suspended';
    const OMS_STATUS_MANUAL_AUTH_REQUIRED = 'manual auth requried';
    const OMS_STATUS_AUTH_TRANSACTION_TIMED_OUT = 'auth transaction timed out';
    const OMS_STATUS_AUTH_OPEN = 'auth open';
    const OMS_STATUS_AUTH_OPEN_WITHOUT_CANCEL = 'auth open without cancel';
    const OMS_STATUS_AUTH_EXPIRED = 'auth expired';
    const OMS_STATUS_AUTH_CLOSED = 'auth closed';
    const OMS_STATUS_PAYMENT_METHOD_CHANGED = 'payment method changed';
    const OMS_STATUS_STATUS_CHANGED = 'status changed';

    const OMS_STATUS_CAPTURE_PENDING = 'capture pending';
    const OMS_STATUS_CAPTURE_DECLINED = 'capture declined';
    const OMS_STATUS_CAPTURE_COMPLETED = 'capture completed';
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

    const REASON_CODE_EXPIRED_UNUSED = 'ExpiredUnused';
    const REASON_CODE_SELLER_CLOSED = 'SellerClosed';
    const REASON_CODE_PAYMENT_METHOD_INVALID = 'InvalidPaymentMethod';
    const REASON_CODE_AMAZON_CLOSED = 'AmazonClosed';
    const REASON_CODE_TRANSACTION_TIMED_OUT = 'TransactionTimedOut';

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
        return $this->get(AmazonpayConstants::CLIENT_ID);
    }

    /**
     * @return string
     */
    public function getAccessKeyId()
    {
        return $this->get(AmazonpayConstants::ACCESS_KEY_ID);
    }

    /**
     * @return string
     */
    public function getSellerId()
    {
        return $this->get(AmazonpayConstants::SELLER_ID);
    }

    /**
     * @return string
     */
    public function getSecretAccessKey()
    {
        return $this->get(AmazonpayConstants::SECRET_ACCESS_KEY);
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->get(AmazonpayConstants::CLIENT_SECRET);
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->get(AmazonpayConstants::REGION);
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
        return (bool)$this->get(AmazonpayConstants::SANDBOX);
    }

    /**
     * @return string
     */
    public function getErrorReportLevel()
    {
        return $this->get(AmazonpayConstants::ERROR_REPORT_LEVEL);
    }

    /**
     * @return bool
     */
    public function getCaptureNow()
    {
        return (bool)$this->get(AmazonpayConstants::CAPTURE_NOW);
    }

    /**
     * @return int
     */
    public function getAuthTransactionTimeout()
    {
        return (int)$this->get(AmazonpayConstants::AUTH_TRANSACTION_TIMEOUT);
    }

    /**
     * @return string
     */
    public function getWidgetScriptPath()
    {
        return $this->get(AmazonpayConstants::WIDGET_SCRIPT_PATH);
    }

    /**
     * @return string
     */
    public function getWidgetScriptPathSandbox()
    {
        return $this->get(AmazonpayConstants::WIDGET_SCRIPT_PATH_SANDBOX);
    }

    /**
     * @return bool
     */
    public function getPopupLogin()
    {
        return (bool)$this->get(AmazonpayConstants::WIDGET_POPUP_LOGIN);
    }

    /**
     * @return string
     */
    public function getButtonSize()
    {
        return $this->get(AmazonpayConstants::WIDGET_BUTTON_SIZE);
    }

    /**
     * @return string
     */
    public function getButtonColor()
    {
        return $this->get(AmazonpayConstants::WIDGET_BUTTON_COLOR);
    }

    /**
     * @return string
     */
    public function getButtonType()
    {
        return $this->get(AmazonpayConstants::WIDGET_BUTTON_TYPE);
    }

    /**
     * @return string
     */
    public function getPaymentRejectRoute()
    {
        return $this->get(AmazonpayConstants::PAYMENT_REJECT_ROUTE);
    }
}
