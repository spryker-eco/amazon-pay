<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Converter;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;

interface AmazonPayTransferToEntityConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    public function mapTransferToEntity(AmazonpayPaymentTransfer $amazonpayPaymentTransfer);

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $entity
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return void
     */
    public function updateAfterAuthorization(SpyPaymentAmazonpay $entity, AmazonpayPaymentTransfer $amazonpayPaymentTransfer);

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $entity
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return void
     */
    public function updateAfterRefund(SpyPaymentAmazonpay $entity, AmazonpayPaymentTransfer $amazonpayPaymentTransfer);

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $entity
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return void
     */
    public function updateAfterCapture(SpyPaymentAmazonpay $entity, AmazonpayPaymentTransfer $amazonpayPaymentTransfer);
}
