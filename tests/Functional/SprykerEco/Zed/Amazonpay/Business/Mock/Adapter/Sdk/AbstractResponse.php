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
    protected $responseBodyXml;

    /**
     * @var string
     */
    protected $orderReferenceId;

    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        if (isset($requestParameters[AbstractAuthorizeAdapter::AMAZON_ORDER_REFERENCE_ID])) {
            $this->orderReferenceId = $requestParameters[AbstractAuthorizeAdapter::AMAZON_ORDER_REFERENCE_ID];
        }

        $this->statusCode = 200;
    }

    /**
     * @return ResponseParser
     */
    public function convertToResponseParser()
    {
        return new ResponseParser([
            'Status' => $this->statusCode,
            'ResponseBody' => $this->responseBodyXml,
        ]);
    }

}