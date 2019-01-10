<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter\Details;

use Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\AbstractArrayConverter;

class RefundDetailsConverter extends AbstractArrayConverter
{
    public const REFUND_AMOUNT = 'RefundAmount';
    public const REFUND_STATUS = 'RefundStatus';

    /**
     * @param array $refundDetailsData
     *
     * @return \Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer
     */
    public function convert(array $refundDetailsData)
    {
        $refundDetails = new AmazonpayRefundDetailsTransfer();
        $refundDetails->fromArray($refundDetailsData, true);

        $this->hydrateRefundAmount($refundDetailsData, $refundDetails);

        $this->hydrateStatus($refundDetailsData, $refundDetails);

        return $refundDetails;
    }

    /**
     * @param array $refundDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer $refundDetails
     *
     * @return void
     */
    protected function hydrateRefundAmount(array $refundDetailsData, AmazonpayRefundDetailsTransfer $refundDetails)
    {
        $refundDetails->setRefundAmount(
            $this->convertPriceToTransfer(
                $refundDetailsData[static::REFUND_AMOUNT]
            )
        );
    }

    /**
     * @param array $refundDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer $refundDetails
     *
     * @return void
     */
    protected function hydrateStatus(array $refundDetailsData, AmazonpayRefundDetailsTransfer $refundDetails)
    {
        if (!empty($refundDetailsData[static::REFUND_STATUS])) {
            $refundDetails->setRefundStatus(
                $this->convertStatusToTransfer($refundDetailsData[static::REFUND_STATUS])
            );
        }
    }
}
