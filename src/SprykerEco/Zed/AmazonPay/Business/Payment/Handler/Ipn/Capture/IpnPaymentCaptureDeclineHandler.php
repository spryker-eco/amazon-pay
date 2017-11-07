<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Capture;

use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class IpnPaymentCaptureDeclineHandler extends IpnAbstractPaymentCaptureHandler
{
    /**
     * @return string
     */
    protected function getOmsStatusName()
    {
        return AmazonPayConfig::OMS_STATUS_CAPTURE_DECLINED;
    }
}
