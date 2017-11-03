<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Converter;

use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;

interface AmazonPayEntityToTransferConverterInterface
{
    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $entity
     *
     * @return \Generated\Shared\Transfer\AmazonpayPaymentTransfer
     */
    public function mapEntityToTransfer(SpyPaymentAmazonpay $entity);
}
