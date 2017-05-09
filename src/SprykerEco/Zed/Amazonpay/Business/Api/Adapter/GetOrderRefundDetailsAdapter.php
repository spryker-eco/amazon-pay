<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\OrderTransfer;

class GetOrderRefundDetailsAdapter extends AbstractAdapter implements OrderAdapterInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayGetOrderReferenceDetailsResponseTransfer
     */
    public function call(OrderTransfer $orderTransfer)
    {
        $result = $this->client->getRefundDetails([
            static::AMAZON_REFUND_ID => $orderTransfer
                ->getAmazonpayPayment()
                ->getRefundDetails()
                ->getAmazonRefundId(),
        ]);

        return $this->converter->convert($result);
    }

}
