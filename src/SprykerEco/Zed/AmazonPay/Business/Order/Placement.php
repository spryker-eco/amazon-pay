<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Order;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\TransactionCollectionInterface;

class Placement implements PlacementInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\TransactionCollectionInterface
     */
    protected $confirmationTransactionCollection;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\TransactionCollectionInterface $confirmationTransactionCollection
     */
    public function __construct(
        TransactionCollectionInterface $confirmationTransactionCollection
    ) {
        $this->confirmationTransactionCollection = $confirmationTransactionCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function placeOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($quoteTransfer->getAmazonpayPayment() === null) {
            return true;
        }

        $this->confirmationTransactionCollection->execute($quoteTransfer);

        if ($quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            return true;
        }

        $checkoutResponseTransfer->setIsSuccess(false);
        $checkoutResponseTransfer->addError(
            $this->createCheckoutErrorFromAmazonPaymentResponse(
                $quoteTransfer->getAmazonpayPayment()
            )
        );

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorFromAmazonPaymentResponse(AmazonpayPaymentTransfer $amazonpayPaymentTransfer)
    {
        $checkoutErrorTransfer = new CheckoutErrorTransfer();
        $checkoutErrorTransfer->setMessage($amazonpayPaymentTransfer->getResponseHeader()->getErrorMessage());

        return $checkoutErrorTransfer;
    }
}
