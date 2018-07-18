<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

class ConfirmQuoteReferenceAdapter extends AbstractAdapter
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->confirmOrderReference([
            AbstractAdapter::AMAZON_ORDER_REFERENCE_ID => $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            AbstractAdapter::AMAZON_AMOUNT => $this->getAmount($amazonpayCallTransfer),
        ]);

        return $this->converter->convert($result);
    }
}
