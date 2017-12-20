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
 * @method \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\AmazonPay\Business\AmazonPayFacade getFacade
 */
class UpdateAuthorizationStatusCommandPlugin extends AbstractAmazonpayCommandPlugin
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
        $amazonpayCallTransfers = $this->groupSalesOrderItemsByAuthId($salesOrderItems);

        foreach ($amazonpayCallTransfers as $amazonpayCallTransfer) {
            $this->addAddresses($amazonpayCallTransfer, $orderEntity);
            $updatedStatus = $this->getFacade()->updateAuthorizationStatus($amazonpayCallTransfer);

            if ($this->isOrderClosed($updatedStatus)) {
                $this->getFacade()->authorizeOrderItems($amazonpayCallTransfer);
            }

            $this->getFacade()->triggerEventForRelatedItems(
                $amazonpayCallTransfer,
                $data->getArrayCopy(),
                AmazonPayConfig::OMS_EVENT_UPDATE_AUTH_STATUS
            );
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $updatedStatus
     *
     * @return bool
     */
    protected function isOrderClosed(AmazonpayCallTransfer $updatedStatus)
    {
        return $updatedStatus->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus()
            ->getState() === AmazonPayConfig::STATUS_CLOSED;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function addAddresses(AmazonpayCallTransfer $amazonpayCallTransfer, SpySalesOrder $orderEntity)
    {
        $amazonpayCallTransfer->setShippingAddress($this->buildAddressTransfer($orderEntity->getShippingAddress()))
            ->setBillingAddress($this->buildAddressTransfer($orderEntity->getBillingAddress()));
    }
}
