<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger;

use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayIpnLog;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingInterface;

class IpnRequestLogger implements IpnRequestLoggerInterface
{
    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingInterface $utilEncoding
     */
    public function __construct(AmazonpayToUtilEncodingInterface $utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $ipnRequest
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $paymentAmazonpay
     *
     * @return void
     */
    public function log(AbstractTransfer $ipnRequest, SpyPaymentAmazonpay $paymentAmazonpay)
    {
        $ipnLog = new SpyPaymentAmazonpayIpnLog();

        $ipnLog->setMessage($this->utilEncoding->encodeJson($ipnRequest->toArray()));
        $ipnLog->setMessageId($ipnRequest->getMessage()->getMessageId());
        $ipnLog->setFkPaymentAmazonpay($paymentAmazonpay->getIdPaymentAmazonpay());
        $ipnLog->save();
    }
}
