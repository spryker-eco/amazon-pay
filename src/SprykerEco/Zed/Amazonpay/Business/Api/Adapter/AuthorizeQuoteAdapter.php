<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\QuoteTransfer;

class AuthorizeQuoteAdapter extends AbstractAuthorizeAdapter implements QuoteAdapterInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayAuthorizeOrderResponseTransfer
     */
    public function call(QuoteTransfer $quoteTransfer)
    {
        $result = $this->client->authorize(
            $this->buildRequestArray($quoteTransfer->getAmazonpayPayment(), $this->getAmount($quoteTransfer))
        );

        return $this->converter->convert($result);
    }

}
