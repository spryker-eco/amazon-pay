<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;
use SprykerEco\Zed\Amazonpay\Business\Payment\PaymentAmazonpayConverterInterface;

/**
 * @method \SprykerEco\Zed\Amazonpay\Business\AmazonpayFacade getFacade()
 * @method \SprykerEco\Zed\Amazonpay\Communication\AmazonpayCommunicationFactory getFactory()
 */
abstract class AbstractAmazonpayCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @var PaymentAmazonpayConverterInterface
     */
    protected $paymentAmazonpayConverter;

    public function __construct(PaymentAmazonpayConverterInterface $paymentAmazonpayConverter)
    {
        $this->paymentAmazonpayConverter = $paymentAmazonpayConverter;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getOrderItemTransfers(SpySalesOrder $salesOrderEntity)
    {
        $salesOrderTransfer = $this
            ->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        $salesOrderItems = $salesOrderTransfer->getItems();

        foreach ($salesOrderItems as $salesOrderItem) {
            $paymentEntity =

            $salesOrderItemTransfer =
        }

        return $items;

        $orderTransfer =
                $salesOrderEntity->getIdSalesOrder()
            );
    }
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $orderEntity)
    {
        $orderTransfer = $this
            ->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder(
                $orderEntity->getIdSalesOrder()
            );
    }

    /**
     * @param array $salesOrderItems
     * @param SpySalesOrder $orderEntity
     * @param string $message
     *
     * @return bool
     */
    protected function ensureRunForFullOrder(array $salesOrderItems, SpySalesOrder $orderEntity, $message)
    {
        if (count($orderEntity->getItems()) !== count($salesOrderItems)) {
            $this->getFactory()
                ->getMessengerFacade()
                ->addErrorMessage($this->createMessageTransfer($message));

            return false;
        }

        return true;
    }

    /**
     * @param string $message
     *
     * @return MessageTransfer
     */
    protected function createMessageTransfer($message)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);

        return $messageTransfer;
    }

}
