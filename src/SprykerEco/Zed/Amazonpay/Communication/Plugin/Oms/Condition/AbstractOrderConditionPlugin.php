<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;

abstract class AbstractOrderConditionPlugin implements ConditionInterface
{

    /**
     * @return string
     */
    abstract protected function getConditionalStatus();

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $orderItem->getOrder()->reload(true);
        foreach ($orderItem->getOrder()->getItems() as $nextOrderItem) {
            if ($nextOrderItem->getOrder()->getSpyPaymentAmazonpays()->getFirst()->getStatus()
                    !== $this->getConditionalStatus()) {
                return false;
            }
        }

        return true;
    }

}
