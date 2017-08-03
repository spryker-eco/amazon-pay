<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class CaptureOrderTransaction extends AbstractAmazonpayTransaction
{

    /**
     * @var \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected $apiResponse;

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

        $this->updatePaymentStatus($amazonpayCallTransfer->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus());

        return $amazonpayCallTransfer;
    }

    /**
     * @param AmazonpayStatusTransfer $captureStatus
     */
    protected function updatePaymentStatus(AmazonpayStatusTransfer $captureStatus)
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

        if ($paymentStatus !== false) {
            $this->paymentEntity->setStatus($paymentStatus);
            $this->paymentEntity->save();
        }
    }

}
