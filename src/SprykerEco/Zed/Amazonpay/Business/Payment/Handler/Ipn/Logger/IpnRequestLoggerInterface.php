<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;

interface IpnRequestLoggerInterface
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $ipnRequest
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $paymentAmazonpay
     *
     * @return void
     */
    public function log(AbstractTransfer $ipnRequest, SpyPaymentAmazonpay $paymentAmazonpay);

}
