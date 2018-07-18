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

class AmazonPayEntityToTransferConverter implements AmazonPayEntityToTransferConverterInterface
{
    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $entity
     *
     * @return \Generated\Shared\Transfer\AmazonpayPaymentTransfer
     */
    public function mapEntityToTransfer(SpyPaymentAmazonpay $entity)
    {
        $responseHeaderTransfer = new AmazonpayResponseHeaderTransfer();
        $responseHeaderTransfer->setIsSuccess(true);

        $paymentTransfer = new AmazonpayPaymentTransfer();
        $paymentTransfer->fromArray($entity->toArray(), true);
        $paymentTransfer->setResponseHeader($responseHeaderTransfer);
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
            $this->getStatusTransfer($entity->getStatus())
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
            $this->getStatusTransfer($entity->getStatus())
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
            $this->getStatusTransfer($entity->getStatus())
        );

        return $captureDetailsTransfer;
    }

    /**
     * @param string $statusName
     *
     * @return \Generated\Shared\Transfer\AmazonpayStatusTransfer
     */
    protected function getStatusTransfer($statusName)
    {
        $amazonpayStatusTransfer = new AmazonpayStatusTransfer();
        $amazonpayStatusTransfer->setState($statusName);

        return $amazonpayStatusTransfer;
    }
}
