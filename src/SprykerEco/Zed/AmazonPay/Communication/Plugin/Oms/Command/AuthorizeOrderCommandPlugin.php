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
 * @method \SprykerEco\Zed\AmazonPay\Business\AmazonPayFacadeInterface getFacade()
 */
class AuthorizeOrderCommandPlugin extends AbstractAmazonpayCommandPlugin
{
    /**
     * {@inheritdoc}
     * - Uses API adapter for sending a request to Amazon for order authorization operation.
     *
     * @api
     *
     * @param array $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): array
    {
        $amazonpayCallTransfers = $this->groupSalesOrderItemsByAuthId($salesOrderItems);
        $customerEmail = $orderEntity->getEmail() ?? $orderEntity->getCustomer()->getEmail();

        foreach ($amazonpayCallTransfers as $amazonpayCallTransfer) {
            $this->updateCallTransfer($amazonpayCallTransfer, $orderEntity, $customerEmail);
            $this->getFacade()->authorizeOrderItems($amazonpayCallTransfer);
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
    protected function updateCallTransfer(AmazonpayCallTransfer $amazonpayCallTransfer, SpySalesOrder $orderEntity, string $customerEmail): void
    {
        $amazonpayCallTransfer->setShippingAddress($this->buildAddressTransfer($orderEntity->getShippingAddress()))
            ->setBillingAddress($this->buildAddressTransfer($orderEntity->getBillingAddress()));
        $amazonpayCallTransfer->setEmail($customerEmail);
        $amazonpayCallTransfer->setRequestedAmount(
            $this->getRequestedAmountByOrderAndItems($orderEntity, $amazonpayCallTransfer->getItems())
        );
    }

    /**
     * @return string
     */
    protected function getAffectingRequestedAmountItemsStateFlag(): string
    {
        return AmazonPayConfig::OMS_FLAG_NOT_AUTH;
    }
}
