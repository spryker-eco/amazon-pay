<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @api
 *
 * @method \SprykerEco\Zed\Amazonpay\Business\AmazonpayBusinessFactory getFactory()
 */
class AmazonpayFacade extends AbstractFacade implements AmazonpayFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleCartWithAmazonpay(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createQuoteUpdateFactory()
            ->createQuoteDataInitializer()
            ->update($quoteTransfer);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function authorizeOrderItems(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->getFactory()
            ->createTransactionFactory()
            ->createAuthorizeTransaction()
            ->execute($amazonpayCallTransfer);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $this->getFactory()
            ->createOrderSaver()
            ->saveOrderPayment($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $headers
     * @param string $body
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function convertAmazonpayIpnRequest(array $headers, $body)
    {
        return $this->getFactory()
            ->createAdapterFactory()
            ->createIpnRequestAdapter($headers, $body)
            ->getIpnRequest();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $ipnRequestTransfer
     *
     * @return void
     */
    public function handleAmazonpayIpnRequest(AbstractTransfer $ipnRequestTransfer)
    {
        $this->getFactory()
            ->createIpnFactory()
            ->createIpnRequestFactory()
            ->createConcreteIpnRequestHandler($ipnRequestTransfer)
            ->handle($ipnRequestTransfer);
    }
}
