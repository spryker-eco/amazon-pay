<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayResponseTransfer;
use PayWithAmazon\ResponseInterface;

abstract class AbstractCaptureOrderConverter extends AbstractResponseParserConverter
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $captureDetailsConverter
     */
    protected $captureDetailsConverter;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $captureDetailsConverter
     */
    public function __construct(ArrayConverterInterface $captureDetailsConverter)
    {
        $this->captureDetailsConverter = $captureDetailsConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseTransfer $responseTransfer
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function setBody(AmazonpayResponseTransfer $responseTransfer, ResponseInterface $responseParser)
    {
        $responseTransfer->setCaptureDetails(
            $this->captureDetailsConverter->convert(
                $this->extractResult($responseParser)['CaptureDetails']
            )
        );

        return parent::setBody($responseTransfer, $responseParser);
    }

}
