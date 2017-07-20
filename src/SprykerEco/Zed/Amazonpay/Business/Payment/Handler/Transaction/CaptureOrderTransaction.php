<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class CaptureOrderTransaction extends AbstractOrderTransaction
{

    /**
     * @var \Generated\Shared\Transfer\AmazonpayCaptureOrderResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function execute(OrderTransfer $orderTransfer)
    {
        $orderTransfer->getAmazonpayPayment()->getCaptureDetails()->setCaptureReferenceId(
            $this->generateOperationReferenceId($orderTransfer)
        );

        $orderTransfer = parent::execute($orderTransfer);

        if ($orderTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            $orderTransfer->getAmazonpayPayment()->setCaptureDetails(
                $this->apiResponse->getCaptureDetails()
            );

            $this->paymentEntity->setAmazonCaptureId(
                $this->apiResponse->getCaptureDetails()->getAmazonCaptureId()
            );

            $this->paymentEntity->setCaptureReferenceId(
                $this->apiResponse->getCaptureDetails()->getCaptureReferenceId()
            );
        }

        $this->updatePaymentStatus($orderTransfer->getAmazonpayPayment()->getCaptureDetails()->getCaptureStatus());

        return $orderTransfer;
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
