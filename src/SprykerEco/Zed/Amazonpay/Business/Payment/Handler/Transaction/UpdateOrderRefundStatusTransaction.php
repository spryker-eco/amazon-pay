<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface;
use SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayTransferToEntityConverterInterface;
use SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;

class UpdateOrderRefundStatusTransaction extends AbstractAmazonpayTransaction
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface
     */
    protected $refundOrderModel;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     * @param \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     * @param \SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayTransferToEntityConverterInterface $converter
     * @param \SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface $refundOrderModel
     */
    public function __construct(
        CallAdapterInterface $executionAdapter,
        AmazonpayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger,
        AmazonpayQueryContainerInterface $amazonpayQueryContainer,
        AmazonpayTransferToEntityConverterInterface $converter,
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

        $refundIsRequired = ($status === AmazonpayConstants::OMS_STATUS_REFUND_COMPLETED
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
            return AmazonpayConstants::OMS_STATUS_REFUND_PENDING;
        }

        if ($status->getIsDeclined()) {
            return AmazonpayConstants::OMS_STATUS_REFUND_DECLINED;
        }

        if ($status->getIsCompleted()) {
            return AmazonpayConstants::OMS_STATUS_REFUND_COMPLETED;
        }

        return AmazonpayConstants::OMS_STATUS_CANCELLED;
    }

}
