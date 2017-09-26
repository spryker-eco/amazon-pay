<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

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
            $amazonpayCallTransfer->setEmail($customerEmail);
            $amazonpayCallTransfer->setRequestedAmount(
                $this->getRequestedAmountByOrderAndItems($orderEntity, $amazonpayCallTransfer->getItems())
            );
            $this->getFacade()->reauthorizeExpiredOrder($amazonpayCallTransfer);
        }
        return [];
    }

    /**
     * @return string
     */
    protected function getAffectingRequestedAmountItemsStateFlag()
    {
        return AmazonpayConstants::OMS_FLAG_NOT_AUTH;
    }

}
