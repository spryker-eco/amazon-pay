<?php

namespace SprykerEco\Zed\Amazonpay\Business\Payment;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteToAmazonpayConverter
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function convert(QuoteTransfer $quoteTransfer)
    {
        $amazonpayCallTransfer = new AmazonpayCallTransfer();
        $amazonpayCallTransfer->setAmazonpayPayment($quoteTransfer->getAmazonpayPayment())
            ->setRequestedAmount($quoteTransfer->getTotals()->getGrandTotal());

        return $amazonpayCallTransfer;
    }

}
