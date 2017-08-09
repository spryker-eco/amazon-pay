<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class HandleDeclinedOrderTransaction implements AmazonpayTransactionInterface
{

    const ORDER_REFERENCE_STATUS_OPEN = 'Open';

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    protected $getOrderReferenceDetailsTransaction;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\QuoteTransactionInterface
     */
    protected $cancelOrderTransaction;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\GetOrderReferenceDetailsTransaction $getOrderReferenceDetailsTransaction
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\CancelPreOrderTransaction $cancelOrderTransaction
     */
    public function __construct(
        GetOrderReferenceDetailsTransaction $getOrderReferenceDetailsTransaction,
        CancelPreOrderTransaction $cancelOrderTransaction
    ) {
        $this->getOrderReferenceDetailsTransaction = $getOrderReferenceDetailsTransaction;
        $this->cancelOrderTransaction = $cancelOrderTransaction;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        if (!$amazonpayCallTransfer
                ->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsDeclined()
        ) {
            return $amazonpayCallTransfer;
        }

        if ($amazonpayCallTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsPaymentMethodInvalid()
        ) {
            return $amazonpayCallTransfer;
        }

        $checkOrderStatus = $this->getOrderReferenceDetailsTransaction->execute($amazonpayCallTransfer);

        if ($checkOrderStatus->getAmazonpayPayment()
                ->getOrderReferenceStatus()
                ->getIsOpen()
        ) {
            $this->cancelOrderTransaction->execute($amazonpayCallTransfer);
        }

        return $amazonpayCallTransfer;
    }

}
