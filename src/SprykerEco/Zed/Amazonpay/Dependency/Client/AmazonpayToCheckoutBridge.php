<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Checkout\CheckoutClientInterface;

class AmazonpayToCheckoutBridge implements AmazonpayToCheckoutInterface
{

    /**
     * @var \Spryker\Client\Checkout\CheckoutClient
     */
    protected $checkoutClient;

    /**
     * @param \Spryker\Client\Checkout\CheckoutClientInterface $checkoutClient
     */
    public function __construct(CheckoutClientInterface $checkoutClient)
    {
        $this->checkoutClient = $checkoutClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer)
    {
        return $this->checkoutClient->placeOrder($quoteTransfer);
    }
}
