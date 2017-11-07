<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Order;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;

interface PaymentProcessorInterface
{
    /**
     * @param string $orderReferenceId
     * @param string $status
     */
    public function updateStatus($orderReferenceId, $status);

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentAmazonPayEntity
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    public function duplicatePaymentEntity(SpyPaymentAmazonpay $paymentAmazonPayEntity);

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    public function createPaymentEntity(AmazonpayCallTransfer $amazonPayCallTransfer);

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay|null
     */
    public function loadPaymentEntity(AmazonpayCallTransfer $amazonPayCallTransfer);

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentEntity
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return void
     */
    public function assignAmazonpayPaymentToItems(SpyPaymentAmazonpay $paymentEntity, AmazonpayCallTransfer $amazonPayCallTransfer);
}
