<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Converter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class AmazonPayConverter implements AmazonPayConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function mapToAmazonpayCallTransfer(QuoteTransfer $quoteTransfer)
    {
        $amazonpayCallTransfer = new AmazonpayCallTransfer();
        $amazonpayCallTransfer->fromArray($quoteTransfer->toArray(), true);
        $amazonpayCallTransfer->setRequestedAmount($quoteTransfer->getTotals()->getGrandTotal());
        $quoteTransfer = $this->addShippingAddressToItemsInQuote($quoteTransfer);
        $amazonpayCallTransfer->setItems($quoteTransfer->getItems());

        return $amazonpayCallTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addShippingAddressToItemsInQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $item) {
            $shipmentTransfer = $quoteTransfer->getShipment();
            $shipmentTransfer->setShippingAddress($quoteTransfer->getShippingAddress());
            $item->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }
}
