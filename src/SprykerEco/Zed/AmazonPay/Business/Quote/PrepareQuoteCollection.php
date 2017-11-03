<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Quote;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMessengerInterface;

class PrepareQuoteCollection implements QuoteUpdaterInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface[]
     */
    protected $quoteUpdaters;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMessengerInterface $messengerFacade
     * @param \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface[] $quoteUpdaters
     */
    public function __construct(AmazonPayToMessengerInterface $messengerFacade, array $quoteUpdaters)
    {
        $this->messengerFacade = $messengerFacade;
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

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage($message)
    {
        return (new MessageTransfer())
            ->setValue($message);
    }
}
