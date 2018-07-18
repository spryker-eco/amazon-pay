<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class AmazonPayToQuoteBridge implements AmazonPayToQuoteInterface
{
    /**
     * @var \Spryker\Client\Quote\QuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\Quote\QuoteClientInterface $quoteClient
     */
    public function __construct($quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return $this->quoteClient->getQuote();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return static
     */
    public function setQuote(QuoteTransfer $quoteTransfer)
    {
        $this->quoteClient->setQuote($quoteTransfer);

        return $this;
    }

    /**
     * @return void
     */
    public function clearQuote()
    {
        $this->quoteClient->clearQuote();
    }
}
