<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command\AbstractAmazonpayCommandPlugin;

class GetOrderTransferCommandPlugin extends AbstractAmazonpayCommandPlugin
{
    /**
     * @inheritdoc
     */
    public function run(array $salesOrderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        return $this->getOrderTransfer($orderEntity);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param array $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $orderEntity, array $salesOrderItems = [])
    {
        $responseHeader = new AmazonpayResponseHeaderTransfer();
        $responseHeader->setIsSuccess(true);

        $paymentTransfer = new AmazonpayPaymentTransfer();
        $paymentTransfer->setResponseHeader($responseHeader);
        $paymentTransfer->setOrderReferenceStatus(new AmazonpayStatusTransfer());
        $paymentTransfer->fromArray($this->getPaymentEntity($orderEntity)->toArray(), true);
        $paymentTransfer->setAuthorizationDetails($this->getAuthorizationDetailsTransfer($orderEntity));
        $paymentTransfer->setCaptureDetails($this->getCaptureDetailsTransfer($orderEntity));
        $paymentTransfer->setRefundDetails($this->getAmazonpayRefundDetailsTransfer($orderEntity));

        $orderTransfer = new OrderTransfer();

        $totals = new TotalsTransfer();
        $totals->setGrandTotal(5000);
        $orderTransfer->setTotals($totals);
        $orderTransfer->setAmazonpayPayment($paymentTransfer);

        return $orderTransfer;
    }
}
