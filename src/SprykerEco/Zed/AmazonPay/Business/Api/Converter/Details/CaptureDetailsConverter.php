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
    const CAPTURE_STATUS = 'CaptureStatus';
    const CAPTURE_FEE = 'CaptureFee';
    const CAPTURE_AMOUNT = 'CaptureAmount';
    const ID_LIST = 'IdList';

    /**
     * @param array $captureDetailsData
     *
     * @return \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer
     */
    public function convert(array $captureDetailsData)
    {
        $captureDetailsTransfer = new AmazonpayCaptureDetailsTransfer();
        $captureDetailsTransfer->fromArray($captureDetailsData, true);

        $this->hydrateIdList($captureDetailsData, $captureDetailsTransfer);
        $this->hydrateCaptureStatus($captureDetailsData, $captureDetailsTransfer);
        $this->hydrateCaptureAmount($captureDetailsData, $captureDetailsTransfer);
        $this->hydrateCaptureFee($captureDetailsData, $captureDetailsTransfer);

        return $captureDetailsTransfer;
    }

    /**
     * @param array $captureDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer $captureDetailsTransfer
     *
     * @return void
     */
    protected function hydrateIdList(array $captureDetailsData, AmazonpayCaptureDetailsTransfer $captureDetailsTransfer)
    {
        if (!empty($captureDetailsData[static::ID_LIST])) {
            $captureDetailsTransfer->setIdList($this->getIdList($captureDetailsData));
        }
    }

    /**
     * @param array $captureDetailsData
     *
     * @return string
     */
    protected function getIdList(array $captureDetailsData)
    {
        return array_values($captureDetailsData[static::ID_LIST])[0];
    }

    /**
     * @param array $captureDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer $captureDetails
     *
     * @return void
     */
    protected function hydrateCaptureStatus(array $captureDetailsData, AmazonpayCaptureDetailsTransfer $captureDetails)
    {
        if (!empty($captureDetailsData[static::CAPTURE_STATUS])) {
            $captureDetails->setCaptureStatus($this->convertStatusToTransfer($captureDetailsData[static::CAPTURE_STATUS]));
        }
    }

    /**
     * @param array $captureDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer $captureDetails
     *
     * @return void
     */
    protected function hydrateCaptureAmount(array $captureDetailsData, AmazonpayCaptureDetailsTransfer $captureDetails)
    {
        if (!empty($captureDetailsData[static::CAPTURE_AMOUNT])) {
            $captureDetails->setCaptureAmount($this->convertPriceToTransfer($captureDetailsData[static::CAPTURE_AMOUNT]));
        }
    }

    /**
     * @param array $captureDetailsData
     * @param \Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer $captureDetails
     *
     * @return void
     */
    protected function hydrateCaptureFee(array $captureDetailsData, AmazonpayCaptureDetailsTransfer $captureDetails)
    {
        if (!empty($captureDetailsData[static::CAPTURE_FEE])) {
            $captureDetails->setCaptureFee($this->convertPriceToTransfer($captureDetailsData[static::CAPTURE_FEE]));
        }
    }
}
