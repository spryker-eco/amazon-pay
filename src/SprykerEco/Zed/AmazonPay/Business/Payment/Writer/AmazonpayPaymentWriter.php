<?php


namespace SprykerEco\Zed\AmazonPay\Business\Payment\Writer;


use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;

class AmazonpayPaymentWriter implements AmazonpayPaymentWriterInterface
{
    /**
     * @param AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return bool
     */
    public function writerConfirmedAmazonPayPayment(AmazonpayPaymentTransfer $amazonpayPaymentTransfer): bool
    {
        $paymentEntity = new SpyPaymentAmazonpay();
        $paymentEntity->setOrderReferenceId($amazonpayPaymentTransfer->getOrderReferenceId());
        $status = $this->getConfirmedOrderStatus($amazonpayPaymentTransfer);
        $paymentEntity->setStatus($status);
        $paymentEntity->setSellerOrderId($amazonpayPaymentTransfer->getSellerOrderId());

        $paymentEntity->setAuthorizationReferenceId(
            $amazonpayPaymentTransfer->getAuthorizationDetails()->getAuthorizationReferenceId()
        );

        $paymentEntity->setAmazonAuthorizationId(
            $amazonpayPaymentTransfer->getAuthorizationDetails()->getAmazonAuthorizationId()
        );

        $paymentEntity->setRequestId(
            $amazonpayPaymentTransfer->getResponseHeader()->getRequestId()
        );

        $paymentEntity->setIsSandbox($amazonpayPaymentTransfer->getIsSandbox());

        return $paymentEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $paymentTransfer
     *
     * @return string
     */
    protected function getConfirmedOrderStatus(AmazonpayPaymentTransfer $paymentTransfer)
    {
        return $paymentTransfer->getOrderReferenceStatus()->getState();
    }
}
