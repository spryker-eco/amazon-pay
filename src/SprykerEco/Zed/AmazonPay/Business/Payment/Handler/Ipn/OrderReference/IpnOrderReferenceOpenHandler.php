<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\OrderReference;

use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class IpnOrderReferenceOpenHandler extends IpnAbstractOrderReferenceHandler
{
    /**
     * @return string
     */
    protected function getOmsEventId()
    {
        return AmazonPayConfig::OMS_EVENT_UPDATE_SUSPENDED_ORDER;
    }

    /**
     * @return string
     */
    protected function getOmsStatusName()
    {
        return AmazonPayConfig::OMS_STATUS_PAYMENT_METHOD_CHANGED;
    }
}
