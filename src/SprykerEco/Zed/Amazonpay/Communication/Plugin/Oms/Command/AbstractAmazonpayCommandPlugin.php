<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use ArrayObject;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpaySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \SprykerEco\Zed\Amazonpay\Business\AmazonpayFacade getFacade()
 * @method \SprykerEco\Zed\Amazonpay\Communication\AmazonpayCommunicationFactory getFactory()
 */
abstract class AbstractAmazonpayCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    protected $wasShippingCharged;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getOrderItemTransfers(SpySalesOrder $salesOrderEntity)
    {
        $salesOrderTransfer = $this
            ->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        return $salesOrderTransfer->getItems();
    }
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $orderEntity)
    {
        return $this->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder(
                $orderEntity->getIdSalesOrder()
            );
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer($message)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);

        return $messageTransfer;
    }

    /**
     * @return string
     */
    protected function getAffectedItemsStateFlag()
    {
        return '';
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int
     */
    protected function getRequestedAmountByOrderAndItems(SpySalesOrder $orderEntity, ArrayObject $itemTransfers)
    {
        $subtotal = 0;

        foreach ($itemTransfers as $itemTransfer) {
            $subtotal += $itemTransfer->getUnitPriceToPayAggregation();
        }

        if (!$this->wasShippingCharged
            && $this->getFactory()
                ->createRequestAmountCalculator()
                ->shouldChargeShipping($orderEntity, $this->getAffectedItemsStateFlag())) {
            $subtotal += $this->getShipmentPrice($orderEntity);
            $this->wasShippingCharged = true;
        }

        return $subtotal;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return int
     */
    protected function getShipmentPrice(SpySalesOrder $orderEntity)
    {
        $shipmentPrice = 0;

        foreach ($orderEntity->getExpenses() as $expense) {
            if ($expense->getType() === ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                $shipmentPrice = $expense->getPriceToPayAggregation();

                break;
            }
        }

        return $shipmentPrice;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer[]
     */
    protected function groupSalesOrderItemsByPayment(array $salesOrderItems)
    {
        $groups = [];

        foreach ($salesOrderItems as $salesOrderItem) {
            $payment = $this->getPaymentDetails($salesOrderItem);

            if (!$payment) {
                continue;
            }

            $groupData = $groups[$payment->getAuthorizationReferenceId()] ?? null;

            if (!$groupData) {
                $groupData = new AmazonpayCallTransfer();

                $paymentTransfer = $this->getFacade()->mapAmazonPaymentToTransfer($payment);

                $groupData->setAmazonpayPayment($paymentTransfer);
            }

            $groupData->addItem(
                $this->mapSalesOrderItemToItemTransfer($salesOrderItem)
            );

            $groups[$payment->getAuthorizationReferenceId()] = $groupData;
        }

        return $groups;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    protected function createAmazonpayCallTransfer(array $salesOrderItems)
    {
        $amazonpayCallTransfer = new AmazonpayCallTransfer();
        $amazonPayment = $this->getAmazonpayPaymentTransferBySalesOrder($salesOrderItems[0]);
        $amazonpayCallTransfer->setAmazonpayPayment($amazonPayment);

        return $amazonpayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param array $salesOrderItems
     */
    protected function populateItems(AmazonpayCallTransfer $amazonpayCallTransfer, array $salesOrderItems)
    {
        $amazonpayCallTransfer->setItems(
            new ArrayObject(
                array_map(array($this, 'mapSalesOrderItemToItemTransfer'), $salesOrderItems)
            )
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     *
     * @return \Generated\Shared\Transfer\AmazonpayPaymentTransfer
     */
    protected function getAmazonpayPaymentTransferBySalesOrder(SpySalesOrderItem $salesOrderItem)
    {
        return $this->getFacade()->mapAmazonPaymentToTransfer($this->getPaymentDetails($salesOrderItem));
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     *
     * @return null|\Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function getPaymentDetails(SpySalesOrderItem $salesOrderItem)
    {
        /** @var SpyPaymentAmazonpaySalesOrderItem $payment */
        $payment = $salesOrderItem->getSpyPaymentAmazonpaySalesOrderItems()->getLast();

        if (!$payment) {
            return null;
        }

        return $payment->getSpyPaymentAmazonpay();

    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function mapSalesOrderItemToItemTransfer(SpySalesOrderItem $salesOrderItemEntity)
    {
        $itemTransfer = (new ItemTransfer())
            ->fromArray($salesOrderItemEntity->toArray(), true);

        $itemTransfer->setUnitGrossPrice($salesOrderItemEntity->getGrossPrice());
        $itemTransfer->setUnitNetPrice($salesOrderItemEntity->getNetPrice());

        $itemTransfer->setUnitPrice($salesOrderItemEntity->getPrice());
        $itemTransfer->setUnitPriceToPayAggregation($salesOrderItemEntity->getPriceToPayAggregation());
        $itemTransfer->setUnitSubtotalAggregation($salesOrderItemEntity->getSubtotalAggregation());
        $itemTransfer->setUnitProductOptionPriceAggregation($salesOrderItemEntity->getProductOptionPriceAggregation());
        $itemTransfer->setUnitExpensePriceAggregation($salesOrderItemEntity->getExpensePriceAggregation());
        $itemTransfer->setUnitTaxAmount($salesOrderItemEntity->getTaxAmount());
        $itemTransfer->setUnitTaxAmountFullAggregation($salesOrderItemEntity->getTaxAmountFullAggregation());
        $itemTransfer->setUnitDiscountAmountAggregation($salesOrderItemEntity->getDiscountAmountAggregation());
        $itemTransfer->setUnitDiscountAmountFullAggregation($salesOrderItemEntity->getDiscountAmountFullAggregation());
        $itemTransfer->setRefundableAmount($salesOrderItemEntity->getRefundableAmount());

        return $itemTransfer;
    }

}
