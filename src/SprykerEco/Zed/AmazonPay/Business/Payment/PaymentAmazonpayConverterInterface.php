<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment;

use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;

interface PaymentAmazonpayConverterInterface
{
    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $entity
     *
     * @return \Generated\Shared\Transfer\AmazonpayPaymentTransfer
     */
    public function mapPaymentEntity(SpyPaymentAmazonpay $entity);
}
