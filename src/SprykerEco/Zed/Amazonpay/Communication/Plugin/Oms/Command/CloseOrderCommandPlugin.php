<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use ArrayObject;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;

class CloseOrderCommandPlugin extends AbstractAmazonpayCommandPlugin
{

    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $payment = $this->getPaymentDetails($salesOrderItems[0]);
        $amazonpayCallTransfer = $this->createAmazonpayCallTransfer($payment);

        $this->getFacade()->closeOrder($amazonpayCallTransfer);

        return [];
    }

    /**
     * @param SpySalesOrderItem[] $salesOrderItems
     * @param SpySalesOrder $orderEntity
     *
     * @return array
     */
    protected function loadOrderItemsFromSameTransactions(array $salesOrderItems, SpySalesOrder $orderEntity)
    {
        $affectedTransactions = [];

        foreach ($salesOrderItems as $salesOrderItem) {
            $payment = $this->getPaymentDetails($salesOrderItem);

            if (!$payment) {
                continue;
            }

            $affectedTransactions[$payment->getOrderReferenceId()] = 1;
        }

        $allSalesOrderItems = [];

        foreach ($orderEntity->getItems() as $salesOrderItem) {
            $paymentInfo = $this->getPaymentDetails($salesOrderItem);

            if ($paymentInfo && array_key_exists($paymentInfo->getOrderReferenceId(), $affectedTransactions)) {
                $allSalesOrderItems[] = $this->mapSalesOrderItemToItemTransfer($salesOrderItem);
            }
        }


        return $allSalesOrderItems;
    }

}
