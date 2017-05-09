<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\QuoteTransfer;

abstract class AbstractQuoteTransaction extends AbstractTransaction implements QuoteTransactionInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateOperationReferenceId(QuoteTransfer $quoteTransfer)
    {
        return uniqid($quoteTransfer->getAmazonpayPayment()->getOrderReferenceId());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer)
    {
        $this->apiResponse = $this->executionAdapter->call($quoteTransfer);
        $quoteTransfer->getAmazonpayPayment()->setResponseHeader($this->apiResponse->getHeader());
        $this->transactionsLogger->log(
            $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            $this->apiResponse->getHeader()
        );

        return $quoteTransfer;
    }

}
