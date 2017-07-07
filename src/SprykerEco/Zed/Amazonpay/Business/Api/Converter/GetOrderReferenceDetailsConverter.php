<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\AmazonpayGetOrderReferenceDetailsResponseTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use PayWithAmazon\ResponseInterface;

class GetOrderReferenceDetailsConverter extends AbstractResponseParserConverter
{

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
        return $this->extractResult($responseParser)['OrderReferenceDetails']['OrderReferenceStatus']['State'];
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return boolean
     */
    protected function extractIsSandbox(ResponseInterface $responseParser)
    {
        return ($this->extractResult($responseParser)['OrderReferenceDetails']['ReleaseEnvironment'] === 'Sandbox');
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
            $response['OrderReferenceDetails']['BillingAddress']['PhysicalAddress'];

        return $this->convertAddressToTransfer($aResponseAddress);
    }

    /**
     * @return \Generated\Shared\Transfer\AmazonpayGetOrderReferenceDetailsResponseTransfer
     */
    protected function createTransferObject()
    {
        return new AmazonpayGetOrderReferenceDetailsResponseTransfer();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function setBody(
        AbstractTransfer $responseTransfer,
        ResponseInterface $responseParser
    ) {
        $responseTransfer->setOrderReferenceStatus(
            $this->convertStatusToTransfer(
                $this->extractResult($responseParser)['OrderReferenceDetails']['OrderReferenceStatus']
            )
        );
        $responseTransfer->setIsSandbox($this->extractIsSandbox($responseParser));
        $responseTransfer->setShippingAddress($this->extractShippingAddress($responseParser));
        $responseTransfer->setBillingAddress($this->extractBillingAddress($responseParser));

        return parent::setBody($responseTransfer, $responseParser);
    }

}
