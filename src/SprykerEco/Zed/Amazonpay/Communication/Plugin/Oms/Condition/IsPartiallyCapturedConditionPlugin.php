<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class IsPartiallyCapturedConditionPlugin extends AbstractOrderItemConditionPlugin
{

    /**
     * @return string
     */
    protected function getConditionalStatus()
    {
        return AmazonpayConstants::OMS_STATUS_AUTH_OPEN;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        if (!parent::check($orderItem)) {
            return false;
        }

        $amazonPayment = $this->getSalesOrderItemPayment($orderItem);

        if ($amazonPayment === null) {
            return false;
        }

        return $amazonPayment->getAuthorizationReferenceId() === null;
    }

}
