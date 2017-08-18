<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

class IsPartiallyCapturedConditionPlugin extends AbstractOrderItemConditionPlugin
{

    /**
     * @return string
     */
    protected function getConditionalStatus()
    {
        return '';
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $amazonPayment = $this->getPaymentAmazonpayBySalesOrderItem($orderItem);

        if ($amazonPayment === null) {
            return true;
        }

        return $amazonPayment->getAuthorizationReferenceId() === null ||
            $amazonPayment->getAmazonAuthorizationId() === null;
    }

}
