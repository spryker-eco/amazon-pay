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
        // no partial reauthorize should be possible
        if ($this->getPaymentEntity($orderEntity)->getStatus()
            === AmazonpayConstants::OMS_STATUS_PAYMENT_METHOD_CHANGED
            && count($orderEntity->getItems()) === count($salesOrderItems)) {
            $this->getFacade()->reauthorizeSuspendedOrder($this->getOrderTransfer($orderEntity));
        }

        return [];
    }

}
