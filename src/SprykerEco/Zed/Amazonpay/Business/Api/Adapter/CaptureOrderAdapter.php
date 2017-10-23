<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

class CaptureOrderAdapter extends AbstractAdapter
{
    const CAPTURE_REFERENCE_ID = 'capture_reference_id';
    const CAPTURE_AMOUNT = 'capture_amount';

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->capture([
            static::AMAZON_AUTHORIZATION_ID =>
                $amazonpayCallTransfer->getAmazonpayPayment()
                    ->getAuthorizationDetails()
                    ->getAmazonAuthorizationId(),
            static::CAPTURE_REFERENCE_ID =>
                $amazonpayCallTransfer->getAmazonpayPayment()
                    ->getCaptureDetails()
                    ->getCaptureReferenceId(),
            static::CAPTURE_AMOUNT => $this->getAmount($amazonpayCallTransfer),
        ]);

        return $this->converter->convert($result);
    }
}
