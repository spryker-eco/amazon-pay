<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class SetOrderReferenceDetailsTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonPayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonPayCallTransfer)
    {
        if ($amazonPayCallTransfer->getAmazonpayPayment()
            && $amazonPayCallTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
            && $amazonPayCallTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getState() === AmazonPayConfig::STATUS_PAYMENT_METHOD_INVALID
            || $amazonPayCallTransfer->getAmazonpayPayment()
                ->getOrderReferenceStatus()
            && $amazonPayCallTransfer->getAmazonpayPayment()
                ->getOrderReferenceStatus()
                ->getState() === AmazonPayConfig::STATUS_OPEN
        ) {
            return $amazonPayCallTransfer;
        }

        if ($amazonPayCallTransfer->getAmazonpayPayment()) {
            $amazonPayCallTransfer->getAmazonpayPayment()->setSellerOrderId(
                $this->generateOperationReferenceId($amazonPayCallTransfer)
            );
        }

        return parent::execute($amazonPayCallTransfer);
    }
}
