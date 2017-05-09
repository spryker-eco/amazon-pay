<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Amazonpay;

use Generated\Shared\Transfer\QuoteTransfer;

interface AmazonpayClientInterface
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
    public function handleCartWithAmazonpay(QuoteTransfer $quoteTransfer);

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
     * Specification:
     * - Places an order
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function confirmPurchase(QuoteTransfer $quoteTransfer);

}
