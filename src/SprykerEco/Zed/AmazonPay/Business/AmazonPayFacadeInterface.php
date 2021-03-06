<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

/**
 * @api
 *
 * @method \SprykerEco\Zed\AmazonPay\Business\AmazonPayBusinessFactory getFactory()
 */
interface AmazonPayFacadeInterface
{
    /**
     * Specification
     * - Updates quote after user clicks Amazon Pay bundle
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleCartWithAmazonPay(QuoteTransfer $quoteTransfer);

    /**
     * Specification
     * - Updates quote with address chosen by user via amazonpay widget
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addSelectedAddressToQuote(QuoteTransfer $quoteTransfer);

    /**
     * Specification
     * - Updates quote with shipment method chosen by user
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addSelectedShipmentMethodToQuote(QuoteTransfer $quoteTransfer);

    /**
     * Specification
     * - Sends SetOrderReference and ConfirmOrderReference requests.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function confirmPurchase(QuoteTransfer $quoteTransfer);

    /**
     *  Specification
     * - send capture() API call to Amazon
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function captureOrder(AmazonpayCallTransfer $amazonpayCallTransfer);

    /**
     * Specification
     * - send an API call to Amazon that order is closed
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function closeOrder(AmazonpayCallTransfer $amazonpayCallTransfer);

    /**
     * Specification
     * - send an API call to Amazon that order is cancelled
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function cancelOrder(AmazonpayCallTransfer $amazonpayCallTransfer);

    /**
     * Specification:
     * - makes an API call and sends calculated amount to Amazon Pay
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function refundOrder(AmazonpayCallTransfer $amazonpayCallTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function reauthorizeExpiredOrder(AmazonpayCallTransfer $amazonpayCallTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function authorizeOrderItems(AmazonpayCallTransfer $amazonpayCallTransfer);

    /**
     * Specification:
     * - Sends Authorize api call for confirmed order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function authorizeOrder(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function reauthorizeSuspendedOrder(AmazonpayCallTransfer $amazonpayCallTransfer);

    /**
     * Specification:
     * - Persists an order in the database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    );

    /**
     * Specification:
     * - Converts amazon-specific income data into Transfer Object
     * - Concrete transfer object depends on incoming data
     *
     * @api
     *
     * @param array $headers
     * @param string $body
     *
     * @return \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer
     */
    public function convertAmazonPayIpnRequest(array $headers, $body);

    /**
     * Specification:
     * - Handles IPN request call
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $ipnRequestTransfer
     *
     * @return void
     */
    public function handleAmazonPayIpnRequest(AmazonpayIpnPaymentRequestTransfer $ipnRequestTransfer);

    /**
     * Specification:
     * - Updates auth status of an order
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function updateAuthorizationStatus(AmazonpayCallTransfer $amazonpayCallTransfer);

    /**
     *  Specification:
     * - Updates capture status of an order
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function updateCaptureStatus(AmazonpayCallTransfer $amazonpayCallTransfer);

    /**
     *  Specification:
     * - Updates refund status of an order
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function updateRefundStatus(AmazonpayCallTransfer $amazonpayCallTransfer);

    /**
     *  Specification:
     * - load amazonpay order info
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderInfo(OrderTransfer $orderTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param int[] $alreadyAffectedItems
     * @param string $eventName
     *
     * @return void
     */
    public function triggerEventForRelatedItems(AmazonpayCallTransfer $amazonpayCallTransfer, array $alreadyAffectedItems, $eventName);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function placeOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);
}
