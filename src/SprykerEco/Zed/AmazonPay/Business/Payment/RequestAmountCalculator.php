<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface;

class RequestAmountCalculator implements RequestAmountCalculatorInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface
     */
    protected $omsFacade;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface $omsFacade
     */
    public function __construct(AmazonPayToOmsInterface $omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param string $itemsFlag
     *
     * @return bool
     */
    public function shouldChargeShipping(SpySalesOrder $orderEntity, $itemsFlag)
    {
        return $this->omsFacade->isOrderFlaggedAll($orderEntity->getIdSalesOrder(), $itemsFlag);
    }
}
