<?php

namespace SprykerEco\Zed\Amazonpay\Business\Converter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface AmazonpayConverterInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function mapToAmazonpayCallTransfer(QuoteTransfer $quoteTransfer);

}
