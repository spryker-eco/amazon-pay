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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @api
 *
 * @method \SprykerEco\Zed\AmazonPay\Business\AmazonPayBusinessFactory getFactory()
 */
class AmazonPayFacade extends AbstractFacade implements AmazonPayFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleCartWithAmazonPay(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createQuoteUpdateFactory()
            ->createQuoteUpdaterCollection()
            ->update($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addSelectedAddressToQuote(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createQuoteUpdateFactory()
            ->createShippingAddressQuoteDataUpdater()
            ->update($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addSelectedShipmentMethodToQuote(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createQuoteUpdateFactory()
            ->createShipmentDataQuoteUpdater()
            ->update($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function confirmPurchase(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createConfirmPurchaseTransaction()
            ->execute($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function captureOrder(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createCaptureAuthorizedTransaction()
            ->execute($amazonpayCallTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function cancelOrder(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createCancelOrderTransactionSequence()
            ->execute($amazonpayCallTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function closeOrder(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createCloseCapturedOrderTransaction()
            ->execute($amazonpayCallTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function refundOrder(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createRefundOrderTransaction()
            ->execute($amazonpayCallTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function reauthorizeExpiredOrder(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createReauthorizeExpiredOrderTransaction()
            ->execute($amazonpayCallTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function authorizeOrderItems(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createAuthorizeTransaction()
            ->execute($amazonpayCallTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function authorizeOrder(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createOrderAuthorizer()
            ->authorizeOrder($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function reauthorizeSuspendedOrder(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createReauthorizeOrderTransaction()
            ->execute($amazonpayCallTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function updateAuthorizationStatus(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createUpdateOrderAuthorizationStatusTransaction()
            ->execute($amazonpayCallTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function updateCaptureStatus(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createUpdateOrderCaptureStatusHandler()
            ->execute($amazonpayCallTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function updateRefundStatus(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createUpdateOrderRefundStatusTransaction()
            ->execute($amazonpayCallTransfer);
    }

    /**
     * {@inheritDoc}
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
    ) {
        $this->getFactory()
            ->createOrderSaver()
            ->saveOrderPayment($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $headers
     * @param string $body
     *
     * @return \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer
     */
    public function convertAmazonPayIpnRequest(array $headers, $body)
    {
        return $this->getFactory()
            ->createAdapterFactory()
            ->createIpnRequestAdapter($headers, $body)
            ->getIpnRequest($body);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $ipnRequestTransfer
     *
     * @return void
     */
    public function handleAmazonPayIpnRequest(AmazonpayIpnPaymentRequestTransfer $ipnRequestTransfer)
    {
        $this->getFactory()
            ->createIpnFactory()
            ->createIpnRequestFactory()
            ->getConcreteIpnRequestHandler($ipnRequestTransfer)
            ->handle($ipnRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderInfo(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createAmazonpayOrderInfoHydrator()
            ->hydrateOrderInfo($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param int[] $alreadyAffectedItems
     * @param string $eventName
     *
     * @return void
     */
    public function triggerEventForRelatedItems(AmazonpayCallTransfer $amazonpayCallTransfer, array $alreadyAffectedItems, $eventName)
    {
        $this->getFactory()
            ->createRelatedItemsUpdateModel()
            ->triggerEvent($amazonpayCallTransfer, $alreadyAffectedItems, $eventName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function placeOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        return $this->getFactory()
            ->createPlacement()
            ->placeOrder($quoteTransfer, $checkoutResponseTransfer);
    }
}
