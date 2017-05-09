<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;

class IsClosedConditionPlugin implements ConditionInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        foreach ($orderItem->getOrder()->getItems() as $nextOrderItem) {
            if ($nextOrderItem->getOrder()->getSpyPaymentAmazonpays()->getFirst()->getStatus()
                    !== AmazonpayConstants::OMS_STATUS_CLOSED) {
                return false;
            }
        }

        return true;
    }

}
