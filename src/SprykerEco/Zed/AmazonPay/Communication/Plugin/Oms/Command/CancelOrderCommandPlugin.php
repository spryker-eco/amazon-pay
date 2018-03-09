<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

/**
 * @method \SprykerEco\Zed\AmazonPay\Business\AmazonPayFacade getFacade()
 */
class CancelOrderCommandPlugin extends AbstractAmazonpayCommandPlugin
{
    /**
     * @param array $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $amazonpayCallTransfer = $this->createAmazonpayCallTransfer(
            $this->getPayment($salesOrderItems)
        );

        $this->getFacade()->cancelOrder($amazonpayCallTransfer);

        $this->populateItems($amazonpayCallTransfer, $salesOrderItems);
        $this->cancelSalesOrder($amazonpayCallTransfer, $data);

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return void
     */
    protected function cancelSalesOrder(AmazonpayCallTransfer $amazonpayCallTransfer, ReadOnlyArrayObject $data)
    {
        $this->getFacade()->triggerEventForRelatedItems(
            $amazonpayCallTransfer,
            $data->getArrayCopy(),
            AmazonPayConfig::OMS_EVENT_CANCEL
        );
    }
}
