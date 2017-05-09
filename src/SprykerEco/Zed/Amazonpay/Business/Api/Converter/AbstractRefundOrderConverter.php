<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayRefundOrderResponseTransfer;
use PayWithAmazon\ResponseInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

abstract class AbstractRefundOrderConverter extends AbstractResponseParserConverter
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $refundDetailsConverter
     */
    protected $refundDetailsConverter;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $refundDetailsConverter
     */
    public function __construct(ArrayConverterInterface $refundDetailsConverter)
    {
        $this->refundDetailsConverter = $refundDetailsConverter;
    }

    /**
     * @return \Generated\Shared\Transfer\AmazonpayRefundOrderResponseTransfer
     */
    protected function createTransferObject()
    {
        return new AmazonpayRefundOrderResponseTransfer();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function setBody(AbstractTransfer $responseTransfer, ResponseInterface $responseParser)
    {
        $responseTransfer->setRefundDetails(
            $this->refundDetailsConverter->convert($this->extractResult($responseParser)['RefundDetails'])
        );

        return parent::setBody($responseTransfer, $responseParser);
    }

}
