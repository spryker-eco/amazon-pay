<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;
use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface;
use SprykerEco\Zed\AmazonPay\Business\Order\PaymentProcessorInterface;
use SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;

class UpdateOrderRefundStatusTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface
     */
    protected $refundOrderModel;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     * @param \SprykerEco\Zed\AmazonPay\Business\Order\PaymentProcessorInterface $paymentProcessor
     * @param \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface $refundOrderModel
     */
    public function __construct(
        CallAdapterInterface $executionAdapter,
        AmazonPayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger,
        PaymentProcessorInterface $paymentProcessor,
        RefundOrderInterface $refundOrderModel
    ) {

        parent::__construct($executionAdapter, $config, $transactionLogger, $paymentProcessor);

        $this->refundOrderModel = $refundOrderModel;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        if (!$this->isAllowed($amazonPayCallTransfer)) {
            return $amazonPayCallTransfer;
        }

        $amazonPayCallTransfer = parent::execute($amazonPayCallTransfer);

        $this->updatePayment($amazonPayCallTransfer);

        return $amazonPayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return bool
     */
    protected function isAllowed(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        return !empty($amazonPayCallTransfer->getAmazonpayPayment()->getRefundDetails()->getAmazonRefundId());
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    protected function updatePayment(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        if (!$this->apiResponse->getResponseHeader()->getIsSuccess()) {
            return;
        }

        $isPartialProcessing = $this->isPartialProcessing($this->paymentEntity, $amazonPayCallTransfer);

        if ($isPartialProcessing) {
            $this->paymentEntity = $this->paymentProcessor->duplicatePaymentEntity($this->paymentEntity);
        }

        $status = $this->apiResponse->getRefundDetails()->getRefundStatus()->getState();

        $refundIsRequired = ($status === AmazonPayConfig::STATUS_COMPLETED
            && $this->paymentEntity->getStatus() !== $status);

        $this->paymentEntity->setStatus($status);
        $this->paymentEntity->save();

        if ($isPartialProcessing) {
            $this->paymentProcessor->assignAmazonpayPaymentToItems($this->paymentEntity, $amazonPayCallTransfer);
        }

        if ($refundIsRequired) {
            $this->refundOrderModel->refundPayment($this->paymentEntity);
        }
    }
}
