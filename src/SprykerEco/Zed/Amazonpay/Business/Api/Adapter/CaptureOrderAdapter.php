<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\OrderTransfer;

class CaptureOrderAdapter extends AbstractAdapter implements OrderAdapterInterface
{

    const CAPTURE_REFERENCE_ID = 'capture_reference_id';
    const CAPTURE_AMOUNT = 'capture_amount';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayAuthorizeOrderResponseTransfer
     */
    public function call(OrderTransfer $orderTransfer)
    {
        $result = $this->client->capture([
            static::AMAZON_AUTHORIZATION_ID =>
                $orderTransfer->getAmazonpayPayment()
                    ->getAuthorizationDetails()
                    ->getAmazonAuthorizationId(),
            static::CAPTURE_REFERENCE_ID =>
                $orderTransfer->getAmazonpayPayment()
                    ->getCaptureDetails()
                    ->getCaptureReferenceId(),
            static::CAPTURE_AMOUNT => $this->getAmount($orderTransfer),
        ]);

        return $this->converter->convert($result);
    }

}
