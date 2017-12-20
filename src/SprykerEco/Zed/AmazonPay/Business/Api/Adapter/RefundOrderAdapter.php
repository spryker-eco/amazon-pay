<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

class RefundOrderAdapter extends AbstractAdapter
{
    const REFUND_REFERENCE_ID = 'refund_reference_id';
    const REFUND_AMOUNT = 'refund_amount';

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $refundAmount = $this->moneyFacade->convertIntegerToDecimal(
            $amazonpayCallTransfer->getRequestedAmount()
        );

        $result = $this->client->refund([
            static::AMAZON_ORDER_REFERENCE_ID => $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            static::AMAZON_CAPTURE_ID =>
                $amazonpayCallTransfer
                    ->getAmazonpayPayment()
                    ->getCaptureDetails()
                    ->getAmazonCaptureId(),
            static::REFUND_REFERENCE_ID =>
                $amazonpayCallTransfer
                    ->getAmazonpayPayment()
                    ->getRefundDetails()
                    ->getRefundReferenceId(),
            static::REFUND_AMOUNT => $refundAmount,
        ]);

        return $this->converter->convert($result);
    }
}
