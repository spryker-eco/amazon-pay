<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter\Ipn;

use Generated\Shared\Transfer\AmazonpayIpnOrderReferenceNotificationTransfer;

class IpnOrderReferenceNotificationConverter extends IpnPaymentAbstractRequestConverter
{

    /**
     * @param array $request
     *
     * @return \Generated\Shared\Transfer\AmazonpayIpnOrderReferenceNotificationTransfer
     */
    public function convert(array $request)
    {
        $ipnOrderReferenceNotificationTransfer = new AmazonpayIpnOrderReferenceNotificationTransfer();
        $ipnOrderReferenceNotificationTransfer->setMessage($this->extractMessage($request));

        $ipnOrderReferenceNotificationTransfer->setOrderReferenceStatus($this->convertStatusToTransfer($request['OrderReference']['OrderReferenceStatus']));
        $ipnOrderReferenceNotificationTransfer->setAmazonOrderReferenceId($request['OrderReference']['AmazonOrderReferenceId']);

        return $ipnOrderReferenceNotificationTransfer;
    }

}
