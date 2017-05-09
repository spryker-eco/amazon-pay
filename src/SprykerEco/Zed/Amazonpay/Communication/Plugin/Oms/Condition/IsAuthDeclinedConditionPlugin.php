<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use Spryker\Shared\Amazonpay\AmazonpayConstants;

class IsAuthDeclinedConditionPlugin extends AbstractOrderConditionPlugin
{

    /**
     * @return string
     */
    protected function getConditionalStatus()
    {
        return AmazonpayConstants::OMS_STATUS_AUTH_DECLINED;
    }

}
