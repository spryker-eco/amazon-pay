<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command;

use ArrayObject;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class CaptureCommandPlugin extends AbstractAmazonpayCommandPlugin
{
    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $amazonpayCallTransfers = $this->groupSalesOrderItemsByAuthId($salesOrderItems);

        $wasSuccessful = $this->captureGroupedOrderItems($amazonpayCallTransfers, $orderEntity);

        if ($wasSuccessful) {
            $this->updateOrdersStatus($orderEntity);
        }

        return [];
    }

    /**
     * @param array $amazonpayCallTransfers
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return bool
     */
    protected function captureGroupedOrderItems(array $amazonpayCallTransfers, SpySalesOrder $orderEntity)
    {
        $wasSuccessful = false;

        foreach ($amazonpayCallTransfers as $amazonpayCallTransfer) {
            $amazonpayCallTransfer->setRequestedAmount(
                $this->getRequestedAmountByOrderAndItems($orderEntity, $amazonpayCallTransfer->getItems())
            );
            $result = $this->getFacade()->captureOrder($amazonpayCallTransfer);

            if ($this->isPaymentSuccess($result)) {
                $wasSuccessful = true;
            }
        }

        return $wasSuccessful;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function updateOrdersStatus(SpySalesOrder $orderEntity)
    {
        $items = new ArrayObject();

        foreach ($orderEntity->getItems() as $salesOrderItem) {
            if ($salesOrderItem->getState()->getName() === AmazonPayConfig::OMS_STATUS_AUTH_OPEN) {
                $items[] = $salesOrderItem;
            }
        }

        $this->setOrderItemsStatus($items, AmazonPayConfig::OMS_STATUS_AUTH_OPEN_WITHOUT_CANCEL);
    }

    /**
     * @return string
     */
    protected function getAffectingRequestedAmountItemsStateFlag()
    {
        return AmazonPayConfig::OMS_FLAG_NOT_CAPTURED;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $payment
     *
     * @return bool
     */
    protected function isPaymentSuccess(AmazonpayCallTransfer $payment)
    {
        return $payment->getAmazonpayPayment() &&
            $payment->getAmazonpayPayment()->getResponseHeader() &&
            $payment->getAmazonpayPayment()->getResponseHeader()->getIsSuccess();
    }
}
