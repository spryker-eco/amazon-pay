<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;

abstract class AbstractOrderItemConditionPlugin implements ConditionInterface
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
        return $orderItem->getOrder()->getSpyPaymentAmazonpays()->getFirst()->getStatus()
            === $this->getConditionalStatus();
    }

}
