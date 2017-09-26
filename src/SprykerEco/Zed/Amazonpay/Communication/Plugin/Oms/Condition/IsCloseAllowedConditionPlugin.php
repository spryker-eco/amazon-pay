<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class IsCloseAllowedConditionPlugin implements ConditionInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        foreach ($orderItem->getOrder()->getItems() as $salesOrderItem) {
            if ($salesOrderItem->getState()->getName() !== AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED) {
                return false;
            }
        }

        return true;
    }

}
