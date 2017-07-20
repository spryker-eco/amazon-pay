<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use ArrayObject;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;

class CloseOrderCommandPlugin extends AbstractAmazonpayCommandPlugin
{

    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $orderTransfer = $this->getOrderTransfer($orderEntity);

        $refundTransfer = $this->getFactory()
            ->getRefundFacade()
            ->calculateRefund($salesOrderItems, $orderEntity);

        $orderTransfer->getTotals()->setGrandTotal(
            $refundTransfer->getAmount()
        );
        $orderTransfer->setItems(new ArrayObject($salesOrderItems));

        $this->getFacade()->closeOrder($orderTransfer);

        return [];
    }

}
