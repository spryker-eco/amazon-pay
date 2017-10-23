<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

class IpnPaymentCaptureDeclineHandler extends IpnAbstractPaymentCaptureHandler
{
    /**
     * @return string
     */
    protected function getOmsStatusName()
    {
        return AmazonpayConfig::OMS_STATUS_CAPTURE_DECLINED;
    }
}
