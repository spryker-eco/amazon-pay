<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Amazonpay\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Yves\Amazonpay\AmazonpayFactory getFactory()
 * @method \Spryker\Client\Amazonpay\AmazonpayClient getClient()
 */
class WidgetController extends AbstractController
{

    /**
     * @return array
     */
    public function payButtonAction()
    {
        $quote = $this->getFactory()->getQuoteClient()->getQuote();
        $logout = $quote->getAmazonpayPayment()
                   && $quote->getAmazonpayPayment()->getAuthorizationDetails()
                   && $quote->getAmazonpayPayment()->getAuthorizationDetails()->getAuthorizationStatus()->getIsDeclined();

        return [
            'amazonpayConfig' => $this->getFactory()->getConfig(),
            'logout' => (int)$logout,
        ];
    }

    /**
     * @return array
     */
    public function checkoutWidgetAction()
    {
        return [
            'amazonpayConfig' => $this->getFactory()->getConfig(),
        ];
    }

    /**
     * @return array
     */
    public function walletWidgetAction()
    {
        return [
            'amazonpayConfig' => $this->getFactory()->getConfig(),
        ];
    }

}
