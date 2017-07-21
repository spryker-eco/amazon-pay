<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

class CloseOrderAdapter extends AbstractAdapter
{

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCloseOrderResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->closeOrderReference([
            AbstractAdapter::AMAZON_ORDER_REFERENCE_ID => $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId(),
        ]);

        return $this->converter->convert($result);
    }

}
