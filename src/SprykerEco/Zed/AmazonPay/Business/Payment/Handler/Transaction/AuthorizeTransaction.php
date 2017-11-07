<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class AuthorizeTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $this->updateAuthorizationReferenceId($amazonPayCallTransfer);

        $amazonPayCallTransfer = parent::execute($amazonPayCallTransfer);

        $this->updatePaymentEntity($amazonPayCallTransfer);

        return $amazonPayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return string
     */
    protected function buildErrorCode(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return AmazonPayConfig::PREFIX_AMAZONPAY_PAYMENT_ERROR .
        $amazonpayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus()
            ->getReasonCode();
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $statusDetails
     *
     * @return string
     */
    protected function getStatus(AmazonpayStatusTransfer $statusDetails)
    {
        if ($statusDetails->getIsOpen()) {
            return AmazonPayConfig::OMS_STATUS_AUTH_OPEN;
        }

        if ($statusDetails->getIsPending()) {
            return AmazonPayConfig::OMS_STATUS_AUTH_PENDING;
        }

        if ($statusDetails->getIsSuspended()) {
            return AmazonPayConfig::OMS_STATUS_AUTH_SUSPENDED;
        }

        return AmazonPayConfig::OMS_STATUS_AUTH_DECLINED;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    protected function updateAuthorizationReferenceId(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $authReferenceId = $this->generateOperationReferenceId($amazonPayCallTransfer);
        $amazonPayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->setAuthorizationReferenceId($authReferenceId);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    protected function updatePaymentEntity(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        if (!$this->isPaymentSuccess($amazonPayCallTransfer)) {
            return;
        }

        $isPartialProcessing = $this->paymentEntity && $this->isPartialProcessing($this->paymentEntity, $amazonPayCallTransfer);

        if ($isPartialProcessing) {
            $this->paymentEntity = $this->paymentProcessor->duplicatePaymentEntity($this->paymentEntity);
        }

        $amazonPayCallTransfer->getAmazonpayPayment()->setAuthorizationDetails(
            $this->apiResponse->getAuthorizationDetails()
        );

        $statusDetails = $amazonPayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus();

        if ($statusDetails->getIsDeclined()) {
            $amazonPayCallTransfer->getAmazonpayPayment()->getResponseHeader()
                ->setIsSuccess(false)
                ->setErrorCode($this->buildErrorCode($amazonPayCallTransfer));
        }

        if ($this->paymentEntity) {
            $this->paymentEntity->setStatus($this->getStatus($statusDetails));
            $this->paymentEntity->save();
        }

        if ($isPartialProcessing) {
            $this->paymentProcessor->assignAmazonpayPaymentToItems(
                $this->paymentEntity,
                $amazonPayCallTransfer
            );
        }
    }
}
