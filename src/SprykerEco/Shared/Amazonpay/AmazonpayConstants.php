<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Amazonpay;

interface AmazonpayConstants
{

    const ACCESS_KEY_ID = 'ACCESS_KEY_ID';
    const CLIENT_ID = 'CLIENT_ID';
    const SELLER_ID = 'SELLER_ID';
    const SECRET_ACCESS_KEY = 'SECRET_ACCESS_KEY';
    const CLIENT_SECRET = 'CLIENT_SECRET';
    const SANDBOX = 'SANDBOX';
    const REGION = 'DE';
    const STORE_NAME = 'STORE_NAME';
    const CURRENCY_CODE = 'EUR';
    const ERROR_REPORT_LEVEL = 'ERROR_REPORT_LEVEL';
    const CAPTURE_NOW = 'CAPTURE_NOW';
    const AUTH_TRANSACTION_TIMEOUT = 'AUTH_TRANSACTION_TIMEOUT';

    const PAYMENT_METHOD = 'Amazon Pay';
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
    const OMS_STATUS_AUTH_OPEN = 'auth open';
    const OMS_STATUS_AUTH_EXPIRED = 'auth expired';
    const OMS_STATUS_AUTH_CLOSED = 'auth closed';
    const OMS_STATUS_PAYMENT_METHOD_CHANGED = 'payment method changed';

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

    const REASON_CODE_EXPIRED_UNUSED = 'ExpiredUnused';
    const REASON_CODE_SELLER_CLOSED = 'SellerClosed';
    const REASON_CODE_PAYMENT_METHOD_INVALID = 'InvalidPaymentMethod';
    const REASON_CODE_AMAZON_CLOSED = 'AmazonClosed';

    const IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE = 'PaymentAuthorize';
    const IPN_REQUEST_TYPE_PAYMENT_CAPTURE = 'PaymentCapture';
    const IPN_REQUEST_TYPE_PAYMENT_REFUND = 'PaymentRefund';
    const IPN_REQUEST_TYPE_ORDER_REFERENCE_NOTIFICATION = 'OrderReferenceNotification';

}
