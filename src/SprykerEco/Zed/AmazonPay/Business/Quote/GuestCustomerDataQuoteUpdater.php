<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Quote;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class GuestCustomerDataQuoteUpdater extends QuoteUpdaterAbstract
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function update(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getCustomer() !== null) {
            return $quoteTransfer;
        }

        if ($quoteTransfer->getAmazonpayPayment() !== null) {
            return $quoteTransfer;
        }

        $amazonCallTransfer = $this->convertQuoteTransferToAmazonPayTransfer($quoteTransfer);

        $responseTransfer = $this->executionAdapter->call($amazonCallTransfer);

        $this->updateCustomer($quoteTransfer, $responseTransfer->getCustomer());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return void
     */
    protected function updateCustomer(QuoteTransfer $quoteTransfer, CustomerTransfer $customer)
    {
        if (!$quoteTransfer->getCustomer()) {
            $quoteTransfer->setCustomer(new CustomerTransfer());
        }

        $quoteTransfer->getCustomer()->fromArray($customer->modifiedToArray());

        if ($quoteTransfer->getCustomer()->getIdCustomer()) {
            $quoteTransfer->getCustomer()->setIsGuest(false);
        }
    }
}
