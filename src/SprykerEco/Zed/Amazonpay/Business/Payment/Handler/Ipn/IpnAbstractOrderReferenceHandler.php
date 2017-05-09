<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

abstract class IpnAbstractOrderReferenceHandler extends IpnAbstractTransferRequestHandler
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\AmazonpayIpnOrderReferenceNotificationTransfer $amazonpayIpnOrderReferenceOpenTransfer
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function retrievePaymentEntity(AbstractTransfer $amazonpayIpnOrderReferenceOpenTransfer)
    {
        return $this->amazonpayQueryContainer->queryPaymentByOrderReferenceId(
            $amazonpayIpnOrderReferenceOpenTransfer->getAmazonOrderReferenceId()
        )->findOne();
    }

}
