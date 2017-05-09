<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use Spryker\Shared\Amazonpay\AmazonpayConstants;

class IsRefundPendingConditionPlugin extends AbstractOrderItemConditionPlugin
{

    /**
     * @return string
     */
    protected function getConditionalStatus()
    {
        return AmazonpayConstants::OMS_STATUS_REFUND_PENDING;
    }

}
