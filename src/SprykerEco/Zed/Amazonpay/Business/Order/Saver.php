<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Order;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpaySalesOrderItem;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class Saver implements SaverInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $paymentAmazonpayEntity = $this->createPaymentAmazonpay($quoteTransfer->getAmazonpayPayment());

        $this->assignPaymentEntityToItems(
            $paymentAmazonpayEntity,
            $checkoutResponseTransfer->getSaveOrder()->getOrderItems()
        );
    }

    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $paymentEntity
     * @param \ArrayObject|ItemTransfer[] $orderItems
     */
    protected function assignPaymentEntityToItems(SpyPaymentAmazonpay $paymentEntity, \ArrayObject $orderItems)
    {
        foreach ($orderItems as $itemTransfer) {
            $this->assignPaymentToOrderItem($paymentEntity->getIdPaymentAmazonpay(), $itemTransfer->getIdSalesOrderItem());
        }
    }

    /**
     * @param $idPayment
     * @param $orderItem
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpaySalesOrderItem
     */
    protected function assignPaymentToOrderItem($idPayment, $orderItem)
    {
        $entity = new SpyPaymentAmazonpaySalesOrderItem();
        $entity->setFkPaymentAmazonpay($idPayment)
            ->setFkSalesOrderItem($orderItem);

        $entity->save();

        return $entity;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $paymentTransfer
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function createPaymentAmazonpay(AmazonpayPaymentTransfer $paymentTransfer)
    {
        $paymentEntity = new SpyPaymentAmazonpay();
        $paymentEntity->setOrderReferenceId($paymentTransfer->getOrderReferenceId());
        $paymentEntity->setStatus($this->getOrderStatus($paymentTransfer));
        $paymentEntity->setSellerOrderId($paymentTransfer->getSellerOrderId());

        $paymentEntity->setAuthorizationReferenceId(
            $paymentTransfer->getAuthorizationDetails()->getAuthorizationReferenceId()
        );

        $paymentEntity->setAmazonAuthorizationId(
            $paymentTransfer->getAuthorizationDetails()->getAmazonAuthorizationId()
        );

        $paymentEntity->setAmazonCaptureId(
            $paymentTransfer->getAuthorizationDetails()->getIdList()
        );

        $paymentEntity->setRequestId(
            $paymentTransfer->getResponseHeader()->getRequestId()
        );

        $paymentEntity->setIsSandbox($paymentTransfer->getIsSandbox());
        $paymentEntity->save();

        return $paymentEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $paymentTransfer
     *
     * @return string
     */
    protected function getOrderStatus(AmazonpayPaymentTransfer $paymentTransfer)
    {
        if ($paymentTransfer->getAuthorizationDetails()->getIdList()) {
            return AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED;
        }

        if ($paymentTransfer->getAuthorizationDetails()->getAuthorizationStatus()->getIsDeclined()) {
            return AmazonpayConstants::OMS_STATUS_AUTH_DECLINED;
        }

        if ($paymentTransfer->getAuthorizationDetails()->getAuthorizationStatus()->getIsPending()) {
            return AmazonpayConstants::OMS_STATUS_AUTH_PENDING;
        }

        if ($paymentTransfer->getAuthorizationDetails()->getAuthorizationStatus()->getIsOpen()) {
            return AmazonpayConstants::OMS_STATUS_AUTH_OPEN;
        }
    }

}
