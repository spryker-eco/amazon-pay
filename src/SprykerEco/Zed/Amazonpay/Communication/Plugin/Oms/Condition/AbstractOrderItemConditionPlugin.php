<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

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
        $payment = $this->getPaymentAmazonpayBySalesOrderItem($orderItem);

        if ($payment === null) {
            return true;
        }

        return $payment->getStatus() === $this->getConditionalStatus();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function getPaymentAmazonpayBySalesOrderItem(SpySalesOrderItem $orderItem)
    {
        $lastPayment = $orderItem->getSpyPaymentAmazonpaySalesOrderItems()->getLast();

        if (!$lastPayment) {
            return null;
        }

        return $lastPayment->getSpyPaymentAmazonpay();
    }
}
