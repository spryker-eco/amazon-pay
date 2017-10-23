<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMessengerInterface;
use Throwable;

class PrepareQuoteCollection implements QuoteUpdaterInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface[]
     */
    protected $quoteUpdaters;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMessengerInterface $messengerFacade
     * @param \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface[] $quoteUpdaters
     */
    public function __construct(AmazonpayToMessengerInterface $messengerFacade, array $quoteUpdaters)
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
        try {
            foreach ($this->quoteUpdaters as $quoteUpdater) {
                $quoteTransfer = $quoteUpdater->update($quoteTransfer);
            }
        } catch (Throwable $e) {
            $this->messengerFacade->addErrorMessage($this->createMessage('amazonpay.timeout.error'));
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
