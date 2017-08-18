<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class CaptureOrderTransaction extends AbstractAmazonpayTransaction
{

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $amazonpayCallTransfer->getAmazonpayPayment()->getCaptureDetails()->setCaptureReferenceId(
            $this->generateOperationReferenceId($amazonpayCallTransfer)
        );

        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);
        $isPartialProcessing = $this->isPartialProcessing($this->paymentEntity, $amazonpayCallTransfer);

        if ($isPartialProcessing) {
            $this->paymentEntity = $this->duplicatePaymentEntity($this->paymentEntity);
        }

        if ($amazonpayCallTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            $amazonpayCallTransfer->getAmazonpayPayment()->setCaptureDetails(
                $this->apiResponse->getCaptureDetails()
            );

            $this->paymentEntity->setAmazonCaptureId(
                $this->apiResponse->getCaptureDetails()->getAmazonCaptureId()
            );

            $this->paymentEntity->setCaptureReferenceId(
                $this->apiResponse->getCaptureDetails()->getCaptureReferenceId()
            );
        }

        $newStatus = $this->getPaymentStatus($amazonpayCallTransfer->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus());

        if ($newStatus !== false) {
            $this->paymentEntity->setStatus($newStatus);
        }

        $this->paymentEntity->save();

        if ($isPartialProcessing) {
            $this->assignAmazonpayPaymentToItemsIfNew($this->paymentEntity, $amazonpayCallTransfer);
        }

        return $amazonpayCallTransfer;
    }

    /**
     * @param AmazonpayStatusTransfer $captureStatus
     *
     * @return bool
     */
    protected function getPaymentStatus(AmazonpayStatusTransfer $captureStatus)
    {
        $paymentStatus = false;

        if ($captureStatus->getIsDeclined()) {
            $paymentStatus = AmazonpayConstants::OMS_STATUS_CAPTURE_DECLINED;
        }

        if ($captureStatus->getIsPending()) {
            $paymentStatus = AmazonpayConstants::OMS_STATUS_CAPTURE_PENDING;
        }

        if ($captureStatus->getIsCompleted()) {
            $paymentStatus = AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED;
        }

        return $paymentStatus;
    }

}
