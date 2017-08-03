<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

abstract class QuoteUpdaterAbstract implements QuoteUpdaterInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    protected $executionAdapter;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    protected function convertQuoteTransferToAmazonPayTransfer(QuoteTransfer $quoteTransfer)
    {
        $amazonpayCallTransfer = new AmazonpayCallTransfer();
        $amazonpayCallTransfer->setAmazonpayPayment($quoteTransfer->getAmazonpayPayment())
            ->setRequestedAmount($quoteTransfer->getTotals()->getGrandTotal());

        return $amazonpayCallTransfer;
    }

}
