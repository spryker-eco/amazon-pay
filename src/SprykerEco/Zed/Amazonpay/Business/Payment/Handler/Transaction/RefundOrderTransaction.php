<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class RefundOrderTransaction extends AbstractAmazonpayTransaction
{

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        if (!$amazonpayCallTransfer->getAmazonpayPayment()
                ->getCaptureDetails()
                ->getAmazonCaptureId()
        ) {
            return $amazonpayCallTransfer;
        }

        $amazonpayCallTransfer->getAmazonpayPayment()->getRefundDetails()->setRefundReferenceId(
            $this->generateOperationReferenceId($amazonpayCallTransfer)
        );

        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        if (!$this->apiResponse->getHeader()->getIsSuccess()) {
            return $amazonpayCallTransfer;
        }

        $isPartialProcessing = $this->isPartialProcessing($this->paymentEntity, $amazonpayCallTransfer);

        if ($isPartialProcessing) {
            $this->paymentEntity = $this->duplicatePaymentEntity($this->paymentEntity);
        }

        $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_REFUND_PENDING);
        $this->paymentEntity->setAmazonRefundId($this->apiResponse->getRefundDetails()->getAmazonRefundId());
        $this->paymentEntity->setRefundReferenceId($this->apiResponse->getRefundDetails()->getRefundReferenceId());
        $this->paymentEntity->save();

        if ($isPartialProcessing) {
            $this->assignAmazonpayPaymentToItemsIfNew($this->paymentEntity, $amazonpayCallTransfer);
        }

        return $amazonpayCallTransfer;
    }

}
