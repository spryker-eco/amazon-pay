<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;

abstract class AbstractTransactionCollection
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AbstractQuoteTransaction[]
     */
    protected $transactionHandlers;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\AbstractQuoteTransaction[] $transactionHandlers
     */
    public function __construct(
        array $transactionHandlers
    ) {
        $this->transactionHandlers = $transactionHandlers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeHandlers(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->getAmazonpayPayment()->setResponseHeader(null);

        foreach ($this->transactionHandlers as $transactionHandler) {
            $quoteTransfer = $transactionHandler->execute($quoteTransfer);

            if ($quoteTransfer->getAmazonpayPayment()->getResponseHeader() &&
                !$quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
                break;
            }
        }

        return $quoteTransfer;
    }

}
