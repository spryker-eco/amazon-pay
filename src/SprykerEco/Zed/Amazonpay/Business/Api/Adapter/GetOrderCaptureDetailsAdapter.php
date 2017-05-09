<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\OrderTransfer;

class GetOrderCaptureDetailsAdapter extends AbstractAdapter implements OrderAdapterInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayGetOrderReferenceDetailsResponseTransfer
     */
    public function call(OrderTransfer $orderTransfer)
    {
        $result = $this->client->getCaptureDetails([
            static::AMAZON_CAPTURE_ID =>
                $orderTransfer->getAmazonpayPayment()
                    ->getCaptureDetails()
                    ->getAmazonCaptureId(),
        ]);

        return $this->converter->convert($result);
    }

}
