<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

class IpnOrderReferenceClosedHandler extends IpnAbstractOrderReferenceHandler
{
    /**
     * @return string
     */
    protected function getOmsEventId()
    {
        return AmazonpayConfig::OMS_EVENT_UPDATE_AUTH_STATUS;
    }

    /**
     * @return string
     */
    protected function getOmsStatusName()
    {
        return AmazonpayConfig::OMS_STATUS_AUTH_DECLINED;
    }
}
