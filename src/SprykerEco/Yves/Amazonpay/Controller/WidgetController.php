<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Amazonpay\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;

/**
 * @method \SprykerEco\Yves\Amazonpay\AmazonpayFactory getFactory()
 * @method \SprykerEco\Client\Amazonpay\AmazonpayClient getClient()
 */
class WidgetController extends AbstractController
{
    const AMAZONPAY_CONFIG = 'amazonpayConfig';
    const LOGOUT = 'logout';

    /**
     * @return array
     */
    public function payButtonAction()
    {
        $quote = $this->getFactory()->getQuoteClient()->getQuote();
        $logout = $quote->getAmazonpayPayment()
                   && $quote->getAmazonpayPayment()->getResponseHeader()
                   && !$quote->getAmazonpayPayment()->getResponseHeader()->getIsSuccess();

        return [
            self::AMAZONPAY_CONFIG => $this->getAmazonPayConfig(),
            self::LOGOUT => (int)$logout,
        ];
    }

    /**
     * @return array
     */
    public function checkoutWidgetAction()
    {
        return [
            self::AMAZONPAY_CONFIG => $this->getAmazonPayConfig(),
        ];
    }

    /**
     * @return array
     */
    public function walletWidgetAction()
    {
        return [
            self::AMAZONPAY_CONFIG => $this->getAmazonPayConfig(),
        ];
    }

    /**
     * @return \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface
     */
    protected function getAmazonPayConfig()
    {
        return $this->getFactory()->createAmazonpayConfig();
    }
}
