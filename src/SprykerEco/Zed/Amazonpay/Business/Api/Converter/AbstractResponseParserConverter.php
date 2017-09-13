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

abstract class AbstractResponseParserConverter extends AbstractConverter implements ResponseParserConverterInterface
{

    const STATUS_CODE_SUCCESS = 200;
    const ORDER_REFERENCE_DETAILS = 'OrderReferenceDetails';
    const CONSTRAINTS = 'Constraints';
    const DESCRIPTION = 'Description';
    const CONSTRAINT_ID = 'ConstraintID';
    const REQUEST_ID = 'RequestId';
    const ERROR = 'Error';

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
        return $responseParser->toArray()['ResponseMetadata'] ?? [];
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return int
     */
    protected function extractStatusCode(ResponseInterface $responseParser)
    {
        return (int)$responseParser->toArray()['ResponseStatus'];
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

        if ($metadata) {
            $header->setRequestId($metadata[self::REQUEST_ID]);
        }

        $responseArray = $responseParser->toArray();
        if (!empty($responseArray[self::ERROR])) {
            $header->setErrorMessage($responseArray[self::ERROR]['Message']);
            $header->setErrorCode($responseArray[self::ERROR]['Code']);
            $header->setRequestId($responseArray[self::REQUEST_ID]);

            return $header;
        }

        $header->setConstraints($constraints);
        $this->extractErrorFromConstraints($header);

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
        $header->setErrorCode('amazonpay.payment.error.' . $constraint->getConstraintId());
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return bool
     */
    protected function isSuccess(ResponseInterface $responseParser)
    {
        return
            $this->extractStatusCode($responseParser) === self::STATUS_CODE_SUCCESS
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
     * @return \ArrayObject|\Generated\Shared\Transfer\AmazonpayResponseConstraintTransfer[]
     */
    protected function extractConstraints(ResponseInterface $responseParser)
    {
        $result = $this->extractResult($responseParser);

        $constraintTransfers = new ArrayObject();

        if (empty($result[self::ORDER_REFERENCE_DETAILS][self::CONSTRAINTS])) {
            return $constraintTransfers;
        }

        if (count($result[self::ORDER_REFERENCE_DETAILS][self::CONSTRAINTS]) === 1) {
            $constraints = array_values($result[self::ORDER_REFERENCE_DETAILS][self::CONSTRAINTS]);
        } else {
            $constraints = $result[self::ORDER_REFERENCE_DETAILS][self::CONSTRAINTS];
        }

        foreach ($constraints as $constraint) {
            if ((!empty($constraint[self::CONSTRAINT_ID])) && !empty($constraint[self::DESCRIPTION])) {
                $constraintTransfer = new AmazonpayResponseConstraintTransfer();
                $constraintTransfer->setConstraintId($constraint[self::CONSTRAINT_ID]);
                $constraintTransfer->setConstraintDescription($constraint[self::DESCRIPTION]);

                $constraintTransfers[] = $constraintTransfer;
            }
        }

        return $constraintTransfers;
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
            $this->extractResult($responseParser)[self::ORDER_REFERENCE_DETAILS]['Destination']['PhysicalDestination'] ?? null;

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

        if (!empty($addressData['Name'])) {
            $address = $this->updateNameData($address, $addressData['Name']);
        }

        $addressData = array_map([$this, 'getStringValue'], $addressData);

        $address->setCity($addressData['City'] ?? null);
        $address->setIso2Code($addressData['CountryCode'] ?? null);
        $address->setZipCode($addressData['PostalCode'] ?? null);
        $address->setAddress1($addressData['AddressLine1'] ?? null);
        $address->setAddress2($addressData['AddressLine2'] ?? null);
        $address->setAddress3($addressData['AddressLine3'] ?? null);
        $address->setRegion($addressData['District'] ?? null);
        $address->setState($addressData['StateOrRegion'] ?? null);
        $address->setPhone($addressData['Phone'] ?? null);

        return $address;
    }

    /**
     * @param string|array $value
     *
     * @return null
     */
    protected function getStringValue($value)
    {
        return empty($value) ? null : $value;
    }

}
