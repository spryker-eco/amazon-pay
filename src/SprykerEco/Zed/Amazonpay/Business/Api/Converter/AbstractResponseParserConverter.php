<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\AmazonpayResponseConstraintTransfer;
use Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer;
use Generated\Shared\Transfer\AmazonpayResponseTransfer;
use PayWithAmazon\ResponseInterface;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;

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
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function setBody(AmazonpayResponseTransfer $responseTransfer, ResponseInterface $responseParser)
    {
        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseTransfer $responseTransfer
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected function mapResponseDataToTransfer(AmazonpayResponseTransfer $responseTransfer, ResponseInterface $responseParser)
    {
        $responseTransfer->setHeader($this->extractHeader($responseParser));

        if ($responseTransfer->getHeader()->getIsSuccess()) {
            return $this->setBody($responseTransfer, $responseParser);
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
        return $this->mapResponseDataToTransfer($this->createTransferObject(), $responseParser);
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return array
     */
    protected function extractMetadata(ResponseInterface $responseParser)
    {
        return $responseParser->toArray()[static::RESPONSE_METADATA] ?? [];
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return int
     */
    protected function extractStatusCode(ResponseInterface $responseParser)
    {
        return (int)$responseParser->toArray()[static::RESPONSE_STATUS];
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer
     */
    protected function extractHeader(ResponseInterface $responseParser)
    {
        $statusCode = $this->extractStatusCode($responseParser);
        $metadata = $this->extractMetadata($responseParser);
        $constraints = $this->extractConstraints($responseParser);

        $header = new AmazonpayResponseHeaderTransfer();
        $header->setIsSuccess($this->isSuccess($responseParser));
        $header->setStatusCode($statusCode);

        if (isset($metadata[static::REQUEST_ID])) {
            $header->setRequestId($metadata[static::REQUEST_ID]);
            $responseArray = $responseParser->toArray();

            if (!empty($responseArray[static::ERROR])) {
                return $this->updateHeaderWithError($header, $responseArray);
            }
        }

        $header->setConstraints($constraints);
        $this->extractErrorFromConstraints($header);

        return $header;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer $header
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer
     */
    protected function updateHeaderWithError(AmazonpayResponseHeaderTransfer $header, array $responseArray)
    {
        $header->setErrorMessage($responseArray[static::ERROR][static::MESSAGE]);
        $header->setErrorCode($responseArray[static::ERROR][static::CODE]);
        $header->setRequestId($responseArray[static::REQUEST_ID]);

        return $header;
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
        $header->setErrorMessage(AmazonpayConfig::PREFIX_AMAZONPAY_PAYMENT_ERROR . $constraint->getConstraintId());
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return bool
     */
    protected function isSuccess(ResponseInterface $responseParser)
    {
        return
            $this->extractStatusCode($responseParser) === static::STATUS_CODE_SUCCESS
            && $this->extractConstraints($responseParser)->count() === 0;
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return array
     */
    protected function extractResult(ResponseInterface $responseParser)
    {
        $responseType = $this->getResponseType();

        return $responseParser->toArray()[$responseType] ?? [];
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \ArrayObject | \Generated\Shared\Transfer\AmazonpayResponseConstraintTransfer[]
     */
    protected function extractConstraints(ResponseInterface $responseParser)
    {
        $result = $this->extractResult($responseParser);

        $constraintTransfers = new ArrayObject();

        if (empty($result[static::ORDER_REFERENCE_DETAILS][static::CONSTRAINTS])) {
            return $constraintTransfers;
        }

        $constraints = $this->getConstraints($result);

        foreach ($constraints as $constraint) {
            if (!empty($constraint[static::CONSTRAINT_ID]) && !empty($constraint[static::DESCRIPTION])) {
                $constraintTransfers[] = $this->buildConstraintTransfer($constraint);
            }
        }

        return $constraintTransfers;
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
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function extractShippingAddress(ResponseInterface $responseParser)
    {
        $address = new AddressTransfer();

        if (!$this->isSuccess($responseParser)) {
            return $address;
        }

        $aResponseAddress =
            $this->extractResult($responseParser)[static::ORDER_REFERENCE_DETAILS][self::DESTINATION][self::PHYSICAL_DESTINATION] ?? null;

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

        if (!empty($addressData[self::NAME])) {
            $address = $this->updateNameData($address, $addressData[self::NAME]);
        }

        $addressData = array_map([$this, 'getStringValue'], $addressData);

        $address->setCity($addressData[self::CITY] ?? null);
        $address->setIso2Code($addressData[self::COUNTRY_CODE] ?? null);
        $address->setZipCode($addressData[self::POSTAL_CODE] ?? null);
        $address->setAddress1($addressData[self::ADDRESS_LINE_1] ?? null);
        $address->setAddress2($addressData[self::ADDRESS_LINE_2] ?? null);
        $address->setAddress3($addressData[self::ADDRESS_LINE_3] ?? null);
        $address->setRegion($addressData[self::DISTRICT] ?? null);
        $address->setState($addressData[self::STATE_OR_REGION] ?? null);
        $address->setPhone($addressData[self::PHONE] ?? null);

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
}
