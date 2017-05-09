<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\QuoteTransfer;

class CancelPreOrderAdapter extends AbstractAdapter implements QuoteAdapterInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCancelOrderResponseTransfer
     */
    public function call(QuoteTransfer $quoteTransfer)
    {
        $result = $this->client->cancelOrderReference([
            AbstractAdapter::AMAZON_ORDER_REFERENCE_ID => $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId(),
        ]);

        return $this->converter->convert($result);
    }

}
