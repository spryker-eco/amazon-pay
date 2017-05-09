<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter\Details;

use Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\AbstractArrayConverter;

class RefundDetailsConverter extends AbstractArrayConverter
{

    const CAPTURE_STATUS_DECLINED = 'Declined';
    const CAPTURE_STATUS_PENDING = 'Pending';
    const CAPTURE_STATUS_COMPLETED = 'Completed';

    /**
     * @param array $refundDetailsData
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function convert(array $refundDetailsData)
    {
        $refundDetails = new AmazonpayRefundDetailsTransfer();
        $refundDetails->setAmazonRefundId($refundDetailsData['AmazonRefundId']);
        $refundDetails->setRefundReferenceId($refundDetailsData['RefundReferenceId']);
        $refundDetails->setRefundAmount($this->convertPriceToTransfer(
            $refundDetailsData['RefundAmount']
        ));

        if (!empty($refundDetailsData['RefundStatus'])) {
            $refundDetails->setRefundStatus(
                $this->convertStatusToTransfer($refundDetailsData['RefundStatus'])
            );
        }

        if (!empty($refundDetailsData['SellerRefundNote'])) {
            $refundDetails->setRefundReferenceId($refundDetailsData['SellerRefundNote']);
        }

        return $refundDetails;
    }

}
