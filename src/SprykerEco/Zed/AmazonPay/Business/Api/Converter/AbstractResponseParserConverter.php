<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\AmazonpayResponseConstraintTransfer;
use Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer;
use Generated\Shared\Transfer\AmazonpayResponseTransfer;
use PayWithAmazon\ResponseInterface;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

abstract class AbstractResponseParserConverter extends AbstractConverter implements ResponseParserConverterInterface
{
    const STATUS_CODE_SUCCESS = 200;
    const ORDER_REFERENCE_DETAILS = 'OrderReferenceDetails';
    const CONSTRAINTS = 'Constraints';
    const DESCRIPTION = 'Description';
    const CONSTRAINT_ID = 'ConstraintID';
    const REQUEST_ID = 'RequestId';
    const ERROR = 'Error';
    const RESPONSE_METADATA = 'ResponseMetadata';
    const RESPONSE_STATUS = 'ResponseStatus';
    const MESSAGE = 'Message';
    const CODE = 'Code';
    const DESTINATION = 'Destination';
    const PHYSICAL_DESTINATION = 'PhysicalDestination';
    const NAME = 'Name';
    const CITY = 'City';
    const COUNTRY_CODE = 'CountryCode';
    const POSTAL_CODE = 'PostalCode';
    const ADDRESS_LINE_1 = 'AddressLine1';
    const ADDRESS_LINE_2 = 'AddressLine2';
    const ADDRESS_LINE_3 = 'AddressLine3';
    const DISTRICT = 'District';
    const STATE_OR_REGION = 'StateOrRegion';
    const PHONE = 'Phone';

    /**
     * @var string
     */
    protected $resultKeyName;

    /**
     * @return string
     */
    abstract protected function getResponseType();

    /**
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function createTransferObject()
    {
        return new AmazonpayResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseTransfer $responseTransfer
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function setBody(AmazonpayResponseTransfer $responseTransfer, array $response)
    {
        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseTransfer $responseTransfer
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function mapResponseDataToTransfer(AmazonpayResponseTransfer $responseTransfer, array $response)
    {
        $responseTransfer->setResponseHeader($this->extractHeader($response));

        if ($responseTransfer->getResponseHeader()->getIsSuccess()) {
            return $this->setBody($responseTransfer, $response);
        }

        return $responseTransfer;
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function convert(ResponseInterface $responseParser)
    {
        return $this->mapResponseDataToTransfer($this->createTransferObject(), $responseParser->toArray());
    }

    /**
     * @param array $response
     *
     * @return array
     */
    protected function extractMetadata(array $response)
    {
        return $response[static::RESPONSE_METADATA] ?? [];
    }

    /**
     * @param array $response
     *
     * @return int
     */
    protected function extractStatusCode(array $response)
    {
        return (int)$response[static::RESPONSE_STATUS];
    }

