<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

class CaptureOrderTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        if (!in_array($amazonpayCallTransfer->getAmazonpayPayment()->getStatus(), [
            AmazonpayConfig::OMS_STATUS_CAPTURE_PENDING,
            AmazonpayConfig::OMS_STATUS_AUTH_OPEN,
            AmazonpayConfig::OMS_STATUS_PAYMENT_METHOD_CHANGED,
        ], true)) {
            return $amazonpayCallTransfer;
        }

        if ($amazonpayCallTransfer->getAmazonpayPayment()->getCaptureDetails()
            && $amazonpayCallTransfer->getAmazonpayPayment()->getCaptureDetails()->getAmazonCaptureId()) {
            return $amazonpayCallTransfer;
        }

        $amazonpayCallTransfer->getAmazonpayPayment()->getCaptureDetails()->setCaptureReferenceId(
            $this->generateOperationReferenceId($amazonpayCallTransfer)
        );

        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        if (!$this->isPaymentSuccess($amazonpayCallTransfer)) {
            return $amazonpayCallTransfer;
        }

        $isPartialProcessing = $this->isPartialProcessing($this->paymentEntity, $amazonpayCallTransfer);

        if ($isPartialProcessing) {
            $this->paymentEntity = $this->duplicatePaymentEntity($this->paymentEntity);
        }

        $amazonpayCallTransfer->getAmazonpayPayment()->setCaptureDetails(
            $this->apiResponse->getCaptureDetails()
        );
        $this->paymentEntity->setAmazonCaptureId(
            $this->apiResponse->getCaptureDetails()->getAmazonCaptureId()
        );
        $this->paymentEntity->setCaptureReferenceId(
            $this->apiResponse->getCaptureDetails()->getCaptureReferenceId()
        );
        $newStatus = $this->getPaymentStatus($amazonpayCallTransfer->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus());

        if ($newStatus !== '') {
            $this->paymentEntity->setStatus($newStatus);
        }

        $this->paymentEntity->save();

        if ($isPartialProcessing) {
            $this->assignAmazonpayPaymentToItemsIfNew($this->paymentEntity, $amazonpayCallTransfer);
        }

        return $amazonpayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $captureStatus
     *
     * @return string
     */
    protected function getPaymentStatus(AmazonpayStatusTransfer $captureStatus)
    {
        if ($captureStatus->getIsDeclined()) {
            return AmazonpayConfig::OMS_STATUS_CAPTURE_DECLINED;
        }

        if ($captureStatus->getIsPending()) {
            return AmazonpayConfig::OMS_STATUS_CAPTURE_PENDING;
        }

        if ($captureStatus->getIsCompleted()) {
            return AmazonpayConfig::OMS_STATUS_CAPTURE_COMPLETED;
        }

        return '';
    }
}
