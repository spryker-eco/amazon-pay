<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Yves\AmazonPay\AmazonPayFactory getFactory()
 */
class WidgetController extends AbstractController
{
    public const AMAZON_PAY_CONFIG = 'amazonpayConfig';
    public const LOGOUT = 'logout';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function payButtonAction(Request $request)
    {
        $response = $this->executePayButtonAction($request);

        return $this->view($response, [], '@AmazonPay/views/pay-button/pay-button.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executePayButtonAction(Request $request)
    {
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
     * @return \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface
     */
    protected function getAmazonPayConfig()
    {
        return $this->getFactory()->createAmazonPayConfig();
    }
}
