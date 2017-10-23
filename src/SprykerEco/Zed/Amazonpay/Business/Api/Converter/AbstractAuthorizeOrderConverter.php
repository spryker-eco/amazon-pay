<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayResponseTransfer;
use PayWithAmazon\ResponseInterface;

abstract class AbstractAuthorizeOrderConverter extends AbstractResponseParserConverter
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $authDetailsConverter
     */
    protected $authDetailsConverter;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $authDetailsConverter
     */
    public function __construct(ArrayConverterInterface $authDetailsConverter)
    {
        $this->authDetailsConverter = $authDetailsConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseTransfer $responseTransfer
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function setBody(AmazonpayResponseTransfer $responseTransfer, ResponseInterface $responseParser)
    {
        $responseTransfer->setAuthorizationDetails(
            $this->authDetailsConverter->convert($this->extractResult($responseParser)['AuthorizationDetails'])
        );

        return parent::setBody($responseTransfer, $responseParser);
    }

}
