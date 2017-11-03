<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Order;

use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;

interface RefundOrderInterface
{
    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $paymentEntity
     *
     * @return void
     */
    public function refundPayment(SpyPaymentAmazonpay $paymentEntity);
}
