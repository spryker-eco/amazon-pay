<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

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

        $amazonPayment = $amazonpayCallTransfer->getAmazonpayPayment();

        if (!$amazonPayment->getResponseHeader()->getIsSuccess()) {
            return $amazonpayCallTransfer;
        }
        $status = $amazonPayment->getAuthorizationDetails()->getAuthorizationStatus();

        if ($amazonPayment->getAuthorizationDetails()->getIdList()) {
            $this->paymentEntity->setAmazonCaptureId(
                $amazonPayment->getAuthorizationDetails()->getIdList()
            )
                ->setStatus(
                    $status->getIsClosed()
                        ? AmazonpayConfig::OMS_STATUS_CLOSED
                        : AmazonpayConfig::OMS_STATUS_CAPTURE_COMPLETED
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
                return AmazonpayConfig::OMS_STATUS_AUTH_SUSPENDED;
            }

            if ($status->getIsTransactionTimedOut()) {
                return AmazonpayConfig::OMS_STATUS_AUTH_TRANSACTION_TIMED_OUT;
            }

            return AmazonpayConfig::OMS_STATUS_AUTH_DECLINED;
        }

        if ($status->getIsOpen()) {
            return AmazonpayConfig::OMS_STATUS_AUTH_OPEN;
        }

        if ($status->getIsClosed()) {
            if ($status->getIsReauthorizable()) {
                return AmazonpayConfig::OMS_STATUS_AUTH_EXPIRED;
            }

            return AmazonpayConfig::OMS_STATUS_AUTH_CLOSED;
        }

        return false;
    }
}
