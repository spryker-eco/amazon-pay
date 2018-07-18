<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class CaptureOrderTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        if (!$this->isAllowed($amazonPayCallTransfer)) {
            return $amazonPayCallTransfer;
        }

        $this->generateCaptureReferenceId($amazonPayCallTransfer);

        $amazonPayCallTransfer = parent::execute($amazonPayCallTransfer);

        $this->updatePaymentEntity($amazonPayCallTransfer);

        return $amazonPayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return bool
     */
    protected function isAllowed(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        if (!in_array($amazonPayCallTransfer->getAmazonpayPayment()->getStatus(), [
            AmazonPayConfig::STATUS_PENDING,
            AmazonPayConfig::STATUS_OPEN,
            AmazonPayConfig::STATUS_PAYMENT_METHOD_CHANGED,
        ], true)) {
            return false;
        }

        if ($amazonPayCallTransfer->getAmazonpayPayment()->getCaptureDetails()
            && $amazonPayCallTransfer->getAmazonpayPayment()->getCaptureDetails()->getAmazonCaptureId()) {
            return false;
        }

        return true;
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

        $isPartialProcessing = $this->isPartialProcessing($this->paymentEntity, $amazonPayCallTransfer);

        if ($isPartialProcessing) {
            $this->paymentEntity = $this->paymentProcessor->duplicatePaymentEntity($this->paymentEntity);
        }

        $captureDetails = $this->apiResponse->getCaptureDetails();

        $amazonPayCallTransfer->getAmazonpayPayment()->setCaptureDetails($captureDetails);
        $this->paymentEntity->setAmazonCaptureId(
            $captureDetails->getAmazonCaptureId()
        );
        $this->paymentEntity->setCaptureReferenceId(
            $captureDetails->getCaptureReferenceId()
        );

        $this->paymentEntity->setStatus($captureDetails->getCaptureStatus()->getState());

        $this->paymentEntity->save();

        if ($isPartialProcessing) {
            $this->paymentProcessor->assignAmazonpayPaymentToItems(
                $this->paymentEntity,
                $amazonPayCallTransfer
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    protected function generateCaptureReferenceId(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        $amazonPayCallTransfer->getAmazonpayPayment()
            ->getCaptureDetails()
            ->setCaptureReferenceId(
                $this->generateOperationReferenceId($amazonPayCallTransfer)
            );
    }
}
