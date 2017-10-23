<?php

namespace SprykerEco\Zed\Amazonpay\Business\Payment;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface;

class RequestAmountCalculator implements RequestAmountCalculatorInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface
     */
    protected $omsFacade;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface $omsFacade
     */
    public function __construct(AmazonpayToOmsInterface $omsFacade)
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