    /**
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer
     */
    protected function extractHeader(array $response)
    {
        $header = new AmazonpayResponseHeaderTransfer();
        $header->setIsSuccess($this->isSuccess($response));

        $statusCode = $this->extractStatusCode($response);
        $header->setStatusCode($statusCode);

        $this->extractRequest($header, $response);

        $constraints = $this->extractConstraints($response);
        $header->setConstraints($constraints);
        $this->extractErrorFromConstraints($header);

        return $header;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer $header
     * @param array $response
     *
     * @return void
     */
    protected function extractRequest(AmazonpayResponseHeaderTransfer $header, array $response)
    {
        $metadata = $this->extractMetadata($response);
        if (isset($metadata[static::REQUEST_ID])) {
            $header->setRequestId($metadata[static::REQUEST_ID]);

            if (!empty($response[static::ERROR])) {
                $this->updateHeaderWithError($header, $response);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer $header
     * @param array $response
     *
     * @return void
     */
    protected function updateHeaderWithError(AmazonpayResponseHeaderTransfer $header, array $response)
    {
        $header->setErrorMessage($response[static::ERROR][static::MESSAGE]);
        $header->setErrorCode($response[static::ERROR][static::CODE]);
        $header->setRequestId($response[static::REQUEST_ID]);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer $header
     *
     * @return void
     */
    protected function extractErrorFromConstraints(AmazonpayResponseHeaderTransfer $header)
    {
        if ($header->getConstraints()->count() === 0) {
            return;
        }

        $constraint = $header->getConstraints()[0];
        $header->setErrorMessage($this->buildErrorMessage($constraint));
    }

    /**
     * @param array $response
     *
     * @return bool
     */
    protected function isSuccess(array $response)
    {
        return
            $this->extractStatusCode($response) === static::STATUS_CODE_SUCCESS
            && $this->extractConstraints($response)->count() === 0;
    }

    /**
     * @param array $response
     *
     * @return array
     */
    protected function extractResult(array $response)
    {
        $responseType = $this->getResponseType();

        return $response[$responseType] ?? [];
    }

    /**
     * @param array $response
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\AmazonpayResponseConstraintTransfer[]
     */
    protected function extractConstraints(array $response)
    {
        $result = $this->extractResult($response);

        if (empty($result[static::ORDER_REFERENCE_DETAILS][static::CONSTRAINTS])) {
            return new ArrayObject();
        }

        $constraints = $this->getConstraints($result);

        return $this->buildConstraintTransfers($constraints);
    }

    /**
     * @param array $constraints
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\AmazonpayResponseConstraintTransfer[]
     */
    protected function buildConstraintTransfers(array $constraints)
    {
        $constraintTransfers = new ArrayObject();

        foreach ($constraints as $constraint) {
            if ($this->isValidConstraint($constraint)) {
                $constraintTransfers[] = $this->buildConstraintTransfer($constraint);
            }
        }

        return $constraintTransfers;
    }

    /**
     * @param array $constraint
     *
     * @return bool
     */
    protected function isValidConstraint(array $constraint)
    {
        return !empty($constraint[static::CONSTRAINT_ID]) && !empty($constraint[static::DESCRIPTION]);
    }

    /**
     * @param array $result
     *
     * @return array
     */
    protected function getConstraints(array $result)
    {
        if (count($result[static::ORDER_REFERENCE_DETAILS][static::CONSTRAINTS]) === 1) {
            return array_values($result[static::ORDER_REFERENCE_DETAILS][static::CONSTRAINTS]);
        }

        return $result[static::ORDER_REFERENCE_DETAILS][static::CONSTRAINTS];
    }

    /**
     * @param array $constraint
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseConstraintTransfer
     */
    protected function buildConstraintTransfer(array $constraint)
    {
        $constraintTransfer = new AmazonpayResponseConstraintTransfer();
        $constraintTransfer->setConstraintId($constraint[static::CONSTRAINT_ID]);
        $constraintTransfer->setConstraintDescription($constraint[static::DESCRIPTION]);

        return $constraintTransfer;
    }

    /**
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function extractShippingAddress(array $response)
    {
        $address = new AddressTransfer();

        if (!$this->isSuccess($response)) {
            return $address;
        }

        $aResponseAddress =
            $this->extractResult($response)[static::ORDER_REFERENCE_DETAILS][static::DESTINATION][static::PHYSICAL_DESTINATION] ?? null;

        if ($aResponseAddress !== null) {
            $address = $this->convertAddressToTransfer($aResponseAddress);
        }

        return $address;
    }

    /**
     * @param array $addressData
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function convertAddressToTransfer(array $addressData)
    {
        $address = new AddressTransfer();

        if (!empty($addressData[static::NAME])) {
            $address = $this->updateNameData($address, $addressData[static::NAME]);
        }

        $addressData = array_map([$this, 'getStringValue'], $addressData);

        $address->setCity($addressData[static::CITY] ?? null);
        $address->setIso2Code($addressData[static::COUNTRY_CODE] ?? null);
        $address->setZipCode($addressData[static::POSTAL_CODE] ?? null);
        $address->setAddress1($addressData[static::ADDRESS_LINE_1] ?? null);
        $address->setAddress2($addressData[static::ADDRESS_LINE_2] ?? null);
        $address->setAddress3($addressData[static::ADDRESS_LINE_3] ?? null);
        $address->setRegion($addressData[static::DISTRICT] ?? null);
        $address->setState($addressData[static::STATE_OR_REGION] ?? null);
        $address->setPhone($addressData[static::PHONE] ?? null);

        return $address;
    }

    /**
     * @param string|array $value
     *
     * @return string|null
     */
    protected function getStringValue($value)
    {
        return empty($value) ? null : (string)$value;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseConstraintTransfer $constraint
     *
     * @return string
     */
    protected function buildErrorMessage(AmazonpayResponseConstraintTransfer $constraint)
    {
        return AmazonPayConfig::PREFIX_AMAZONPAY_PAYMENT_ERROR . $constraint->getConstraintId();
    }
}
