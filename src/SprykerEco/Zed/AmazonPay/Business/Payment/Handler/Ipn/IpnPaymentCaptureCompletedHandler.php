<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $amazonpayIpnRequestTransfer
     *
     * @return void
     */
    public function handle(AbstractTransfer $amazonpayIpnRequestTransfer)
    {
        $this->updatePaymentEntityByCaptureReferenceId($amazonpayIpnRequestTransfer);

        parent::handle($amazonpayIpnRequestTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\AmazonpayIpnPaymentCaptureRequestTransfer $amazonpayIpnRequestTransfer
     *
     * @return void
     */
    protected function updatePaymentEntityByCaptureReferenceId(AbstractTransfer $amazonpayIpnRequestTransfer)
    {
        $paymentEntity = $this->retrievePaymentEntity($amazonpayIpnRequestTransfer);

        if (!$paymentEntity) {
            $paymentEntity = $this->amazonPayQueryContainer->queryPaymentByAuthorizationReferenceId(
                $amazonpayIpnRequestTransfer->getCaptureDetails()->getCaptureReferenceId()
            )
                ->findOne();

            if ($paymentEntity) {
                $paymentEntity->setAmazonCaptureId($amazonpayIpnRequestTransfer->getCaptureDetails()->getAmazonCaptureId())
                    ->setCaptureReferenceId($amazonpayIpnRequestTransfer->getCaptureDetails()->getCaptureReferenceId())
                    ->save();
            }
        }
    }
}
