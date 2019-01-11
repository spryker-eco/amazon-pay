<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class IsCloseAllowedConditionPlugin implements ConditionInterface
{
    /**
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        foreach ($orderItem->getOrder()->getItems() as $salesOrderItem) {
            if (!$this->isInCloseAllowedState($salesOrderItem->getState()->getName())) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $state
     *
     * @return bool
     */
    protected function isInCloseAllowedState($state)
    {
        return in_array($state, [
            AmazonPayConfig::OMS_STATUS_CAPTURE_COMPLETED,
            AmazonPayConfig::OMS_STATUS_REFUND_COMPLETED,
            AmazonPayConfig::OMS_STATUS_REFUND_PENDING,
            AmazonPayConfig::OMS_STATUS_REFUND_DECLINED,
            AmazonPayConfig::OMS_STATUS_REFUND_WAITING_FOR_STATUS,
        ], true);
    }
}
