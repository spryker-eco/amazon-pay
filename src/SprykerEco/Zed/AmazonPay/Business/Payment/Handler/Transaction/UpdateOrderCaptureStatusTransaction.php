<?php


/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

class UpdateOrderCaptureStatusTransaction extends AbstractAmazonpayTransaction
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

        $this->updatePayment();

        return $amazonPayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return bool
     */
    protected function isAllowed(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        return $amazonPayCallTransfer->getAmazonpayPayment()->getCaptureDetails() !== null
            && $amazonPayCallTransfer->getAmazonpayPayment()->getCaptureDetails()->getAmazonCaptureId() !== null;
    }

    /**
     * @return void
     */
    protected function updatePayment()
    {
        if (!$this->apiResponse->getResponseHeader()->getIsSuccess()) {
            return;
        }

        if ($this->apiResponse->getCaptureDetails()->getAmazonCaptureId()) {
            $this->paymentEntity->setAmazonCaptureId(
                $this->apiResponse->getCaptureDetails()->getAmazonCaptureId()
            );
        }

        $newStatus = $this->apiResponse->getCaptureDetails()->getCaptureStatus()->getState();
        $this->paymentEntity->setStatus($newStatus);
        $this->paymentEntity->save();
    }
}
