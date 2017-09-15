<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class IpnOrderReferenceCancelledHandler extends IpnAbstractOrderReferenceHandler
{

    /**
     * @return string
     */
    protected function getOmsEventId()
    {
        return AmazonpayConstants::OMS_EVENT_UPDATE_AUTH_STATUS;
    }

    /**
     * @return string
     */
    protected function getOmsStatusName()
    {
        return AmazonpayConstants::OMS_STATUS_CANCELLED;
    }

}
