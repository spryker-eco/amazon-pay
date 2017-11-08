<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class UpdateOrderAuthorizationStatusTransaction extends AbstractAmazonpayTransaction
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

        $this->updatePayment($amazonPayCallTransfer);

        return $amazonPayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    protected function updatePayment(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        if (!$this->isPaymentSuccess($amazonPayCallTransfer)) {
            return;
        }

        $amazonPayment = $amazonPayCallTransfer->getAmazonpayPayment();
        $status = $amazonPayment->getAuthorizationDetails()->getAuthorizationStatus();

        if ($amazonPayment->getAuthorizationDetails()->getIdList()) {
            $this->paymentEntity->setAmazonCaptureId(
                $amazonPayment->getAuthorizationDetails()->getIdList()
                )
                ->setStatus($status->getState())
                ->save();

            return;
        }

        if ($status->getState() === AmazonPayConfig::STATUS_PENDING) {
            return;
        }

        $this->paymentEntity->setStatus($status->getState());
        if ($this->apiResponse->getCaptureDetails() &&
            $this->apiResponse->getCaptureDetails()->getAmazonCaptureId()) {
            $this->paymentEntity->setAmazonCaptureId(
                $this->apiResponse->getCaptureDetails()->getAmazonCaptureId()
            );
        }

        $this->paymentEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return bool
     */
    protected function isAllowed(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        return $amazonPayCallTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAmazonAuthorizationId() !== null;
    }
}
