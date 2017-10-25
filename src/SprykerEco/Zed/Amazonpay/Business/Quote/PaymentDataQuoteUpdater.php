<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

class PaymentDataQuoteUpdater implements QuoteUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function update(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentMethod(AmazonpayConfig::PROVIDER_NAME);
        $paymentTransfer->setPaymentProvider(AmazonpayConfig::PROVIDER_NAME);
        $paymentTransfer->setPaymentSelection(AmazonpayConfig::PROVIDER_NAME);
        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }
}
