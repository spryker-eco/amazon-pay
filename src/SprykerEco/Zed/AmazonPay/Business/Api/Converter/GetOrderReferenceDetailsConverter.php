<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\AmazonpayResponseTransfer;
use PayWithAmazon\ResponseInterface;

class GetOrderReferenceDetailsConverter extends AbstractResponseParserConverter
{
    const ORDER_REFERENCE_DETAILS = 'OrderReferenceDetails';
    const ORDER_REFERENCE_STATUS = 'OrderReferenceStatus';

    /**
     * @return string
     */
    protected function getResponseType()
    {
        return 'GetOrderReferenceDetailsResult';
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return array
     */
    protected function extractOrderReferenceStatus(ResponseInterface $responseParser)
    {
        return $this->extractResult($responseParser)[static::ORDER_REFERENCE_DETAILS][static::ORDER_REFERENCE_STATUS]['State'];
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return int
     */
    protected function extractIsSandbox(ResponseInterface $responseParser)
    {
        return ($this->extractResult($responseParser)[static::ORDER_REFERENCE_DETAILS]['ReleaseEnvironment'] === 'Sandbox') ? 1 : 0;
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function extractBillingAddress(ResponseInterface $responseParser)
    {
        $address = new AddressTransfer();

        if (!$this->isSuccess($responseParser)) {
            return $address;
        }

        $response = $this->extractResult($responseParser);

        $aResponseAddress =
            $response[static::ORDER_REFERENCE_DETAILS]['BillingAddress']['PhysicalAddress'];

        return $this->convertAddressToTransfer($aResponseAddress);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseTransfer $responseTransfer
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function setBody(AmazonpayResponseTransfer $responseTransfer, ResponseInterface $responseParser)
    {
        $responseTransfer->setOrderReferenceStatus(
            $this->convertStatusToTransfer(
                $this->extractResult($responseParser)[static::ORDER_REFERENCE_DETAILS][static::ORDER_REFERENCE_STATUS]
            )
        );
        $responseTransfer->setIsSandbox($this->extractIsSandbox($responseParser));
        $responseTransfer->setShippingAddress($this->extractShippingAddress($responseParser));
        $responseTransfer->setBillingAddress($this->extractBillingAddress($responseParser));

        return parent::setBody($responseTransfer, $responseParser);
    }
}
