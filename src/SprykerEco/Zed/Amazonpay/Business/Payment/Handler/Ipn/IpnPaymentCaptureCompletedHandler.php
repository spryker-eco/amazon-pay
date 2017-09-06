<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class IpnPaymentCaptureCompletedHandler extends IpnAbstractPaymentCaptureHandler
{

    /**
     * @return string
     */
    protected function getOmsStatusName()
    {
        return AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED;
    }


    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\AmazonpayIpnPaymentCaptureRequestTransfer $amazonpayIpnRequestTransfer
     *
     * @return void
     */
    public function handle(AbstractTransfer $amazonpayIpnRequestTransfer)
    {
        $paymentEntity = $this->retrievePaymentEntity($amazonpayIpnRequestTransfer);

        if (!$paymentEntity) {
            $paymentEntity = $this->amazonpayQueryContainer->queryPaymentByAuthorizationReferenceId(
                $amazonpayIpnRequestTransfer->getCaptureDetails()->getCaptureReferenceId()
            )
                ->findOne();

            if ($paymentEntity) {
                $paymentEntity->setAmazonCaptureId($amazonpayIpnRequestTransfer->getCaptureDetails()->getAmazonCaptureId())
                    ->setCaptureReferenceId($amazonpayIpnRequestTransfer->getCaptureDetails()->getCaptureReferenceId())
                    ->save();
            }
        }


        parent::handle($amazonpayIpnRequestTransfer);
    }

}
