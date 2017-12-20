<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

class GetOrderReferenceDetailsAdapter extends AbstractAdapter
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->getOrderReferenceDetails([
            AbstractAdapter::AMAZON_ORDER_REFERENCE_ID =>
                $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            AbstractAdapter::AMAZON_ADDRESS_CONSENT_TOKEN =>
                $amazonpayCallTransfer->getAmazonpayPayment()->getAddressConsentToken(),
        ]);

        return $this->converter->convert($result);
    }
}
