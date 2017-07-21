<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

class GetOrderRefundDetailsAdapter extends AbstractAdapter
{

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayGetOrderReferenceDetailsResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->getRefundDetails([
            static::AMAZON_REFUND_ID => $amazonpayCallTransfer
                ->getAmazonpayPayment()
                ->getRefundDetails()
                ->getAmazonRefundId(),
        ]);

        return $this->converter->convert($result);
    }

}
