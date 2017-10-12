<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Converter;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AmazonpayTransferToEntityConverter implements AmazonpayTransferToEntityConverterInterface
{

    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    public function mapTransferToEntity(AmazonpayPaymentTransfer $amazonpayPaymentTransfer)
    {
        $responseHeader = new AmazonpayResponseHeaderTransfer();
        $responseHeader->setIsSuccess(true);

        $paymentEntity = new SpyPaymentAmazonpay();
        $paymentEntity->fromArray($amazonpayPaymentTransfer->toArray());

        return $paymentEntity;
    }

    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $entity
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return void
     */
    public function updateAfterAuthorization(SpyPaymentAmazonpay $entity, AmazonpayPaymentTransfer $amazonpayPaymentTransfer)
    {
        $entity->fromArray($amazonpayPaymentTransfer->getAuthorizationDetails()->toArray());
        $entity->setStatus(
            $this->getPaymentStatusFromTransfer(
                $amazonpayPaymentTransfer->getAuthorizationDetails()->getAuthorizationStatus()
            )
        );
    }

    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $entity
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return void
     */
    public function updateAfterRefund(SpyPaymentAmazonpay $entity, AmazonpayPaymentTransfer $amazonpayPaymentTransfer)
    {
        $entity->fromArray($amazonpayPaymentTransfer->getRefundDetails()->toArray());
        $entity->setStatus($this->getRefundStatusFromTransfer(
            $amazonpayPaymentTransfer->getRefundDetails()->getRefundStatus()
        ));
    }

    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $entity
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return void
     */
    public function updateAfterCapture(SpyPaymentAmazonpay $entity, AmazonpayPaymentTransfer $amazonpayPaymentTransfer)
    {
        $entity->fromArray($amazonpayPaymentTransfer->getCaptureDetails()->toArray());
        $entity->setStatus($this->getCaptureStatusFromTransfer(
            $amazonpayPaymentTransfer->getCaptureDetails()->getCaptureStatus()
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $amazonpayStatusTransfer
     *
     * @return string
     */
    protected function getPaymentStatusFromTransfer(AmazonpayStatusTransfer $amazonpayStatusTransfer)
    {
        if ($amazonpayStatusTransfer->getIsPending()) {
            return AmazonpayConstants::OMS_STATUS_AUTH_PENDING;
        }

        if ($amazonpayStatusTransfer->getIsDeclined()) {
            return AmazonpayConstants::OMS_STATUS_AUTH_DECLINED;
        }

        if ($amazonpayStatusTransfer->getIsSuspended()) {
            return AmazonpayConstants::OMS_STATUS_AUTH_SUSPENDED;
        }

        if ($amazonpayStatusTransfer->getIsTransactionTimedOut()) {
            return AmazonpayConstants::OMS_STATUS_AUTH_TRANSACTION_TIMED_OUT;
        }

        if ($amazonpayStatusTransfer->getIsOpen()) {
            return AmazonpayConstants::OMS_STATUS_AUTH_OPEN;
        }

        return AmazonpayConstants::OMS_STATUS_CLOSED;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $amazonpayStatusTransfer
     *
     * @return string
     */
    protected function getCaptureStatusFromTransfer(AmazonpayStatusTransfer $amazonpayStatusTransfer)
    {
        if ($amazonpayStatusTransfer->getIsPending()) {
            return AmazonpayConstants::OMS_STATUS_CAPTURE_PENDING;
        }

        if ($amazonpayStatusTransfer->getIsDeclined()) {
            return AmazonpayConstants::OMS_STATUS_CAPTURE_DECLINED;
        }

        if ($amazonpayStatusTransfer->getIsCompleted()) {
            return AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED;
        }

        if ($amazonpayStatusTransfer->getIsClosed()) {
            return AmazonpayConstants::OMS_STATUS_CAPTURE_CLOSED;
        }

        return AmazonpayConstants::OMS_STATUS_CAPTURE_CLOSED;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $amazonpayStatusTransfer
     *
     * @return string
     */
    protected function getRefundStatusFromTransfer(AmazonpayStatusTransfer $amazonpayStatusTransfer)
    {
        if ($amazonpayStatusTransfer->getIsPending()) {
            return AmazonpayConstants::OMS_STATUS_REFUND_PENDING;
        }

        if ($amazonpayStatusTransfer->getIsDeclined()) {
            return AmazonpayConstants::OMS_STATUS_REFUND_DECLINED;
        }

        if ($amazonpayStatusTransfer->getIsCompleted()) {
            return AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED;
        }

        return AmazonpayConstants::OMS_STATUS_CAPTURE_DECLINED;
    }

}
