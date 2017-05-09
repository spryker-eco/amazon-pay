<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\OrderTransfer;

class CancelOrderAdapter extends AbstractAdapter implements OrderAdapterInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCancelOrderResponseTransfer
     */
    public function call(OrderTransfer $orderTransfer)
    {
        $result = $this->client->cancelOrderReference([
            AbstractAdapter::AMAZON_ORDER_REFERENCE_ID => $orderTransfer->getAmazonpayPayment()->getOrderReferenceId(),
        ]);

        return $this->converter->convert($result);
    }

}
