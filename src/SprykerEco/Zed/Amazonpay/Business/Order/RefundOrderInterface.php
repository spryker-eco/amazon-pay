<?php

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
