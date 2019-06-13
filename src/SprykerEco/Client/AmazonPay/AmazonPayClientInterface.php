<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\AmazonPay;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface AmazonPayClientInterface
{
    /**
     * Specification:
     *  - Set initial order data to quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleCartWithAmazonPay(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Handles address selection
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addSelectedAddressToQuote(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Handles shipment method selection
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addSelectedShipmentMethodToQuote(QuoteTransfer $quoteTransfer);

    /**
     * Specification
     * -
     *
     * @api
     *
     * @param AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return AmazonpayCallTransfer
     */
    public function setOrderDetailsAndConfirmation(AmazonpayCallTransfer $amazonpayCallTransfer): AmazonpayCallTransfer;
}
