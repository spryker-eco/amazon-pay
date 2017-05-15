<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk;

use PayWithAmazon\ResponseParser;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AbstractAuthorizeAdapter;

class AbstractResponse
{
    /**
     * @var array
     */
    protected $responseData;

    /**
     * @var \PayWithAmazon\ResponseParser $responseParser
     */
    protected $responseParser;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $xml;

    /**
     * @var string
     */
    protected $responseBody;

    /**
     * @var string
     */
    protected $orderReferenceId;

    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        $this->orderReferenceId = $requestParameters[AbstractAuthorizeAdapter::AMAZON_ORDER_REFERENCE_ID];
        $this->statusCode = ($this->orderReferenceId === 'S02-1234567-0000666') ? 404 : 200;

        $this->wrongXml =
            '<ErrorResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <Error>
    <Type>Sender</Type>
    <Code>InvalidOrderReferenceId</Code>
    <Message>The OrderReferenceId wrong_one is invalid.</Message>
  </Error>
  <RequestId>51912fd0-87d0-42b5-96fd-2c384848b135</RequestId>
</ErrorResponse>
';
    }

    /**
     * @return ResponseParser
     */
    public function convertToResponseParser()
    {
        return new ResponseParser([
            'Status' => $this->statusCode,
            'ResponseBody' => $this->responseBody,
        ]);
    }

}