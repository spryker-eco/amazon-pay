<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

class IsClosedConditionPlugin extends AbstractOrderItemConditionPlugin
{

    /**
     * @return bool
     */
    public function getConditionalStatus()
    {
        return AmazonpayConstants::OMS_STATUS_CLOSED;
    }

}
