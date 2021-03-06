<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteUpdaterCollection implements QuoteUpdaterInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface[]
     */
    protected $quoteUpdaters;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface[] $quoteUpdaters
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
