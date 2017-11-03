<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Order;

use Generated\Shared\Transfer\AmazonpayOrderInfoTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;

class AmazonpayOrderInfoHydrator implements AmazonpayOrderInfoHydratorInterface
{
    /**
     * @var \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $queryContainer
     */
    public function __construct(AmazonpayQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderInfo(OrderTransfer $orderTransfer)
    {
        $orderTransfer->setAmazonpayOrderInfo($this->getAmazonpayOrderInfo($orderTransfer));
        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayOrderInfoTransfer
     */
    protected function getAmazonpayOrderInfo(OrderTransfer $orderTransfer)
    {
        $payment = $this->queryContainer->queryPaymentBySalesOrderItemId($orderTransfer->getItems()[0]->getIdSalesOrderItem())
            ->findOne();
        $amazonpayOrderInfo = new AmazonpayOrderInfoTransfer();
        $amazonpayOrderInfo->fromArray($payment->toArray(), true);
        return $amazonpayOrderInfo;
    }
}
