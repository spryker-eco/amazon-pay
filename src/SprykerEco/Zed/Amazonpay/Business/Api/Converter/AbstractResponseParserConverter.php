<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\AmazonpayResponseConstraintTransfer;
use Generated\Shared\Transfer\AmazonpayResponseHeaderTransfer;
use PayWithAmazon\ResponseInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

abstract class AbstractResponseParserConverter extends AbstractConverter implements ResponseParserConverterInterface
{

    const STATUS_CODE_SUCCESS = 200;

    /**
     * @var string
     */
    protected $resultKeyName;

    /**
     * @return string
     */
    abstract protected function getResponseType();

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    abstract protected function createTransferObject();

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function setBody(AbstractTransfer $responseTransfer, ResponseInterface $responseParser)
    {
        return $responseTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function setResponseDataToTransfer(AbstractTransfer $responseTransfer, ResponseInterface $responseParser)
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
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function convert(ResponseInterface $responseParser)
    {
        return $this->setResponseDataToTransfer($this->createTransferObject(), $responseParser);
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return array
     */
    protected function extractMetadata(ResponseInterface $responseParser)
    {
        return empty($responseParser->toArray()['ResponseMetadata'])
            ? []
            : $responseParser->toArray()['ResponseMetadata'];
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
            $header->setRequestId($metadata['RequestId']);
        }

        if (!empty($responseParser->toArray()['Error'])) {
            $header->setErrorMessage($responseParser->toArray()['Error']['Message']);
            $header->setErrorCode($responseParser->toArray()['Error']['Code']);
            $header->setRequestId($responseParser->toArray()['RequestId']);

            return $header;
        }

        if ($constraints) {
            $header->setConstraints($constraints);
        }

        return $header;
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return bool
     */
    protected function isSuccess(ResponseInterface $responseParser)
    {
        return
            $this->extractStatusCode($responseParser) == self::STATUS_CODE_SUCCESS
            && empty($this->extractConstraints($responseParser));
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return array
     */
    protected function extractResult(ResponseInterface $responseParser)
    {
        $responseType = $this->getResponseType();

        return !empty($responseParser->toArray()[$responseType])
            ? $responseParser->toArray()[$responseType]
            : [];
    }

    /**
     * @param \PayWithAmazon\ResponseInterface $responseParser
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseConstraintTransfer[]
     */
    protected function extractConstraints(ResponseInterface $responseParser)
    {
        $result = $this->extractResult($responseParser);

        if (empty($result['OrderReferenceDetails']['Constraints'])) {
            return [];
        }

        $constraintTransfers = [];

        if (count($result['OrderReferenceDetails']['Constraints']) === 1) {
            $constraints = array_values($result['OrderReferenceDetails']['Constraints']);
        } else {
            $constraints = $result['OrderReferenceDetails']['Constraints'];
        }

        foreach ($constraints as $constraint) {
            if ((!empty($constraint['ConstraintID'])) && !empty($constraint['Description'])) {
                $constraintTransfer = new AmazonpayResponseConstraintTransfer();
                $constraintTransfer->setConstraintId($constraint['ConstraintID']);
                $constraintTransfer->setConstraintId($constraint['Description']);

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
            $this->extractResult($responseParser)['OrderReferenceDetails']['Destination']['PhysicalDestination'] ?? null;

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

        array_walk($addressData, [$this, 'getStringValue']);

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

    protected function getStringValue($value)
    {
        return empty($value) ? null : $value;
    }

}
