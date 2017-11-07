<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;

interface IpnRequestLoggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentAmazonpay
     *
     * @return void
     */
    public function log(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer, SpyPaymentAmazonpay $paymentAmazonpay);
}
