<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment;

use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;

interface PaymentAmazonpayConverterInterface
{

    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $entity
     *
     * @return \Generated\Shared\Transfer\AmazonpayPaymentTransfer
     */
    public function mapPaymentEntity(SpyPaymentAmazonpay $entity);

}
