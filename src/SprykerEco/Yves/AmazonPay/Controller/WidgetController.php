<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Yves\AmazonPay\AmazonPayFactory getFactory()
 */
class WidgetController extends AbstractController
{
    const ADDRESS_BOOK_MODE = 'addressBookMode';
    const AMAZON_PAY_CONFIG = 'amazonpayConfig';
    const LOGOUT = 'logout';
    const ORDER_REFERENCE = 'orderReferenceId';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     */
    public function payButtonAction(Request $request)
    {
        if (!$request->isSecure()) {
            return new Response('');
        }

        $isLogout = $this->isLogout();

        if ($isLogout) {
            $this->resetAmazonPaymentInQuote();
        }

        return [
            static::AMAZON_PAY_CONFIG => $this->getAmazonPayConfig(),
            static::LOGOUT => $isLogout,
        ];
    }

    /**
     * @return void
     */
    protected function resetAmazonPaymentInQuote()
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        $quoteTransfer->setAmazonpayPayment(null);
        $this->getFactory()
            ->getQuoteClient()
            ->setQuote($quoteTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     */
    public function checkoutWidgetAction(Request $request)
    {
        if (!$request->isSecure()) {
            return new Response('');
        }

        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        $data = [
            static::AMAZON_PAY_CONFIG => $this->getAmazonPayConfig(),
        ];

        if ($this->isAmazonPaymentInvalid($quoteTransfer)) {
            $data[static::ORDER_REFERENCE] = $this->getAmazonPaymentOrderReferenceId($quoteTransfer);
            $data[static::ADDRESS_BOOK_MODE] = AmazonPayConfig::DISPLAY_MODE_READONLY;
        }

        return $data;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     */
    public function walletWidgetAction(Request $request)
    {
        if (!$request->isSecure()) {
            return new Response('');
        }

        return [
            static::AMAZON_PAY_CONFIG => $this->getAmazonPayConfig(),
        ];
    }

    /**
     * @return int
     */
    protected function isLogout()
    {
        $quote = $this->getFactory()->getQuoteClient()->getQuote();

        $isLogout = $quote->getAmazonpayPayment()
            && $quote->getAmazonpayPayment()->getResponseHeader()
            && !$quote->getAmazonpayPayment()->getResponseHeader()->getIsSuccess();

        if ($isLogout) {
            return 1;
        }

        return 0;
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
