<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Converter;

use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;

interface AmazonpayEntityToTransferConverterInterface
{

    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $entity
     *
     * @return \Generated\Shared\Transfer\AmazonpayPaymentTransfer
     */
    public function mapEntityToTransfer(SpyPaymentAmazonpay $entity);

}
