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
use SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface;
use SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface;

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
     * @param \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface $amazonpayQueryContainer
     * @param \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface $converter
     * @param \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface $refundOrderModel
     */
    public function __construct(
        CallAdapterInterface $executionAdapter,
        AmazonPayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger,
        AmazonPayQueryContainerInterface $amazonpayQueryContainer,
        AmazonPayTransferToEntityConverterInterface $converter,
        RefundOrderInterface $refundOrderModel
    ) {

        parent::__construct($executionAdapter, $config, $transactionLogger, $amazonpayQueryContainer, $converter);

        $this->refundOrderModel = $refundOrderModel;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        if (!$amazonpayCallTransfer->getAmazonpayPayment()->getRefundDetails()->getAmazonRefundId()) {
            return $amazonpayCallTransfer;
        }

        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        if (!$this->apiResponse->getHeader()->getIsSuccess()) {
            return $amazonpayCallTransfer;
        }

        $isPartialProcessing = $this->isPartialProcessing($this->paymentEntity, $amazonpayCallTransfer);

        if ($isPartialProcessing) {
            $this->paymentEntity = $this->duplicatePaymentEntity($this->paymentEntity);
        }

        $status = $this->getPaymentStatus($this->apiResponse->getRefundDetails()->getRefundStatus());

        $refundIsRequired = ($status === AmazonPayConfig::OMS_STATUS_REFUND_COMPLETED
            && $this->paymentEntity->getStatus() !== $status);

        $this->paymentEntity->setStatus($status);
        $this->paymentEntity->save();

        if ($isPartialProcessing) {
            $this->assignAmazonpayPaymentToItemsIfNew($this->paymentEntity, $amazonpayCallTransfer);
        }

        if ($refundIsRequired) {
            $this->refundOrderModel->refundPayment($this->paymentEntity);
        }

        return $amazonpayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $status
     *
     * @return string
     */
    protected function getPaymentStatus(AmazonpayStatusTransfer $status)
    {
        if ($status->getIsPending()) {
            return AmazonPayConfig::OMS_STATUS_REFUND_PENDING;
        }

        if ($status->getIsDeclined()) {
            return AmazonPayConfig::OMS_STATUS_REFUND_DECLINED;
        }

        if ($status->getIsCompleted()) {
            return AmazonPayConfig::OMS_STATUS_REFUND_COMPLETED;
        }

        return AmazonPayConfig::OMS_STATUS_CANCELLED;
    }
}
