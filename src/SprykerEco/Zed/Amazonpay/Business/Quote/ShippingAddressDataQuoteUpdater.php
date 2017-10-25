<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;

class ShippingAddressDataQuoteUpdater extends QuoteUpdaterAbstract
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function update(QuoteTransfer $quoteTransfer)
    {
        $amazonCallTransfer = $this->convertQuoteTransferToAmazonPayTransfer($quoteTransfer);
        $apiResponse = $this->executionAdapter->call($amazonCallTransfer);

        if ($apiResponse->getHeader()->getIsSuccess()) {
            $quoteTransfer->setShippingAddress($apiResponse->getShippingAddress());
        }

        return $quoteTransfer;
    }
}
