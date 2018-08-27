<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

/**
 * @method \SprykerEco\Yves\AmazonPay\AmazonPayFactory getFactory()
 */
class WidgetController extends AbstractController
{
    const ADDRESS_BOOK_MODE = 'addressBookMode';
    const AMAZON_PAY_CONFIG = 'amazonpayConfig';
    const LOGOUT = 'logout';
    const QUOTE_TRANSFER = 'quoteTransfer';

    /**
     * @return array
     */
    public function payButtonAction()
    {
        return [
            static::AMAZON_PAY_CONFIG => $this->getAmazonPayConfig(),
            static::LOGOUT => (int)$this->isLogout(),
        ];
    }

    /**
     * @return bool
     */
    protected function isLogout()
    {
        $quote = $this->getFactory()->getQuoteClient()->getQuote();

        return $quote->getAmazonpayPayment()
            && $quote->getAmazonpayPayment()->getResponseHeader()
            && !$quote->getAmazonpayPayment()->getResponseHeader()->getIsSuccess();
    }

    /**
     * @return array
     */
    public function checkoutWidgetAction()
    {
        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        return [
            static::QUOTE_TRANSFER => $this->getAmazonPaymentOrderReferenceId($quoteTransfer),
            static::AMAZON_PAY_CONFIG => $this->getAmazonPayConfig(),
            static::ADDRESS_BOOK_MODE => $this->isAmazonPaymentInvalid($quoteTransfer) ? AmazonPayConfig::DISPLAY_MODE_READONLY : null,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return null|string
     */
    protected function getAmazonPaymentOrderReferenceId(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getAmazonpayPayment() !== null && $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId() !== null) {
            return $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId();
        }

        return null;
    }

    /**
     * @return array
     */
    public function walletWidgetAction()
    {
        return [
            static::AMAZON_PAY_CONFIG => $this->getAmazonPayConfig(),
        ];
    }

    /**
     * @return \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface
     */
    protected function getAmazonPayConfig()
    {
        return $this->getFactory()->createAmazonPayConfig();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isAmazonPaymentInvalid(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getAmazonpayPayment()->getResponseHeader() !== null
            && $quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getIsInvalidPaymentMethod()) {
            return true;
        }

        return false;
    }
}
