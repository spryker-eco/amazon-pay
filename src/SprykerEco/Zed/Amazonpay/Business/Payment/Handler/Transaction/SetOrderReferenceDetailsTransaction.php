<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;

class SetOrderReferenceDetailsTransaction extends AbstractQuoteTransaction
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer)
    {
        // handling suspended case
        if ($quoteTransfer->getAmazonpayPayment()
            && $quoteTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
            && $quoteTransfer->getAmazonpayPayment()
                ->getAuthorizationDetails()
                ->getAuthorizationStatus()
                ->getIsPaymentMethodInvalid()
        ) {
            return $quoteTransfer;
        }

        $quoteTransfer->getAmazonpayPayment()->setSellerOrderId(
            $this->generateOperationReferenceId($quoteTransfer)
        );

        return parent::execute($quoteTransfer);
    }

}
