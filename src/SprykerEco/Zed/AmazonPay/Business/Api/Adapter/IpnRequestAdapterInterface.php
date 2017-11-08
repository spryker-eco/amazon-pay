<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;

interface IpnRequestAdapterInterface
{
    /**
     * @return AmazonpayIpnPaymentRequestTransfer
     */
    public function getIpnRequest();
}
