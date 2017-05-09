<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter\Details;

use Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\AbstractArrayConverter;

class CaptureDetailsConverter extends AbstractArrayConverter
{

    const CAPTURE_STATUS_DECLINED = 'Declined';
    const CAPTURE_STATUS_PENDING = 'Pending';
    const CAPTURE_STATUS_COMPLETED = 'Completed';

    /**
     * @param array $captureDetailsData
     *
     * @return \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer
     */
    public function convert(array $captureDetailsData)
    {
        $captureDetails = new AmazonpayCaptureDetailsTransfer();
        $captureDetails->setAmazonCaptureId($captureDetailsData['AmazonCaptureId']);
        $captureDetails->setCaptureReferenceId($captureDetailsData['CaptureReferenceId']);

        if (!empty($captureDetailsData['CaptureAmount'])) {
            $captureDetails->setCaptureAmount($this->convertPriceToTransfer($captureDetailsData['CaptureAmount']));
        }

        if (!empty($captureDetailsData['CaptureFee'])) {
            $captureDetails->setCaptureFee($this->convertPriceToTransfer($captureDetailsData['CaptureFee']));
        }

        if (!empty($captureDetailsData['CaptureStatus'])) {
            $captureDetails->setCaptureStatus($this->convertStatusToTransfer($captureDetailsData['CaptureStatus']));
        }

        if (!empty($captureDetailsData['IdList'])) {
            $captureDetails->setIdList(array_values($captureDetailsData['IdList'])[0]);
        }

        if (!empty($captureDetailsData['SellerCaptureNote'])) {
            $captureDetails->setSellerCaptureNote($captureDetailsData['SellerCaptureNote']);
        }

        if (!empty($captureDetailsData['CreationTimestamp'])) {
            $captureDetails->setCreationTimestamp($captureDetailsData['CreationTimestamp']);
        }

        return $captureDetails;
    }

}
