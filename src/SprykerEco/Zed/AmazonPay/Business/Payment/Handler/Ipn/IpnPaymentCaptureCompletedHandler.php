<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class IpnPaymentCaptureCompletedHandler extends IpnAbstractPaymentCaptureHandler
{
    /**
     * @return string
     */
    protected function getOmsStatusName()
    {
        return AmazonPayConfig::OMS_STATUS_CAPTURE_COMPLETED;
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

        if (!$paymentEntity) {
            $paymentEntity = $this->amazonPayQueryContainer->queryPaymentByAuthorizationReferenceId(
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
}
