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
        if (!empty($captureDetailsData[self::ID_LIST])) {
            $captureDetails->setIdList(array_values($captureDetailsData[self::ID_LIST])[0]);
        }

        if (!empty($captureDetailsData[self::SELLER_CAPTURE_NOTE])) {
            $captureDetails->setSellerCaptureNote($captureDetailsData[self::SELLER_CAPTURE_NOTE]);
        }

        if (!empty($captureDetailsData[self::CREATION_TIMESTAMP])) {
            $captureDetails->setCreationTimestamp($captureDetailsData[self::CREATION_TIMESTAMP]);
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
        $captureDetails->setAmazonCaptureId($captureDetailsData[self::AMAZON_CAPTURE_ID]);
        $captureDetails->setCaptureReferenceId($captureDetailsData[self::CAPTURE_REFERENCE_ID]);

        if (!empty($captureDetailsData[self::CAPTURE_AMOUNT])) {
            $captureDetails->setCaptureAmount($this->convertPriceToTransfer($captureDetailsData[self::CAPTURE_AMOUNT]));
        }

        if (!empty($captureDetailsData[self::CAPTURE_FEE])) {
            $captureDetails->setCaptureFee($this->convertPriceToTransfer($captureDetailsData[self::CAPTURE_FEE]));
        }

        if (!empty($captureDetailsData[self::CAPTURE_STATUS])) {
            $captureDetails->setCaptureStatus($this->convertStatusToTransfer($captureDetailsData[self::CAPTURE_STATUS]));
        }
    }
}
