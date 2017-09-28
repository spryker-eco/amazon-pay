<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AuthorizeTransaction extends AbstractAmazonpayTransaction
{

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $authReferenceId = $this->generateOperationReferenceId($amazonpayCallTransfer);
        $amazonpayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->setAuthorizationReferenceId($authReferenceId);

        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        if (!$amazonpayCallTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            return $amazonpayCallTransfer;
        }

        $isPartialProcessing = $this->paymentEntity && $this->isPartialProcessing($this->paymentEntity, $amazonpayCallTransfer);

        if ($isPartialProcessing) {
            $this->paymentEntity = $this->duplicatePaymentEntity($this->paymentEntity);
        }

        $amazonpayCallTransfer->getAmazonpayPayment()->setAuthorizationDetails(
            $this->apiResponse->getAuthorizationDetails()
        );

        $statusDetails = $amazonpayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus();
        if ($statusDetails->getIsDeclined()) {
            $amazonpayCallTransfer->getAmazonpayPayment()->getResponseHeader()
                ->setIsSuccess(false)
                ->setErrorCode($this->buildErrorCode($amazonpayCallTransfer));
        }

        if ($this->paymentEntity) {
            $this->paymentEntity->setStatus($this->getStatus($statusDetails));
            $this->paymentEntity->save();
        }

        if ($isPartialProcessing) {
            $this->assignAmazonpayPaymentToItemsIfNew($this->paymentEntity, $amazonpayCallTransfer);
        }

        return $amazonpayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return string
     */
    protected function buildErrorCode(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return AmazonpayConstants::PREFIX_AMAZONPAY_PAYMENT_ERROR .
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
            return AmazonpayConstants::OMS_STATUS_AUTH_OPEN;
        }

        if ($statusDetails->getIsPending()) {
            return AmazonpayConstants::OMS_STATUS_AUTH_PENDING;
        }

        if ($statusDetails->getIsSuspended()) {
            return AmazonpayConstants::OMS_STATUS_AUTH_SUSPENDED;
        }

        return AmazonpayConstants::OMS_STATUS_AUTH_DECLINED;
    }

}
