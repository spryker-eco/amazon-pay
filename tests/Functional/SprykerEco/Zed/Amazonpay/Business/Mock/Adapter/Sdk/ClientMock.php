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

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function cancelOrderReference($requestParameters = array())
    {
        $responseWrapper = new CancelOrderReferenceResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function capture($requestParameters = array())
    {
        $responseWrapper = new CaptureOrderResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function closeOrderReference($requestParameters = array())
    {
        $responseWrapper = new CloseOrderResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     * @return \PayWithAmazon\ResponseInterface
     */
    public function confirmOrderReference($requestParameters = array())
    {
        $responseWrapper = new ConfirmOrderReferenceResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    public function getAuthorizationDetails($requestParameters = array())
    {
        $responseWrapper = new GetAuthorizationDetailsResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    public function getCaptureDetails($requestParameters = array())
    {
        $responseWrapper = new GetCaptureDetailsResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function getOrderReferenceDetails($requestParameters = array())
    {
        $responseWrapper = new GetOrderReferenceDetailsResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseParser
     */
    public function getRefundDetails($requestParameters = array())
    {
        $responseWrapper = new GetRefundDetailsResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
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

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function refund($requestParameters = array())
    {
        $responseWrapper = new RefundOrderResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
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
