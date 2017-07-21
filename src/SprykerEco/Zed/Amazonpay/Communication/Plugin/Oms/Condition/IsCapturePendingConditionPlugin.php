<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class IsCapturePendingConditionPlugin extends AbstractOrderItemConditionPlugin
{

    /**
     * @return string
     */
    protected function getConditionalStatus()
    {
        return AmazonpayConstants::OMS_STATUS_CAPTURE_PENDING;
    }

}
