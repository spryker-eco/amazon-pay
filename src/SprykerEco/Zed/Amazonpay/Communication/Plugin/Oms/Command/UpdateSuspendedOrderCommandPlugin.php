<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;

class UpdateSuspendedOrderCommandPlugin extends AbstractAmazonpayCommandPlugin
{

    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        if (!$this->ensureRunForFullOrder($salesOrderItems, $orderEntity, 'amazonpay.update-suspended.error.only-full-order')) {
            return [];
        }

        /** TODO: looks like we might introduce special state and get rid of this IF */
        if ($this->getPaymentEntity($orderEntity)->getStatus()
            === AmazonpayConstants::OMS_STATUS_PAYMENT_METHOD_CHANGED) {
            $this->getFacade()->reauthorizeSuspendedOrder($this->getOrderTransfer($orderEntity));
        }

        return [];
    }

}
