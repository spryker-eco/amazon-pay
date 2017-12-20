<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayIpnLog;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToUtilEncodingInterface;

class IpnRequestLogger implements IpnRequestLoggerInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToUtilEncodingInterface $utilEncoding
     */
    public function __construct(AmazonPayToUtilEncodingInterface $utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentAmazonpay
     *
     * @return void
     */
    public function log(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer, SpyPaymentAmazonpay $paymentAmazonpay)
    {
        $ipnLog = new SpyPaymentAmazonpayIpnLog();

        $ipnLog->setMessage($this->utilEncoding->encodeJson($paymentRequestTransfer->toArray()));
        $ipnLog->setMessageId($paymentRequestTransfer->getMessage()->getMessageId());
        $ipnLog->setFkPaymentAmazonpay($paymentAmazonpay->getIdPaymentAmazonpay());
        $ipnLog->save();
    }
}
