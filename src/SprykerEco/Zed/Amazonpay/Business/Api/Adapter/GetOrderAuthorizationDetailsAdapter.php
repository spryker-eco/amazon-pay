<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\OrderTransfer;

class GetOrderAuthorizationDetailsAdapter extends AbstractAdapter implements OrderAdapterInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayGetOrderReferenceDetailsResponseTransfer
     */
    public function call(OrderTransfer $orderTransfer)
    {
        $result = $this->client->getAuthorizationDetails([
            static::AMAZON_AUTHORIZATION_ID =>
                $orderTransfer->getAmazonpayPayment()
                    ->getAuthorizationDetails()
                    ->getAmazonAuthorizationId(),
        ]);

        return $this->converter->convert($result);
    }

}
