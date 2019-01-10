<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk;

use PayWithAmazon\Client;

class ClientMock extends Client
{
    public const FIRST_NAME = 'John';
    public const LAST_NAME = 'Doe';
    public const EMAIL = 'john@doe.xxx';

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function authorize($requestParameters = [])
    {
        $responseWrapper = new AuthorizeResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function cancelOrderReference($requestParameters = [])
    {
        $responseWrapper = new CancelOrderReferenceResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function capture($requestParameters = [])
    {
        $responseWrapper = new CaptureOrderResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function closeOrderReference($requestParameters = [])
    {
        $responseWrapper = new CloseOrderResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function confirmOrderReference($requestParameters = [])
    {
        $responseWrapper = new ConfirmOrderReferenceResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseParser
     */
    public function getAuthorizationDetails($requestParameters = [])
    {
        $responseWrapper = new GetAuthorizationDetailsResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseParser
     */
    public function getCaptureDetails($requestParameters = [])
    {
        $responseWrapper = new GetCaptureDetailsResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function getOrderReferenceDetails($requestParameters = [])
    {
        $responseWrapper = new GetOrderReferenceDetailsResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseParser
     */
    public function getRefundDetails($requestParameters = [])
    {
        $responseWrapper = new GetRefundDetailsResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param string $accessToken
     *
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
    public function refund($requestParameters = [])
    {
        $responseWrapper = new RefundOrderResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }

    /**
     * @param array $requestParameters
     *
     * @return \PayWithAmazon\ResponseInterface
     */
    public function setOrderReferenceDetails($requestParameters = [])
    {
        $responseWrapper = new SetOrderReferenceDetailsResponse($requestParameters);

        return $responseWrapper->convertToResponseParser();
    }
}
