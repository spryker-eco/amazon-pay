<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\AmazonpaySalesOrderItemGroupTransfer;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpaySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;

class UpdateAuthorizationStatusCommandPlugin extends AbstractAmazonpayCommandPlugin
{

    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $salesOrderItemGroups = $this->groupSalesOrderItemsByPayment($salesOrderItems);

        foreach ($salesOrderItemGroups as $salesOrderItemGroup) {
            $this->getFacade()->updateAuthorizationStatus(
                $this->buildAmazonpayCallTransfer(
                    $salesOrderItemGroup->getAmazonpayPayment(),
                    $this->getRequestedAmountByOrderAndItems($orderEntity, $salesOrderItemGroup->getSalesOrderItems())
                )
            );
        }

        return [];
    }

    /**
     * @param SpySalesOrder $orderEntity
     * @param SpySalesOrderItem[] $salesOrderItems
     *
     * @return int
     */
    protected function getRequestedAmountByOrderAndItems(SpySalesOrder $orderEntity, array $salesOrderItems)
    {
        $subtotal = 0;

        foreach ($salesOrderItems as $salesOrderItem) {
            $subtotal+= $salesOrderItem->getGrossPrice();
        }

        return $subtotal;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPayment
     * @param int $requestedAmount
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    protected function buildAmazonpayCallTransfer(AmazonpayPaymentTransfer $amazonpayPayment, $requestedAmount)
    {
        return (new AmazonpayCallTransfer())
            ->setAmazonpayPayment($amazonpayPayment)
            ->setRequestedAmount($requestedAmount);
    }

    /**
     * @param array|SpySalesOrderItem[] $salesOrderItems
     *
     * @return AmazonpaySalesOrderItemGroupTransfer[]
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
                $groupData = new AmazonpaySalesOrderItemGroupTransfer();

                $paymentTransfer = $this->getFacade()->mapAmazonPaymentToTransfer($payment);

                $groupData->setAmazonpayPayment($paymentTransfer);
            }

            $groupData->addSalesOrderItem($salesOrderItem);

            $groups[$payment->getAuthorizationReferenceId()] = $groupData;
        }

        return $groups;
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

}
