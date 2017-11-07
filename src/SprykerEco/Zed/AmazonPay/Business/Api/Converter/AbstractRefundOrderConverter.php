<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayResponseTransfer;

abstract class AbstractRefundOrderConverter extends AbstractResponseParserConverter
{
    const REFUND_DETAILS = 'RefundDetails';

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface $refundDetailsConverter
     */
    protected $refundDetailsConverter;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface $refundDetailsConverter
     */
    public function __construct(ArrayConverterInterface $refundDetailsConverter)
    {
        $this->refundDetailsConverter = $refundDetailsConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseTransfer $responseTransfer
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function setBody(AmazonpayResponseTransfer $responseTransfer, array $response)
    {
        $responseTransfer->setRefundDetails(
            $this->refundDetailsConverter->convert($this->extractResult($response)[static::REFUND_DETAILS])
        );

        return parent::setBody($responseTransfer, $response);
    }
}
