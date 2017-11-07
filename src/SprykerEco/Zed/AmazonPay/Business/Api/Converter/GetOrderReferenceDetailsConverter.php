<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\AmazonpayResponseTransfer;

class GetOrderReferenceDetailsConverter extends AbstractResponseParserConverter
{
    const ORDER_REFERENCE_DETAILS = 'OrderReferenceDetails';
    const ORDER_REFERENCE_STATUS = 'OrderReferenceStatus';
    const STATE = 'State';
    const RELEASE_ENVIRONMENT = 'ReleaseEnvironment';
    const SANDBOX = 'Sandbox';
    const BILLING_ADDRESS = 'BillingAddress';
    const PHYSICAL_ADDRESS = 'PhysicalAddress';

    /**
     * @return string
     */
    protected function getResponseType()
    {
        return 'GetOrderReferenceDetailsResult';
    }

    /**
     * @param array $response
     *
     * @return array
     */
    protected function extractOrderReferenceStatus(array $response)
    {
        return $this->extractResult($response)[static::ORDER_REFERENCE_DETAILS][static::ORDER_REFERENCE_STATUS][static::STATE];
    }

    /**
     * @param array $response
     *
     * @return int
     */
    protected function extractIsSandbox(array $response)
    {
        return ($this->extractResult($response)[static::ORDER_REFERENCE_DETAILS][static::RELEASE_ENVIRONMENT] === static::SANDBOX) ? 1 : 0;
    }

    /**
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function extractBillingAddress(array $response)
    {
        $address = new AddressTransfer();

        if (!$this->isSuccess($response)) {
            return $address;
        }

        $result = $this->extractResult($response);

        $aResponseAddress =
            $result[static::ORDER_REFERENCE_DETAILS][static::BILLING_ADDRESS][static::PHYSICAL_ADDRESS];

        return $this->convertAddressToTransfer($aResponseAddress);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseTransfer $responseTransfer
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function setBody(AmazonpayResponseTransfer $responseTransfer, array $response)
    {
        $responseTransfer->setOrderReferenceStatus(
            $this->convertStatusToTransfer(
                $this->extractResult($response)[static::ORDER_REFERENCE_DETAILS][static::ORDER_REFERENCE_STATUS]
            )
        );
        $responseTransfer->setIsSandbox($this->extractIsSandbox($response));
        $responseTransfer->setShippingAddress($this->extractShippingAddress($response));
        $responseTransfer->setBillingAddress($this->extractBillingAddress($response));

        return parent::setBody($responseTransfer, $response);
    }
}
