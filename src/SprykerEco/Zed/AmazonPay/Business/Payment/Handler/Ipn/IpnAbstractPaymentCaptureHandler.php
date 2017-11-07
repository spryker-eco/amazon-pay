<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

abstract class IpnAbstractPaymentCaptureHandler extends IpnAbstractTransferRequestHandler
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\AmazonpayIpnPaymentCaptureRequestTransfer $amazonpayIpnPaymentAuthorizeRequestTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    protected function retrievePaymentEntity(AbstractTransfer $amazonpayIpnPaymentAuthorizeRequestTransfer)
    {
        return $this->amazonPayQueryContainer
            ->queryPaymentByCaptureReferenceId(
                $amazonpayIpnPaymentAuthorizeRequestTransfer->getCaptureDetails()->getCaptureReferenceId()
            )
            ->findOne();
    }

    /**
     * @return string
     */
    protected function getOmsEventId()
    {
        return AmazonPayConfig::OMS_EVENT_UPDATE_CAPTURE_STATUS;
    }
}
