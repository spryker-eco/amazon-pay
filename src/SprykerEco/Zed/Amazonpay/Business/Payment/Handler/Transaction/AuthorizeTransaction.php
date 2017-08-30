<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
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
        $isPartialProcessing = $this->isPartialProcessing($this->paymentEntity, $amazonpayCallTransfer);

        if (!$amazonpayCallTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            return $amazonpayCallTransfer;
        }

        if ($isPartialProcessing) {
            $this->paymentEntity = $this->duplicatePaymentEntity($this->paymentEntity);
        }

        $amazonpayCallTransfer->getAmazonpayPayment()->setAuthorizationDetails(
            $this->apiResponse->getAuthorizationDetails()
        );

        if ($amazonpayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus()
            ->getIsDeclined()) {
            $amazonpayCallTransfer->getAmazonpayPayment()->getResponseHeader()
                ->setIsSuccess(false)
                ->setErrorCode($this->buildErrorCode($amazonpayCallTransfer));

            return $amazonpayCallTransfer;
        }

        $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_PENDING);
        $this->paymentEntity->save();

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
        return 'amazonpay.payment.error.'.
        $amazonpayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus()
            ->getReasonCode();
    }

}
