<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class UpdateSuspendedOrderCommandPlugin extends AbstractAmazonpayCommandPlugin
{
    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $amazonpayCallTransfers = $this->groupSalesOrderItemsByAuthId($salesOrderItems);

        foreach ($amazonpayCallTransfers as $amazonpayCallTransfer) {
            $amazonpayCallTransfer->setRequestedAmount(
                $this->getRequestedAmountByOrderAndItems($orderEntity, $amazonpayCallTransfer->getItems())
            );

            if ($amazonpayCallTransfer->getAmazonpayPayment()->getStatus()
                === AmazonPayConfig::STATUS_PAYMENT_METHOD_CHANGED
            ) {
                $this->getFacade()->reauthorizeSuspendedOrder($amazonpayCallTransfer);
            }

            $this->getFacade()->captureOrder($amazonpayCallTransfer);
        }

        return [];
    }

    /**
     * @return string
     */
    protected function getAffectingRequestedAmountItemsStateFlag()
    {
        return AmazonPayConfig::OMS_FLAG_NOT_AUTH;
    }
}
