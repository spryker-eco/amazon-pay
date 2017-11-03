<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Converter;

use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class AmazonPayEntityToTransferConverter implements AmazonPayEntityToTransferConverterInterface
{
    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $entity
     *
     * @return \Generated\Shared\Transfer\AmazonpayPaymentTransfer
     */
    public function mapEntityToTransfer(SpyPaymentAmazonpay $entity)
    {
        $responseHeader = new AmazonpayResponseHeaderTransfer();
        $responseHeader->setIsSuccess(true);

        $paymentTransfer = new AmazonpayPaymentTransfer();
        $paymentTransfer->fromArray($entity->toArray(), true);
        $paymentTransfer->setResponseHeader($responseHeader);
        $paymentTransfer->setOrderReferenceStatus(new AmazonpayStatusTransfer());
        $paymentTransfer->setAuthorizationDetails($this->getAuthorizationDetailsTransfer($entity));
        $paymentTransfer->setCaptureDetails($this->getCaptureDetailsTransfer($entity));
        $paymentTransfer->setRefundDetails($this->getAmazonpayRefundDetailsTransfer($entity));

        return $paymentTransfer;
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $entity
     *
     * @return \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer
     */
    protected function getAuthorizationDetailsTransfer(SpyPaymentAmazonpay $entity)
    {
        $authDetailsTransfer = new AmazonpayAuthorizationDetailsTransfer();
        $authDetailsTransfer->fromArray($entity->toArray(), true);
        $authDetailsTransfer->setAuthorizationStatus(
            $this->getAuthStatusTransfer($entity->getStatus())
        );

        return $authDetailsTransfer;
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $entity
     *
     * @return \Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer
     */
    protected function getAmazonpayRefundDetailsTransfer(SpyPaymentAmazonpay $entity)
    {
        $refundDetailsTransfer = new AmazonpayRefundDetailsTransfer();
        $refundDetailsTransfer->fromArray($entity->toArray(), true);
        $refundDetailsTransfer->setRefundStatus(
            $this->getRefundStatusTransfer($entity->getStatus())
        );

        return $refundDetailsTransfer;
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $entity
     *
     * @return \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer
     */
    protected function getCaptureDetailsTransfer(SpyPaymentAmazonpay $entity)
    {
        $captureDetailsTransfer = new AmazonpayCaptureDetailsTransfer();
        $captureDetailsTransfer->fromArray($entity->toArray(), true);
        $captureDetailsTransfer->setCaptureStatus(
            $this->getCaptureStatusTransfer($entity->getStatus())
        );

        return $captureDetailsTransfer;
    }

    /**
     * @param string $statusName
     *
     * @return \Generated\Shared\Transfer\AmazonpayStatusTransfer
     */
    protected function getAuthStatusTransfer($statusName)
    {
        $amazonpayStatusTransfer = new AmazonpayStatusTransfer();

        $amazonpayStatusTransfer->setIsPending(
            $statusName === AmazonPayConfig::OMS_STATUS_AUTH_PENDING
        );

        $amazonpayStatusTransfer->setIsDeclined(
            $statusName === AmazonPayConfig::OMS_STATUS_AUTH_DECLINED ||
            $statusName === AmazonPayConfig::OMS_STATUS_AUTH_SUSPENDED
        );

        $amazonpayStatusTransfer->setIsSuspended(
            $statusName === AmazonPayConfig::OMS_STATUS_AUTH_SUSPENDED
        );

        $amazonpayStatusTransfer->setIsTransactionTimedOut(
            $statusName === AmazonPayConfig::OMS_STATUS_AUTH_TRANSACTION_TIMED_OUT
        );

        $amazonpayStatusTransfer->setIsOpen(
            $statusName === AmazonPayConfig::OMS_STATUS_AUTH_OPEN
        );

        $amazonpayStatusTransfer->setIsClosed(
            $statusName === AmazonPayConfig::OMS_STATUS_AUTH_CLOSED
        );

        return $amazonpayStatusTransfer;
    }

    /**
     * @param string $statusName
     *
     * @return \Generated\Shared\Transfer\AmazonpayStatusTransfer
     */
    protected function getCaptureStatusTransfer($statusName)
    {
        $amazonpayStatusTransfer = new AmazonpayStatusTransfer();

        $amazonpayStatusTransfer->setIsPending(
            $statusName === AmazonPayConfig::OMS_STATUS_CAPTURE_PENDING
        );

        $amazonpayStatusTransfer->setIsDeclined(
            $statusName === AmazonPayConfig::OMS_STATUS_CAPTURE_DECLINED
        );

        $amazonpayStatusTransfer->setIsCompleted(
            $statusName === AmazonPayConfig::OMS_STATUS_CAPTURE_COMPLETED
        );

        $amazonpayStatusTransfer->setIsClosed(
            $statusName === AmazonPayConfig::OMS_STATUS_CAPTURE_CLOSED
        );

        return $amazonpayStatusTransfer;
    }

    /**
     * @param string $statusName
     *
     * @return \Generated\Shared\Transfer\AmazonpayStatusTransfer
     */
    protected function getRefundStatusTransfer($statusName)
    {
        $amazonpayStatusTransfer = new AmazonpayStatusTransfer();

        $amazonpayStatusTransfer->setIsPending(
            $statusName === AmazonPayConfig::OMS_STATUS_REFUND_PENDING
        );

        $amazonpayStatusTransfer->setIsDeclined(
            $statusName === AmazonPayConfig::OMS_STATUS_REFUND_DECLINED
        );

        $amazonpayStatusTransfer->setIsCompleted(
            $statusName === AmazonPayConfig::OMS_STATUS_CAPTURE_COMPLETED
        );

        return $amazonpayStatusTransfer;
    }
}
