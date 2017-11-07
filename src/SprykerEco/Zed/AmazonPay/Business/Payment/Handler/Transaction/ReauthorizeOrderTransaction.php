<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class ReauthorizeOrderTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $amazonPayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->setAuthorizationReferenceId(
                $this->generateOperationReferenceId($amazonPayCallTransfer)
            );

        $amazonPayCallTransfer = parent::execute($amazonPayCallTransfer);

        if (!$this->apiResponse->getHeader()->getIsSuccess()) {
            return $amazonPayCallTransfer;
        }

        $isPartialProcessing = $this->isPartialProcessing($this->paymentEntity, $amazonPayCallTransfer);

        if ($isPartialProcessing && $this->paymentEntity->getStatus() !== AmazonPayConfig::OMS_STATUS_AUTH_PENDING) {
            $this->paymentEntity = $this->paymentProcessor->duplicatePaymentEntity($this->paymentEntity);
        }

        $amazonPayCallTransfer->getAmazonpayPayment()->setAuthorizationDetails(
            $this->apiResponse->getAuthorizationDetails()
        );

        $this->paymentEntity->setAmazonAuthorizationId(
            $this->apiResponse->getAuthorizationDetails()->getAmazonAuthorizationId()
        );

        $this->paymentEntity->setAuthorizationReferenceId(
            $this->apiResponse->getAuthorizationDetails()->getAuthorizationReferenceId()
        );

        $this->paymentEntity->setStatus(AmazonPayConfig::OMS_STATUS_AUTH_PENDING);
        $this->paymentEntity->save();

        if ($isPartialProcessing) {
            $this->paymentProcessor->assignAmazonpayPaymentToItems(
                $this->paymentEntity,
                $amazonPayCallTransfer
            );
        }

        return $amazonPayCallTransfer;
    }
}
