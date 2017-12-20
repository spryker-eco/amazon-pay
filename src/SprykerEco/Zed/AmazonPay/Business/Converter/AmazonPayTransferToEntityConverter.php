<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Converter;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;

class AmazonPayTransferToEntityConverter implements AmazonPayTransferToEntityConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    public function mapTransferToEntity(AmazonpayPaymentTransfer $amazonpayPaymentTransfer)
    {
        $paymentEntity = new SpyPaymentAmazonpay();
        $paymentEntity->fromArray($amazonpayPaymentTransfer->toArray());

        return $paymentEntity;
    }
}
