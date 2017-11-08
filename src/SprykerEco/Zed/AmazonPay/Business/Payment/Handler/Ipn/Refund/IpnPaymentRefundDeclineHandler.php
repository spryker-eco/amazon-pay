<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Refund;

use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class IpnPaymentRefundDeclineHandler extends IpnAbstractPaymentRefundHandler
{
    /**
     * @return string
     */
    protected function getStatusName()
    {
        return AmazonPayConfig::STATUS_DECLINED;
    }
}
