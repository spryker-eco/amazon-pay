<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class IsAuthSuspendedConditionPlugin extends AbstractOrderItemConditionPlugin
{

    /**
     * @return string
     */
    protected function getConditionalStatus()
    {
        return AmazonpayConstants::OMS_STATUS_AUTH_SUSPENDED;
    }

}
