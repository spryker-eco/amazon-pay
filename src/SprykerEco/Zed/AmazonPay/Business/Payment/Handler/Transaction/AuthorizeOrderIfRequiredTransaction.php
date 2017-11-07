<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class AuthorizeOrderIfRequiredTransaction extends ReauthorizeOrderTransaction
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
        if ($amazonPayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAmazonAuthorizationId()) {
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
            $this->paymentEntity = $this->duplicatePaymentEntity($this->paymentEntity);
        }

        $this->paymentEntity->setStatus(AmazonPayConfig::OMS_STATUS_CAPTURE_PENDING);
        $this->paymentEntity->save();

        if ($isPartialProcessing) {
            $this->assignAmazonpayPaymentToItems($this->paymentEntity, $amazonPayCallTransfer);
        }
    }
}
