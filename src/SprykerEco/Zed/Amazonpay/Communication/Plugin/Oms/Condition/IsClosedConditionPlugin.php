<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

class IsClosedConditionPlugin extends AbstractByOrderItemConditionPlugin
{
    /**
     * @return string
     */
    public function getConditionalStatus()
    {
        return AmazonpayConfig::OMS_STATUS_CLOSED;
    }
}
