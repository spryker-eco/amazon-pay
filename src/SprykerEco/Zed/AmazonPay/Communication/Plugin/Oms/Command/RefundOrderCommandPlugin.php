<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;

class RefundOrderCommandPlugin extends AbstractAmazonpayCommandPlugin
{
    /**
     * @var array
     */
    protected $salesOrderItemsMap = [];

    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $amazonpayCallTransfers = $this->groupSalesOrderItemsByCaptureId($salesOrderItems);
        $this->buildSalesOrderItemsMap($salesOrderItems);

        foreach ($amazonpayCallTransfers as $amazonpayCallTransfer) {
            $this->processOrder($amazonpayCallTransfer, $orderEntity);
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function processOrder(AmazonpayCallTransfer $amazonpayCallTransfer, SpySalesOrder $orderEntity)
    {
        $currentGroupSalesOrderItems = $this->getSalesOrderItemsForGroup($amazonpayCallTransfer);

        $refundTransfer = $this->getFactory()
            ->getRefundFacade()
            ->calculateRefund($currentGroupSalesOrderItems, $orderEntity);

        $amazonpayCallTransfer->setRequestedAmount(
            $refundTransfer->getAmount()
        );

        $this->getFacade()->refundOrder($amazonpayCallTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem[]
     */
    protected function getSalesOrderItemsForGroup(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = [];

        foreach ($amazonpayCallTransfer->getItems() as $itemTransfer) {
            $result[] = $this->salesOrderItemsMap[$itemTransfer->getIdSalesOrderItem()];
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem[] $salesOrderItems
     *
     * @return void
     */
    protected function buildSalesOrderItemsMap(array $salesOrderItems)
    {
        foreach ($salesOrderItems as $salesOrderItem) {
            $this->salesOrderItemsMap[$salesOrderItem->getIdSalesOrderItem()] = $salesOrderItem;
        }
    }
}
