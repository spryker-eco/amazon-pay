<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use ArrayObject;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

class CaptureCommandPlugin extends AbstractAmazonpayCommandPlugin
{
    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $amazonpayCallTransfers = $this->groupSalesOrderItemsByAuthId($salesOrderItems);

        $wasSuccessful = false;

        foreach ($amazonpayCallTransfers as $amazonpayCallTransfer) {
            $amazonpayCallTransfer->setRequestedAmount(
                $this->getRequestedAmountByOrderAndItems($orderEntity, $amazonpayCallTransfer->getItems())
            );
            $result = $this->getFacade()->captureOrder($amazonpayCallTransfer);

            if ($result->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
                $wasSuccessful = true;
            }
        }

        if ($wasSuccessful) {
            $items = new ArrayObject();

            foreach ($orderEntity->getItems() as $salesOrderItem) {
                if ($salesOrderItem->getState()->getName() === AmazonpayConfig::OMS_STATUS_AUTH_OPEN) {
                    $items[] = $salesOrderItem;
                }
            }

            $this->setOrderItemsStatus($items, AmazonpayConfig::OMS_STATUS_AUTH_OPEN_NO_CANCEL);
        }

        return [];
    }

    /**
     * @return string
     */
    protected function getAffectingRequestedAmountItemsStateFlag()
    {
        return AmazonpayConfig::OMS_FLAG_NOT_CAPTURED;
    }
}
