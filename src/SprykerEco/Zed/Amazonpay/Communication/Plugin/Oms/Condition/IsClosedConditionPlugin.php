<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

class IsClosedConditionPlugin implements ConditionInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        if (
            in_array($orderItem->getState()->getName(), [
                AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED,
                AmazonpayConstants::OMS_STATUS_CAPTURE_DECLINED,
            ], true)
        ) {
            return true;
        }

        return false;
    }

}
