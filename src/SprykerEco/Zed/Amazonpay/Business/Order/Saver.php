<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Order;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
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
        $this->savePaymentForOrder(
            $quoteTransfer->getAmazonpayPayment(),
            $checkoutResponseTransfer->getSaveOrder()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function savePaymentForOrder(AmazonpayPaymentTransfer $paymentTransfer, SaveOrderTransfer $saveOrderTransfer)
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

        $paymentEntity->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());

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
