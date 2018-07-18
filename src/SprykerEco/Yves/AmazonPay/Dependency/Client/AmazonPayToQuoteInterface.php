<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface AmazonPayToQuoteInterface
{
    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote();

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return static
     */
    public function setQuote(QuoteTransfer $quoteTransfer);

    /**
     * @return void
     */
    public function clearQuote();
}
