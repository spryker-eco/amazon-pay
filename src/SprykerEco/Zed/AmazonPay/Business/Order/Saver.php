<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Order;

use ArrayObject;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpaySalesOrderItem;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

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
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentEntity
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return void
     */
    protected function assignPaymentEntityToItems(SpyPaymentAmazonpay $paymentEntity, ArrayObject $orderItems)
    {
        foreach ($orderItems as $itemTransfer) {
            $this->assignPaymentToOrderItem($paymentEntity->getIdPaymentAmazonpay(), $itemTransfer->getIdSalesOrderItem());
        }
    }

    /**
     * @param int $idPayment
     * @param int $orderItem
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpaySalesOrderItem
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
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
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
            return AmazonPayConfig::OMS_STATUS_CAPTURE_COMPLETED;
        }

        if ($paymentTransfer->getAuthorizationDetails()->getAuthorizationStatus()->getIsDeclined()) {
            return AmazonPayConfig::OMS_STATUS_AUTH_DECLINED;
        }

        if ($paymentTransfer->getAuthorizationDetails()->getAuthorizationStatus()->getIsPending()) {
            return AmazonPayConfig::OMS_STATUS_AUTH_PENDING;
        }

        if ($paymentTransfer->getAuthorizationDetails()->getAuthorizationStatus()->getIsOpen()) {
            return AmazonPayConfig::OMS_STATUS_AUTH_OPEN;
        }

        return AmazonPayConfig::OMS_STATUS_CANCELLED;
    }
}
