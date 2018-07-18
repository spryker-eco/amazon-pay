<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Converter;

use Generated\Shared\Transfer\QuoteTransfer;

interface AmazonPayConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function mapToAmazonpayCallTransfer(QuoteTransfer $quoteTransfer);
}
