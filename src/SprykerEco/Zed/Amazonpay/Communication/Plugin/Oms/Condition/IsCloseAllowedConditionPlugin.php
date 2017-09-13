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
     * @return array
     */
    protected function getAllowedStatuses()
    {
        return [
            AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED,
            AmazonpayConstants::OMS_STATUS_CANCELLED,
        ];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        foreach ($orderItem->getOrder()->getItems() as $salesOrderItem) {
            if (!in_array($salesOrderItem->getState()->getName(), $this->getAllowedStatuses(), true)) {
                return false;
            }
        }

        return true;
    }

}
