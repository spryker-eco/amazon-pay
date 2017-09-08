<?php


/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class UpdateOrderCaptureStatusTransaction extends AbstractAmazonpayTransaction
{

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        if (!$amazonpayCallTransfer->getAmazonpayPayment()->getCaptureDetails()
            || !$amazonpayCallTransfer->getAmazonpayPayment()->getCaptureDetails()->getAmazonCaptureId()) {
            return $amazonpayCallTransfer;
        }

        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        if (!$this->apiResponse->getHeader()->getIsSuccess()) {
            return $amazonpayCallTransfer;
        }

        if ($this->apiResponse->getCaptureDetails()->getIdList()) {
            $this->paymentEntity->setAmazonCaptureId(
                $this->apiResponse->getCaptureDetails()->getIdList()
            );
        }

        $newStatus = $this->getPaymentStatus($this->apiResponse->getCaptureDetails()->getCaptureStatus());

        $this->paymentEntity->setStatus($newStatus);
        $this->paymentEntity->save();

        return $amazonpayCallTransfer;
    }

    /**
     * @param AmazonpayStatusTransfer $status
     *
     * @return string
     */
    protected function getPaymentStatus(AmazonpayStatusTransfer $status)
    {
        if ($status->getIsDeclined()) {
            return AmazonpayConstants::OMS_STATUS_CAPTURE_DECLINED;
        }

        if ($status->getIsCompleted()) {
            return AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED;
        }

        if ($status->getIsClosed()) {
            return AmazonpayConstants::OMS_STATUS_CAPTURE_CLOSED;
        }

        if ($status->getIsPending()) {
            return AmazonpayConstants::OMS_STATUS_CAPTURE_PENDING;
        }

        return AmazonpayConstants::OMS_STATUS_CANCELLED;
    }

}
