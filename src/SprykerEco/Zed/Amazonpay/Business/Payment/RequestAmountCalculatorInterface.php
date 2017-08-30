<?php

namespace SprykerEco\Zed\Amazonpay\Business\Payment;

use ArrayObject;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface RequestAmountCalculatorInterface
{


    /**
     * @param SpySalesOrder $orderEntity
     * @param string $itemsFlag
     *
     * @return bool
     */
    public function shouldChargeShipping(SpySalesOrder $orderEntity, $itemsFlag);

}
