<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

/**
 * @method \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface getQueryContainer()
 */
class UpdateAuthorizationStatusCommandPlugin extends AbstractAmazonpayCommandPlugin
{
    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $amazonpayCallTransfers = $this->groupSalesOrderItemsByAuthId($salesOrderItems);

        foreach ($amazonpayCallTransfers as $amazonpayCallTransfer) {
            $amazonpayCallTransfer->setShippingAddress($this->buildAddressTransfer($orderEntity->getShippingAddress()))
                ->setBillingAddress($this->buildAddressTransfer($orderEntity->getBillingAddress()));
            $updatedStatus = $this->getFacade()->updateAuthorizationStatus($amazonpayCallTransfer);

            if ($updatedStatus->getAmazonpayPayment()->getAuthorizationDetails()->getAuthorizationStatus()->getIsClosed()) {
                $this->getFacade()->authorizeOrderItems($amazonpayCallTransfer);
            }

            $this->triggerEventForRelatedItems($amazonpayCallTransfer, $data->getArrayCopy());
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param array $alreadyAffectedItems
     *
     * @return void
     */
    protected function triggerEventForRelatedItems(AmazonpayCallTransfer $amazonpayCallTransfer, array $alreadyAffectedItems)
    {
        $affectedItems = array_map(
            function (ItemTransfer $item) {
                return $item->getIdSalesOrderItem();
            },
            $amazonpayCallTransfer->getItems()->getArrayCopy()
        );
        $affectedItems = array_merge($affectedItems, $alreadyAffectedItems);

        $toBeUpdatedItems = $this->getQueryContainer()
            ->querySalesOrderItemsByPaymentReferenceId(
                $amazonpayCallTransfer->getAmazonpayPayment()->getAuthorizationDetails()->getAuthorizationReferenceId(),
                $affectedItems
            )
            ->find();

        if ($toBeUpdatedItems->count() > 0) {
            $this->getFactory()
                ->getOmsFacade()
                ->triggerEvent(
                    AmazonpayConfig::OMS_EVENT_UPDATE_AUTH_STATUS,
                    $toBeUpdatedItems,
                    [],
                    $affectedItems
                );
        }
    }
}
