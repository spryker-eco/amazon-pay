<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use ArrayObject;
use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpaySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \SprykerEco\Zed\Amazonpay\Business\AmazonpayFacade getFacade()
 * @method \SprykerEco\Zed\Amazonpay\Communication\AmazonpayCommunicationFactory getFactory()
 */
abstract class AbstractAmazonpayCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{

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
     * @param array $salesOrderItems
     * @param SpySalesOrder $orderEntity
     * @param string $message
     *
     * @return bool
     */
    protected function ensureRunForFullOrder(array $salesOrderItems, SpySalesOrder $orderEntity, $message)
    {
        if (count($orderEntity->getItems()) !== count($salesOrderItems)) {
            $this->getFactory()
                ->getMessengerFacade()
                ->addErrorMessage($this->createMessageTransfer($message));

            return false;
        }

        return true;
    }

    /**
     * @param string $message
     *
     * @return MessageTransfer
     */
    protected function createMessageTransfer($message)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);

        return $messageTransfer;
    }

    /**
     * @param SpySalesOrder $orderEntity
     * @param \ArrayObject|ItemTransfer[] $itemTransfers
     *
     * @return int
     */
    protected function getRequestedAmountByOrderAndItems(SpySalesOrder $orderEntity, ArrayObject $itemTransfers)
    {
        $subtotal = 0;

        foreach ($itemTransfers as $itemTransfer) {
            $subtotal+= $itemTransfer->getUnitPriceToPayAggregation();
        }

        return $subtotal;
    }

    /**
     * @param SpySalesOrderItem[] $salesOrderItems
     *
     * @return AmazonpayCallTransfer[]
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
     * @param SpySalesOrder $orderEntity
     * @param SpySalesOrderItem[] $salesOrderItems
     *
     * @return AmazonpayCallTransfer
     */
    protected function createAmazonpayCallTransfer(SpySalesOrder $orderEntity, array $salesOrderItems)
    {
        $amazonpayCallTransfer = new AmazonpayCallTransfer();
        $amazonpayCallTransfer->setItems(
            new ArrayObject(
                array_map(array($this, 'mapSalesOrderItemToItemTransfer'), $salesOrderItems)
            )
        );
        $amazonPayment = $this->getAmazonpayPaymentTransferBySalesOrder($salesOrderItems[0]);
        $amazonpayCallTransfer->setAmazonpayPayment($amazonPayment);

        $amazonpayCallTransfer->setRequestedAmount(
            $this->getRequestedAmountByOrderAndItems($orderEntity, $amazonpayCallTransfer->getItems())
        );

        return $amazonpayCallTransfer;
    }

    /**
     * @param SpySalesOrderItem $salesOrderItem
     *
     * @return AmazonpayPaymentTransfer
     */
    protected function getAmazonpayPaymentTransferBySalesOrder(SpySalesOrderItem $salesOrderItem)
    {
        return $this->getFacade()->mapAmazonPaymentToTransfer($this->getPaymentDetails($salesOrderItem));
    }

    /**
     * @param SpySalesOrderItem $salesOrderItem
     *
     * @return null|SpyPaymentAmazonpay
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
     * @param SpySalesOrderItem $salesOrderItemEntity
     *
     * @return ItemTransfer
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
