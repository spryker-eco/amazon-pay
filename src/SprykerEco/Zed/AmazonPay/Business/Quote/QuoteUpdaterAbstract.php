<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Quote;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface;

abstract class QuoteUpdaterAbstract implements QuoteUpdaterInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    protected $executionAdapter;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface $executionAdapter
     */
    public function __construct(
        CallAdapterInterface $executionAdapter
    ) {
        $this->executionAdapter = $executionAdapter;
    }

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
