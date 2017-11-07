<?php


/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class UpdateOrderCaptureStatusTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        if (!$amazonPayCallTransfer->getAmazonpayPayment()->getCaptureDetails()
            || !$amazonPayCallTransfer->getAmazonpayPayment()->getCaptureDetails()->getAmazonCaptureId()) {
            return $amazonPayCallTransfer;
        }

        $amazonPayCallTransfer = parent::execute($amazonPayCallTransfer);

        if (!$this->apiResponse->getHeader()->getIsSuccess()) {
            return $amazonPayCallTransfer;
        }

        if ($this->apiResponse->getCaptureDetails()->getAmazonCaptureId()) {
            $this->paymentEntity->setAmazonCaptureId(
                $this->apiResponse->getCaptureDetails()->getAmazonCaptureId()
            );
        }

        $newStatus = $this->getPaymentStatus($this->apiResponse->getCaptureDetails()->getCaptureStatus());

        $this->paymentEntity->setStatus($newStatus);
        $this->paymentEntity->save();

        return $amazonPayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $status
     *
     * @return string
     */
    protected function getPaymentStatus(AmazonpayStatusTransfer $status)
    {
        if ($status->getIsDeclined()) {
            return AmazonPayConfig::OMS_STATUS_CAPTURE_DECLINED;
        }

        if ($status->getIsCompleted()) {
            return AmazonPayConfig::OMS_STATUS_CAPTURE_COMPLETED;
        }

        if ($status->getIsClosed()) {
            return AmazonPayConfig::OMS_STATUS_CAPTURE_CLOSED;
        }

        if ($status->getIsPending()) {
            return AmazonPayConfig::OMS_STATUS_CAPTURE_PENDING;
        }

        return AmazonPayConfig::OMS_STATUS_CANCELLED;
    }
}
