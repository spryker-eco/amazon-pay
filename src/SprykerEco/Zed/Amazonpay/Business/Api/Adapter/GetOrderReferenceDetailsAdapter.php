<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\QuoteTransfer;

class GetOrderReferenceDetailsAdapter extends AbstractAdapter implements QuoteAdapterInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayGetOrderReferenceDetailsResponseTransfer
     */
    public function call(QuoteTransfer $quoteTransfer)
    {
        $result = $this->client->getOrderReferenceDetails([
            AbstractAdapter::AMAZON_ORDER_REFERENCE_ID =>
                $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            AbstractAdapter::AMAZON_ADDRESS_CONSENT_TOKEN =>
                $quoteTransfer->getAmazonpayPayment()->getAddressConsentToken(),
        ]);

        return $this->converter->convert($result);
    }

}
