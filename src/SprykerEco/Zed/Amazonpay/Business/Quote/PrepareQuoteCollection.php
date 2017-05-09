<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;

class PrepareQuoteCollection implements QuoteUpdaterInterface
{

    /**
     * @var \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface[]
     */
    protected $quoteUpdaters;

    /**
     * @param \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface[] $quoteUpdaters
     */
    public function __construct(array $quoteUpdaters)
    {
        $this->quoteUpdaters = $quoteUpdaters;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function update(QuoteTransfer $quoteTransfer)
    {
        foreach ($this->quoteUpdaters as $quoteUpdater) {
            $quoteTransfer = $quoteUpdater->update($quoteTransfer);
        }

        return $quoteTransfer;
    }

}
