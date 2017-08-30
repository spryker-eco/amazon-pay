<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;

class RefundOrderCommandPlugin extends AbstractAmazonpayCommandPlugin
{

    protected $salesOrderItemsMap = [];

    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $amazonpayCallTransfers = $this->groupSalesOrderItemsByPayment($salesOrderItems);

        foreach ($amazonpayCallTransfers as $amazonpayCallTransfer) {
            $currentGroupSalesOrderItems = $this->getSalesOrderItemsForGroup($amazonpayCallTransfer, $salesOrderItems);

            $refundTransfer = $this->getFactory()
                ->getRefundFacade()
                ->calculateRefund($currentGroupSalesOrderItems, $orderEntity);

            $amazonpayCallTransfer->setRequestedAmount(
                $refundTransfer->getAmount()
            );

            $orderTransfer = $this->getFacade()->refundOrder($amazonpayCallTransfer);

            if ($orderTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
                $this->getFactory()
                    ->getRefundFacade()
                    ->saveRefund($refundTransfer);
            }
        }

        return [];
    }

    /**
     * @param AmazonpayCallTransfer $amazonpayCallTransfer
     * @param SpySalesOrderItem[] $salesOrderItems
     *
     * @return SpySalesOrderItem[]
     */
    protected function getSalesOrderItemsForGroup(AmazonpayCallTransfer $amazonpayCallTransfer, array $salesOrderItems)
    {
        if (empty($this->salesOrderItemsMap)) {
            $this->buildSalesOrderItemsMap($salesOrderItems);
        }

        $result = [];

        foreach ($amazonpayCallTransfer->getItems() as $itemTransfer) {
            $result[] = $this->salesOrderItemsMap[$itemTransfer->getIdSalesOrderItem()];
        }

        return $result;
    }

    /**
     * @param SpySalesOrderItem[] $salesOrderItems
     */
    protected function buildSalesOrderItemsMap(array $salesOrderItems)
    {
        foreach ($salesOrderItems as $salesOrderItem) {
            $this->salesOrderItemsMap[$salesOrderItem->getIdSalesOrderItem()] = $salesOrderItem;
        }
    }

}
