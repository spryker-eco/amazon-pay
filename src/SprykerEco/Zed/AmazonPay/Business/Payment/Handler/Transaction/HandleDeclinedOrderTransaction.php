<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;

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
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface
     */
    protected $transactionLogger;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface $getOrderReferenceDetailsTransaction
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\AmazonpayTransactionInterface $cancelOrderTransaction
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     */
    public function __construct(
        AmazonpayTransactionInterface $getOrderReferenceDetailsTransaction,
        AmazonpayTransactionInterface $cancelOrderTransaction,
        TransactionLoggerInterface $transactionLogger
    ) {
        $this->getOrderReferenceDetailsTransaction = $getOrderReferenceDetailsTransaction;
        $this->cancelOrderTransaction = $cancelOrderTransaction;
        $this->transactionLogger = $transactionLogger;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $stateName = $amazonPayCallTransfer
            ->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus()
            ->getState();

        $this->transactionLogger->logMessage(
            $amazonPayCallTransfer->getAmazonpayPayment(),
            sprintf('Status %s', $stateName)
        );

        if ($stateName !== AmazonPayConfig::STATUS_DECLINED) {
            return $amazonPayCallTransfer;
        }

        if ($stateName === AmazonPayConfig::STATUS_PAYMENT_METHOD_INVALID) {
            return $amazonPayCallTransfer;
        }

        $this->checkOrderStatus($amazonPayCallTransfer);

        return $amazonPayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    protected function checkOrderStatus(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $checkOrderStatus = $this->getOrderReferenceDetailsTransaction->execute($amazonPayCallTransfer);
        $this->transactionLogger->logMessage(
            $amazonPayCallTransfer->getAmazonpayPayment(),
            sprintf('Checking state: %s', json_encode($checkOrderStatus->toArray()))
        );


        if ($checkOrderStatus->getAmazonpayPayment()
                ->getOrderReferenceStatus()
                ->getState() === AmazonPayConfig::STATUS_OPEN
        ) {
            $this->transactionLogger->logMessage(
                $amazonPayCallTransfer->getAmazonpayPayment(),
                'Calling to Cancel'
            );
            $this->cancelOrderTransaction->execute($amazonPayCallTransfer);
        }
    }
}
