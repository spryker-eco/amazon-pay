<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayResponseTransfer;

abstract class AbstractCaptureOrderConverter extends AbstractResponseParserConverter
{
    const CAPTURE_DETAILS = 'CaptureDetails';

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface $captureDetailsConverter
     */
    protected $captureDetailsConverter;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface $captureDetailsConverter
     */
    public function __construct(ArrayConverterInterface $captureDetailsConverter)
    {
        $this->captureDetailsConverter = $captureDetailsConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseTransfer $responseTransfer
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function setBody(AmazonpayResponseTransfer $responseTransfer, array $response)
    {
        $responseTransfer->setCaptureDetails(
            $this->captureDetailsConverter->convert(
                $this->extractResult($response)[static::CAPTURE_DETAILS]
            )
        );

        return parent::setBody($responseTransfer, $response);
    }
}
