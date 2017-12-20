<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

class SetOrderReferenceDetailsAdapter extends AbstractAdapter
{
    const SELLER_ORDER_ID = 'seller_order_id';

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->setOrderReferenceDetails([
            static::AMAZON_ORDER_REFERENCE_ID => $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            static::AMAZON_ADDRESS_CONSENT_TOKEN => $amazonpayCallTransfer->getAmazonpayPayment()->getAddressConsentToken(),
            static::AMAZON_AMOUNT => $this->getAmount($amazonpayCallTransfer),
            static::SELLER_ORDER_ID => $amazonpayCallTransfer->getAmazonpayPayment()->getSellerOrderId(),
        ]);

        return $this->converter->convert($result);
    }
}
