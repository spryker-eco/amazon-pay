<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

class GetOrderAuthorizationDetailsAdapter extends AbstractAdapter
{
    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->getAuthorizationDetails([
            static::AMAZON_AUTHORIZATION_ID =>
                $amazonpayCallTransfer
                    ->getAmazonpayPayment()
                    ->getAuthorizationDetails()
                    ->getAmazonAuthorizationId(),
        ]);

        return $this->converter->convert($result);
    }
}
