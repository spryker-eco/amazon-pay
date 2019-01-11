<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Capture;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class IpnPaymentCaptureCompletedHandler extends IpnAbstractPaymentCaptureHandler
{
    /**
     * @return string
     */
    protected function getStatusName()
    {
        return AmazonPayConfig::STATUS_COMPLETED;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return void
     */
    public function handle(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer)
    {
        $this->updatePaymentEntityByCaptureReferenceId($paymentRequestTransfer);

        parent::handle($paymentRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return void
     */
    protected function updatePaymentEntityByCaptureReferenceId(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer)
    {
        $paymentEntity = $this->retrievePaymentEntity($paymentRequestTransfer);

        if ($paymentEntity !== null) {
            return;
        }

        $paymentEntity = $this->queryContainer->queryPaymentByAuthorizationReferenceId(
            $paymentRequestTransfer->getCaptureDetails()->getCaptureReferenceId()
        )
            ->findOne();

        if ($paymentEntity) {
            $paymentEntity->setAmazonCaptureId($paymentRequestTransfer->getCaptureDetails()->getAmazonCaptureId())
                ->setCaptureReferenceId($paymentRequestTransfer->getCaptureDetails()->getCaptureReferenceId())
                ->save();
        }
    }
}
