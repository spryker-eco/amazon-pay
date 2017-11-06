<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;

/**
 * @method \SprykerEco\Yves\AmazonPay\AmazonPayFactory getFactory()
 * @method \SprykerEco\Client\AmazonPay\AmazonPayClient getClient()
 */
class WidgetController extends AbstractController
{
    const AMAZON_PAY_CONFIG = 'amazonpayConfig';
    const LOGOUT = 'logout';

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
        return [
            static::AMAZON_PAY_CONFIG => $this->getAmazonPayConfig(),
        ];
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
}
