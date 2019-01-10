<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerEco\Zed\AmazonPay\Business\AmazonPayFacadeInterface getFacade()
 * @method \SprykerEco\Zed\AmazonPay\Communication\AmazonPayCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface getQueryContainer()
 */
class AmazonPayConfirmOrderPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        return $this->getFacade()
            ->placeOrder($quoteTransfer, $checkoutResponseTransfer);
    }
}
