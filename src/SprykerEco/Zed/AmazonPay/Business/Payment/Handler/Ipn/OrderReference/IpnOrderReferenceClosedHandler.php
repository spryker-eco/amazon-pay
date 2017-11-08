<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\OrderReference;

use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class IpnOrderReferenceClosedHandler extends IpnAbstractOrderReferenceHandler
{
    /**
     * @return string
     */
    protected function getOmsEventId()
    {
        return AmazonPayConfig::OMS_EVENT_UPDATE_AUTH_STATUS;
    }

    /**
     * @return string
     */
    protected function getStatusName()
    {
        return AmazonPayConfig::STATUS_DECLINED;
    }
}
