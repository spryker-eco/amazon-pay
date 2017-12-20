<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment;

use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface RequestAmountCalculatorInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param string $itemsFlag
     *
     * @return bool
     */
    public function shouldChargeShipping(SpySalesOrder $orderEntity, $itemsFlag);
}
