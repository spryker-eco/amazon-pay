<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

class SetOrderReferenceDetailsTransaction extends AbstractAmazonpayTransaction
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        if ($amazonpayCallTransfer->getAmazonpayPayment()
            && $amazonpayCallTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
            && $amazonpayCallTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsPaymentMethodInvalid()
        ) {
            return $amazonpayCallTransfer;
        }

        if ($amazonpayCallTransfer->getAmazonpayPayment()) {
            $amazonpayCallTransfer->getAmazonpayPayment()->setSellerOrderId(
                $this->generateOperationReferenceId($amazonpayCallTransfer)
            );
        }

        return parent::execute($amazonpayCallTransfer);
    }
}
