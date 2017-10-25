<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

abstract class IpnAbstractPaymentRefundHandler extends IpnAbstractTransferRequestHandler
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\AmazonpayResponseTransfer $amazonpayIpnPaymentRefundRequestTransfer
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function retrievePaymentEntity(AbstractTransfer $amazonpayIpnPaymentRefundRequestTransfer)
    {
        return $this->amazonpayQueryContainer->queryPaymentByRefundReferenceId(
            $amazonpayIpnPaymentRefundRequestTransfer->getRefundDetails()->getRefundReferenceId()
        )
            ->findOne();
    }

    /**
     * @return string
     */
    protected function getOmsEventId()
    {
        return AmazonpayConfig::OMS_EVENT_UPDATE_REFUND_STATUS;
    }
}
