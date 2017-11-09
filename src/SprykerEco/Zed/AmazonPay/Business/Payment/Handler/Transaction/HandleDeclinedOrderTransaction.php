<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class HandleDeclinedOrderTransaction implements AmazonpayTransactionInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    protected $getOrderReferenceDetailsTransaction;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface
     */
    protected $cancelOrderTransaction;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface $getOrderReferenceDetailsTransaction
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface $cancelOrderTransaction
     */
    public function __construct(
        AmazonpayTransactionInterface $getOrderReferenceDetailsTransaction,
        AmazonpayTransactionInterface $cancelOrderTransaction
    ) {
        $this->getOrderReferenceDetailsTransaction = $getOrderReferenceDetailsTransaction;
        $this->cancelOrderTransaction = $cancelOrderTransaction;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        if ($amazonPayCallTransfer
                ->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getState() !== AmazonPayConfig::STATUS_DECLINED
        ) {
            return $amazonPayCallTransfer;
        }

        if ($amazonPayCallTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getState() === AmazonPayConfig::STATUS_PAYMENT_METHOD_INVALID
        ) {
            return $amazonPayCallTransfer;
        }

        $checkOrderStatus = $this->getOrderReferenceDetailsTransaction->execute($amazonPayCallTransfer);

        if ($checkOrderStatus->getAmazonpayPayment()
                ->getOrderReferenceStatus()
                ->getState() === AmazonPayConfig::STATUS_OPEN
        ) {
            $this->cancelOrderTransaction->execute($amazonPayCallTransfer);
        }

        return $amazonPayCallTransfer;
    }
}
