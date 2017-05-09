<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use Spryker\Shared\Amazonpay\AmazonpayConstants;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

abstract class IpnAbstractPaymentCaptureHandler extends IpnAbstractTransferRequestHandler
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $amazonpayIpnPaymentAuthorizeRequestTransfer
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function retrievePaymentEntity(AbstractTransfer $amazonpayIpnPaymentAuthorizeRequestTransfer)
    {
        return $this->amazonpayQueryContainer->queryPaymentByCaptureReferenceId(
            $amazonpayIpnPaymentAuthorizeRequestTransfer->getCaptureDetails()->getCaptureReferenceId()
        )
            ->findOne();
    }

    /**
     * @return string
     */
    protected function getOmsEventId()
    {
        return AmazonpayConstants::OMS_EVENT_UPDATE_CAPTURE_STATUS;
    }

}
