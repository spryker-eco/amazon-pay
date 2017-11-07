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

class ReauthorizeExpiredOrderCommandPlugin extends AbstractAmazonpayCommandPlugin
{
    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $amazonpayCallTransfers = $this->groupSalesOrderItemsByAuthId($salesOrderItems);
        $customerEmail = $orderEntity->getEmail() ?? $orderEntity->getCustomer()->getEmail();

        foreach ($amazonpayCallTransfers as $amazonpayCallTransfer) {
            $this->processOrder($amazonpayCallTransfer, $orderEntity, $customerEmail);
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param string $customerEmail
     *
     * @return void
     */
    protected function processOrder(AmazonpayCallTransfer $amazonpayCallTransfer, SpySalesOrder $orderEntity, $customerEmail)
    {
        $amazonpayCallTransfer->setShippingAddress($this->buildAddressTransfer($orderEntity->getShippingAddress()))
            ->setBillingAddress($this->buildAddressTransfer($orderEntity->getBillingAddress()));
        $amazonpayCallTransfer->setEmail($customerEmail);
        $amazonpayCallTransfer->setRequestedAmount(
            $this->getRequestedAmountByOrderAndItems($orderEntity, $amazonpayCallTransfer->getItems())
        );
        $this->getFacade()->reauthorizeExpiredOrder($amazonpayCallTransfer);
    }

    /**
     * @return string
     */
    protected function getAffectingRequestedAmountItemsStateFlag()
    {
        return AmazonPayConfig::OMS_FLAG_NOT_AUTH;
    }
}
