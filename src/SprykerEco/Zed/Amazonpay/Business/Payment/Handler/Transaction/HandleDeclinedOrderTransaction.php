<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;

class HandleDeclinedOrderTransaction extends AbstractQuoteTransaction
{

    const ORDER_REFERENCE_STATUS_OPEN = 'Open';

    /**
     * @var \Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    protected $getOrderReferenceDetailsTransaction;

    /**
     * @var \Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    protected $cancelOrderTransaction;

    /**
     * @param \Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\GetOrderReferenceDetailsTransaction $getOrderReferenceDetailsTransaction
     * @param \Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\CancelPreOrderTransaction $cancelOrderTransaction
     */
    public function __construct(
        GetOrderReferenceDetailsTransaction $getOrderReferenceDetailsTransaction,
        CancelPreOrderTransaction $cancelOrderTransaction
    ) {
        $this->getOrderReferenceDetailsTransaction = $getOrderReferenceDetailsTransaction;
        $this->cancelOrderTransaction = $cancelOrderTransaction;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer
                ->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsDeclined()
        ) {
            return $quoteTransfer;
        }

        if ($quoteTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsPaymentMethodInvalid()
        ) {
            return $quoteTransfer;
        }

        $checkOrderStatus = $this->getOrderReferenceDetailsTransaction->execute($quoteTransfer);

        if ($checkOrderStatus->getAmazonpayPayment()->getOrderReferenceStatus()->getIsOpen()) {
            $this->cancelOrderTransaction->execute($quoteTransfer);
        }

        return $quoteTransfer;
    }

}
