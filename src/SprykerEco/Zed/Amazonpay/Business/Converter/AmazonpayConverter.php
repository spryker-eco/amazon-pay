<?php

namespace SprykerEco\Zed\Amazonpay\Business\Converter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class AmazonpayConverter implements AmazonpayConverterInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function mapToAmazonpayCallTransfer(QuoteTransfer $quoteTransfer)
    {
        $amazonpayCallTransfer = new AmazonpayCallTransfer();
        $amazonpayCallTransfer->fromArray($quoteTransfer->toArray(), true);

        $amazonpayCallTransfer->setRequestedAmount($quoteTransfer->getTotals()->getGrandTotal());
        $amazonpayCallTransfer->setItems($quoteTransfer->getItems());

        return $amazonpayCallTransfer;
    }

}
