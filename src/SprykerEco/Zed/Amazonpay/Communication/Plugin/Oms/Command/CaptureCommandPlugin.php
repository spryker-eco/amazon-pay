<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;

class CaptureCommandPlugin extends AbstractAmazonpayCommandPlugin
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

        $this->getFacade()->captureOrder($orderTransfer);

        return [];
    }

}
