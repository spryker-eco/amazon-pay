<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class RefundOrderTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        if (!$amazonPayCallTransfer->getAmazonpayPayment()
                ->getCaptureDetails()
                ->getAmazonCaptureId()
        ) {
            return $amazonPayCallTransfer;
        }

        $amazonPayCallTransfer->getAmazonpayPayment()->getRefundDetails()->setRefundReferenceId(
            $this->generateOperationReferenceId($amazonPayCallTransfer)
        );

        $amazonPayCallTransfer = parent::execute($amazonPayCallTransfer);

        if (!$this->apiResponse->getHeader()->getIsSuccess()) {
            return $amazonPayCallTransfer;
        }

        $isPartialProcessing = $this->isPartialProcessing($this->paymentEntity, $amazonPayCallTransfer);

        if ($isPartialProcessing) {
            $this->paymentEntity = $this->paymentProcessor->duplicatePaymentEntity($this->paymentEntity);
        }

        $this->paymentEntity->setStatus(AmazonPayConfig::OMS_STATUS_REFUND_PENDING);
        $this->paymentEntity->setAmazonRefundId($this->apiResponse->getRefundDetails()->getAmazonRefundId());
        $this->paymentEntity->setRefundReferenceId($this->apiResponse->getRefundDetails()->getRefundReferenceId());
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
