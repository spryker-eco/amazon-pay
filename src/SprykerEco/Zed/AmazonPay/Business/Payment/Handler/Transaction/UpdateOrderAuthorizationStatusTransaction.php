<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class UpdateOrderAuthorizationStatusTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        if (!$amazonpayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAmazonAuthorizationId()) {
            return $amazonpayCallTransfer;
        }

        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        if (!$this->isPaymentSuccess($amazonpayCallTransfer)) {
            return $amazonpayCallTransfer;
        }

        $amazonPayment = $amazonpayCallTransfer->getAmazonpayPayment();
        $status = $amazonPayment->getAuthorizationDetails()->getAuthorizationStatus();

        if ($amazonPayment->getAuthorizationDetails()->getIdList()) {
            $this->paymentEntity->setAmazonCaptureId(
                $amazonPayment->getAuthorizationDetails()->getIdList()
            )
                ->setStatus(
                    $status->getIsClosed()
                        ? AmazonPayConfig::OMS_STATUS_CLOSED
                        : AmazonPayConfig::OMS_STATUS_CAPTURE_COMPLETED
                )
                ->save();

            return $amazonpayCallTransfer;
        }

        if ($status->getIsPending()) {
            return $amazonpayCallTransfer;
        }

        $paymentStatus = $this->getPaymentStatus($status);

        if ($paymentStatus !== false) {
            $this->paymentEntity->setStatus($paymentStatus);
        }
        if ($this->apiResponse->getCaptureDetails() &&
            $this->apiResponse->getCaptureDetails()->getAmazonCaptureId()) {
            $this->paymentEntity->setAmazonCaptureId(
                $this->apiResponse->getCaptureDetails()->getAmazonCaptureId()
            );
        }

        $this->paymentEntity->save();

        return $amazonpayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $status
     *
     * @return bool|string
     */
    protected function getPaymentStatus(AmazonpayStatusTransfer $status)
    {
        if ($status->getIsDeclined()) {
            if ($status->getIsSuspended()) {
                return AmazonPayConfig::OMS_STATUS_AUTH_SUSPENDED;
            }

            if ($status->getIsTransactionTimedOut()) {
                return AmazonPayConfig::OMS_STATUS_AUTH_TRANSACTION_TIMED_OUT;
            }

            return AmazonPayConfig::OMS_STATUS_AUTH_DECLINED;
        }

        if ($status->getIsOpen()) {
            return AmazonPayConfig::OMS_STATUS_AUTH_OPEN;
        }

        if ($status->getIsClosed()) {
            if ($status->getIsReauthorizable()) {
                return AmazonPayConfig::OMS_STATUS_AUTH_EXPIRED;
            }

            return AmazonPayConfig::OMS_STATUS_AUTH_CLOSED;
        }

        return false;
    }
}
