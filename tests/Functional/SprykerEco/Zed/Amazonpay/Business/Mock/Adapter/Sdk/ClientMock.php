<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk;

use PayWithAmazon\Client;

class ClientMock extends Client
{

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function authorize($requestParameters = array())
    {
        $responseWrapper = new AuthorizeResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    public function cancelOrderReference($requestParameters = array())
    {

    }

    public function capture($requestParameters = array())
    {

    }

    public function closeOrderReference($requestParameters = array())
    {

    }

    public function confirmOrderReference($requestParameters = array())
    {

    }

    public function getAuthorizationDetails($requestParameters = array())
    {

    }

    public function getCaptureDetails($requestParameters = array())
    {

    }

    public function getOrderReferenceDetails($requestParameters = array())
    {

    }

    public function getRefundDetails($requestParameters = array())
    {

    }

    /**
     * @param string $accessToken
     * @return array
     */
    public function getUserInfo($accessToken)
    {
        $responseWrapper = new GetUserInfoResponse($accessToken);

        return $responseWrapper->convertToResponseParser();
    }

    public function refund($requestParameters = array())
    {
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function setOrderReferenceDetails($requestParameters = array())
    {
        $responseWrapper = new SetOrderReferenceDetailsResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

}