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

/**
 * @method \SprykerEco\Zed\Amazonpay\Business\AmazonpayFacade getFacade()
 * @method \SprykerEco\Zed\Amazonpay\Communication\AmazonpayCommunicationFactory getFactory()
 */
abstract class AbstractAmazonpayCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{

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

        $paymentTransfer = $this->buildAmazonPaymentTransfer($orderEntity);
        $orderTransfer->setAmazonpayPayment($paymentTransfer);

        return $orderTransfer;
    }

    /**
     * @param SpySalesOrder $orderEntity
     *
     * @return AmazonpayPaymentTransfer
     */
    protected function buildAmazonPaymentTransfer(SpySalesOrder $orderEntity)
    {
        $responseHeader = new AmazonpayResponseHeaderTransfer();
        $responseHeader->setIsSuccess(true);

        $paymentTransfer = new AmazonpayPaymentTransfer();
        $paymentTransfer->fromArray($this->getPaymentEntity($orderEntity)->toArray(), true);
        $paymentTransfer->setResponseHeader($responseHeader);
        $paymentTransfer->setOrderReferenceStatus(new AmazonpayStatusTransfer());
        $paymentTransfer->setAuthorizationDetails($this->getAuthorizationDetailsTransfer($orderEntity));
        $paymentTransfer->setCaptureDetails($this->getCaptureDetailsTransfer($orderEntity));
        $paymentTransfer->setRefundDetails($this->getAmazonpayRefundDetailsTransfer($orderEntity));

        return $paymentTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function getPaymentEntity(SpySalesOrder $orderEntity)
    {
        return $orderEntity->getSpyPaymentAmazonpays()->getFirst();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer
     */
    protected function getAuthorizationDetailsTransfer(SpySalesOrder $orderEntity)
    {
        $authDetailsTransfer = new AmazonpayAuthorizationDetailsTransfer();
        $authDetailsTransfer->fromArray($this->getPaymentEntity($orderEntity)->toArray(), true);
        $authDetailsTransfer->setAuthorizationStatus(
            $this->getAuthStatusTransfer($this->getPaymentEntity($orderEntity)->getStatus())
        );

        return $authDetailsTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer
     */
    protected function getAmazonpayRefundDetailsTransfer(SpySalesOrder $orderEntity)
    {
        $refundDetailsTransfer = new AmazonpayRefundDetailsTransfer();
        $refundDetailsTransfer->fromArray($this->getPaymentEntity($orderEntity)->toArray(), true);
        $refundDetailsTransfer->setRefundStatus(
            $this->getRefundStatusTransfer($this->getPaymentEntity($orderEntity)->getStatus())
        );

        return $refundDetailsTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer
     */
    protected function getCaptureDetailsTransfer($orderEntity)
    {
        $captureDetailsTransfer = new AmazonpayCaptureDetailsTransfer();
        $captureDetailsTransfer->fromArray($this->getPaymentEntity($orderEntity)->toArray(), true);
        $captureDetailsTransfer->setCaptureStatus(
            $this->getCaptureStatusTransfer($this->getPaymentEntity($orderEntity)->getStatus())
        );

        return $captureDetailsTransfer;
    }

    /**
     * @param string $statusName
     *
     * @return \Generated\Shared\Transfer\AmazonpayStatusTransfer
     */
    protected function getAuthStatusTransfer($statusName)
    {
        $amazonpayStatusTransfer = new AmazonpayStatusTransfer();

        $amazonpayStatusTransfer->setIsPending(
            $statusName === AmazonpayConstants::OMS_STATUS_AUTH_PENDING
        );

        $amazonpayStatusTransfer->setIsDeclined(
            $statusName === AmazonpayConstants::OMS_STATUS_AUTH_DECLINED ||
            $statusName === AmazonpayConstants::OMS_STATUS_AUTH_SUSPENDED
        );

        $amazonpayStatusTransfer->setIsSuspended(
            $statusName === AmazonpayConstants::OMS_STATUS_AUTH_SUSPENDED
        );

        $amazonpayStatusTransfer->setIsTransactionTimedOut(
            $statusName === AmazonpayConstants::OMS_STATUS_AUTH_TRANSACTION_TIMED_OUT
        );

        $amazonpayStatusTransfer->setIsOpen(
            $statusName === AmazonpayConstants::OMS_STATUS_AUTH_OPEN
        );

        return $amazonpayStatusTransfer;
    }

    /**
     * @param string $statusName
     *
     * @return \Generated\Shared\Transfer\AmazonpayStatusTransfer
     */
    protected function getCaptureStatusTransfer($statusName)
    {
        $amazonpayStatusTransfer = new AmazonpayStatusTransfer();

        $amazonpayStatusTransfer->setIsPending(
            $statusName === AmazonpayConstants::OMS_STATUS_CAPTURE_PENDING
        );

        $amazonpayStatusTransfer->setIsDeclined(
            $statusName === AmazonpayConstants::OMS_STATUS_CAPTURE_DECLINED
        );

        $amazonpayStatusTransfer->setIsCompleted(
            $statusName === AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED
        );

        $amazonpayStatusTransfer->setIsClosed(
            $statusName === AmazonpayConstants::OMS_STATUS_CAPTURE_CLOSED
        );

        return $amazonpayStatusTransfer;
    }

    /**
     * @param string $statusName
     *
     * @return \Generated\Shared\Transfer\AmazonpayStatusTransfer
     */
    protected function getRefundStatusTransfer($statusName)
    {
        $amazonpayStatusTransfer = new AmazonpayStatusTransfer();

        $amazonpayStatusTransfer->setIsPending(
            $statusName === AmazonpayConstants::OMS_STATUS_REFUND_PENDING
        );

        $amazonpayStatusTransfer->setIsDeclined(
            $statusName === AmazonpayConstants::OMS_STATUS_REFUND_DECLINED
        );

        $amazonpayStatusTransfer->setIsCompleted(
            $statusName === AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED
        );

        return $amazonpayStatusTransfer;
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
