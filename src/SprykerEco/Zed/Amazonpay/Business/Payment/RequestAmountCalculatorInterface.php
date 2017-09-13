<?php

namespace SprykerEco\Zed\Amazonpay\Business\Payment;

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
