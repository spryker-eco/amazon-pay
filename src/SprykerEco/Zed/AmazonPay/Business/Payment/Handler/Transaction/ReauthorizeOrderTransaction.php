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
        $this->updateAuthorizeReferenceId($amazonPayCallTransfer);

        $amazonPayCallTransfer = parent::execute($amazonPayCallTransfer);

        $this->updatePaymentEntity($amazonPayCallTransfer);

        return $amazonPayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    protected function updateAuthorizeReferenceId(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $amazonPayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->setAuthorizationReferenceId(
                $this->generateOperationReferenceId($amazonPayCallTransfer)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    protected function updatePaymentEntity(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        if (!$this->apiResponse->getResponseHeader()->getIsSuccess()) {
            return;
        }

        $isPartialProcessing = $this->isPartialProcessing($this->paymentEntity, $amazonPayCallTransfer);

        if ($isPartialProcessing && $this->paymentEntity->getStatus() !== AmazonPayConfig::STATUS_PENDING) {
            $this->paymentEntity = $this->paymentProcessor->duplicatePaymentEntity($this->paymentEntity);
        }

        $this->paymentEntity->setAmazonAuthorizationId(
            $this->apiResponse->getAuthorizationDetails()->getAmazonAuthorizationId()
        );

        $this->paymentEntity->setAuthorizationReferenceId(
            $this->apiResponse->getAuthorizationDetails()->getAuthorizationReferenceId()
        );

        $this->paymentEntity->setStatus(AmazonPayConfig::STATUS_PENDING);
        $this->paymentEntity->save();

        if ($isPartialProcessing) {
            $this->paymentProcessor->assignAmazonpayPaymentToItems(
                $this->paymentEntity,
                $amazonPayCallTransfer
            );
        }
    }
}
