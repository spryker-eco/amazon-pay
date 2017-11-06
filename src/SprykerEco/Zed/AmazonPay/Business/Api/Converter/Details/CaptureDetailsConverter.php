<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter\Details;

use Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\AbstractArrayConverter;

class CaptureDetailsConverter extends AbstractArrayConverter
{
    const SELLER_CAPTURE_NOTE = 'SellerCaptureNote';
    const CREATION_TIMESTAMP = 'CreationTimestamp';
    const CAPTURE_STATUS = 'CaptureStatus';
    const CAPTURE_FEE = 'CaptureFee';
    const CAPTURE_AMOUNT = 'CaptureAmount';
    const CAPTURE_REFERENCE_ID = 'CaptureReferenceId';
    const AMAZON_CAPTURE_ID = 'AmazonCaptureId';
    const ID_LIST = 'IdList';

    /**
     * @param array $captureDetailsData
     *
     * @return \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer
     */
    public function convert(array $captureDetailsData)
    {
        $captureDetails = new AmazonpayCaptureDetailsTransfer();

        $this->convertCaptureDetails($captureDetailsData, $captureDetails);
        $this->convertGenericDetails($captureDetailsData, $captureDetails);

        return $captureDetails;
    }

    /**
     * @param array $captureDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer $captureDetails
     *
     * @return void
     */
    protected function convertGenericDetails(array $captureDetailsData, AmazonpayCaptureDetailsTransfer $captureDetails)
    {
        if (!empty($captureDetailsData[static::ID_LIST])) {
            $captureDetails->setIdList(array_values($captureDetailsData[static::ID_LIST])[0]);
        }

        if (!empty($captureDetailsData[static::SELLER_CAPTURE_NOTE])) {
            $captureDetails->setSellerCaptureNote($captureDetailsData[static::SELLER_CAPTURE_NOTE]);
        }

        if (!empty($captureDetailsData[static::CREATION_TIMESTAMP])) {
            $captureDetails->setCreationTimestamp($captureDetailsData[static::CREATION_TIMESTAMP]);
        }
    }

    /**
     * @param array $captureDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer $captureDetails
     *
     * @return void
     */
    protected function convertCaptureDetails(array $captureDetailsData, AmazonpayCaptureDetailsTransfer $captureDetails)
    {
        $captureDetails->setAmazonCaptureId($captureDetailsData[static::AMAZON_CAPTURE_ID]);
        $captureDetails->setCaptureReferenceId($captureDetailsData[static::CAPTURE_REFERENCE_ID]);

        if (!empty($captureDetailsData[static::CAPTURE_AMOUNT])) {
            $captureDetails->setCaptureAmount($this->convertPriceToTransfer($captureDetailsData[static::CAPTURE_AMOUNT]));
        }

        if (!empty($captureDetailsData[static::CAPTURE_FEE])) {
            $captureDetails->setCaptureFee($this->convertPriceToTransfer($captureDetailsData[static::CAPTURE_FEE]));
        }

        if (!empty($captureDetailsData[static::CAPTURE_STATUS])) {
            $captureDetails->setCaptureStatus($this->convertStatusToTransfer($captureDetailsData[static::CAPTURE_STATUS]));
        }
    }
}
