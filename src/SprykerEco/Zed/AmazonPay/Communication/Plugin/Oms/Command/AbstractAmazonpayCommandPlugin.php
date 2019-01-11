<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use IteratorAggregate;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \SprykerEco\Zed\AmazonPay\Business\AmazonPayFacadeInterface getFacade()
 * @method \SprykerEco\Zed\AmazonPay\Communication\AmazonPayCommunicationFactory getFactory()
 */
abstract class AbstractAmazonpayCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * @var bool
     */
    protected $wasShippingCharged;

    /**
     * @return string
     */
    protected function getAffectingRequestedAmountItemsStateFlag()
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
        $subtotal = $this->getPriceToPay($itemTransfers);

        if (!$this->wasShippingCharged
            && $this->getFactory()
                ->createRequestAmountCalculator()
                ->shouldChargeShipping($orderEntity, $this->getAffectingRequestedAmountItemsStateFlag())) {
            $subtotal += $this->getShipmentPrice($orderEntity);
            $this->wasShippingCharged = true;
        }

        return $subtotal;
    }

    /**
     * @param \ArrayObject $itemTransfers
     *
     * @return int
     */
    protected function getPriceToPay(ArrayObject $itemTransfers)
    {
        $subtotal = 0;

        foreach ($itemTransfers as $itemTransfer) {
            $subtotal += $itemTransfer->getUnitPriceToPayAggregation();
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
        return $this->getExpenseByType($orderEntity, ShipmentConstants::SHIPMENT_EXPENSE_TYPE)
            ->getPriceToPayAggregation();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param string $type
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense|null
     */
    protected function getExpenseByType(SpySalesOrder $orderEntity, $type)
    {
        foreach ($orderEntity->getExpenses() as $expense) {
            if ($expense->getType() === $type) {
                return $expense;
            }
        }

        return null;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer[]
     */
    protected function groupSalesOrderItemsByAuthId(array $salesOrderItems)
    {
        $groups = [];

        foreach ($salesOrderItems as $salesOrderItem) {
            $payment = $this->getPaymentDetails($salesOrderItem);

            if (!$payment) {
                continue;
            }

            $groupData = $groups[$payment->getAuthorizationReferenceId()] ?? $this->createAmazonpayCallTransfer($payment);

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
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer[]
     */
    protected function groupSalesOrderItemsByCaptureId(array $salesOrderItems)
    {
        $groups = [];

        foreach ($salesOrderItems as $salesOrderItem) {
            $payment = $this->getPaymentDetails($salesOrderItem);

            if (!$payment) {
                continue;
            }

            $groupData = $groups[$payment->getAmazonCaptureId()] ?? $this->createAmazonpayCallTransfer($payment);

            $groupData->addItem(
                $this->mapSalesOrderItemToItemTransfer($salesOrderItem)
            );

            $groups[$payment->getAmazonCaptureId()] = $groupData;
        }

        return $groups;
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $payment
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    protected function createAmazonpayCallTransfer(SpyPaymentAmazonpay $payment)
    {
        $amazonPayment = $this->getFactory()
            ->createPaymentAmazonpayConverter()
            ->mapEntityToTransfer($payment);

        $amazonpayCallTransfer = new AmazonpayCallTransfer();
        $amazonpayCallTransfer->setAmazonpayPayment($amazonPayment);

        return $amazonpayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param array $salesOrderItems
     *
     * @return void
     */
    protected function populateItems(AmazonpayCallTransfer $amazonpayCallTransfer, array $salesOrderItems)
    {
        $amazonpayCallTransfer->setItems(
            new ArrayObject(
                array_map([$this, 'mapSalesOrderItemToItemTransfer'], $salesOrderItems)
            )
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function getPaymentDetails(SpySalesOrderItem $salesOrderItem)
    {
        /** @var \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpaySalesOrderItem|null $payment */
        $payment = $salesOrderItem->getSpyPaymentAmazonpaySalesOrderItems()->getLast();

        if ($payment === null) {
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

    /**
     * @param \IteratorAggregate|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param string $statusName
     *
     * @return void
     */
    protected function setOrderItemsStatus(IteratorAggregate $salesOrderItems, $statusName)
    {
        $statusEntity = $this->getOmsStatusByName($statusName);

        foreach ($salesOrderItems as $salesOrderItem) {
            $salesOrderItem->setFkOmsOrderItemState($statusEntity->getIdOmsOrderItemState());
            $salesOrderItem->save();
        }
    }

    /**
     * @param string $statusName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    protected function getOmsStatusByName($statusName)
    {
        $statusEntity = SpyOmsOrderItemStateQuery::create()->filterByName($statusName)
            ->findOneOrCreate();
        $statusEntity->save();

        return $statusEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $address
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function buildAddressTransfer(SpySalesOrderAddress $address)
    {
        return (new AddressTransfer())
            ->fromArray($address->toArray(), true)
            ->fromArray($address->getCountry()->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    protected function getPayment(array $salesOrderItems)
    {
        return $this->getPaymentDetails($salesOrderItems[0]);
    }
}
