<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Authorize;

use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class IpnPaymentAuthorizeClosedHandler extends IpnAbstractPaymentAuthorizeHandler
{
    /**
     * @return string
     */
    protected function getOmsStatusName()
    {
        return AmazonPayConfig::OMS_STATUS_AUTH_CLOSED;
    }

    /**
     * @return string
     */
    protected function getOmsEventId()
    {
        return AmazonPayConfig::OMS_EVENT_CAPTURE;
    }
}
