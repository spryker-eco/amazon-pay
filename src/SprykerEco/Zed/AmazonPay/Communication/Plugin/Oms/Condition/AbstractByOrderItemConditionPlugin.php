<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

abstract class AbstractByOrderItemConditionPlugin implements ConditionInterface
{
    /**
     * @return string|array
     */
    abstract protected function getPaymentStatus();

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

        return $this->isMatchingStatus($payment->getStatus());
    }

    /**
     * @param string $status
     *
     * @return bool
     */
    protected function isMatchingStatus($status)
    {
        $expectedStatus = $this->getPaymentStatus();

        if (is_array($expectedStatus)) {
            return in_array($status, $expectedStatus, true);
        }

        return $status === $expectedStatus;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay|null
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
