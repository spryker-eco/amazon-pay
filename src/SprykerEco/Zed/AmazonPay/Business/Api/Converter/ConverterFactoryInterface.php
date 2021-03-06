<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

interface ConverterFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCloseOrderConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    public function createObtainProfileInformationConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createSetOrderReferenceDetailsConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createConfirmOrderReferenceConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetOrderReferenceDetailsConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createAuthorizeOrderConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetAuthorizationDetailsOrderConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCaptureOrderConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetCaptureOrderDetailsConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createRefundOrderConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetRefundOrderConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCancelOrderConverter();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn\IpnConverterFactoryInterface
     */
    public function createIpnConverterFactory();

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn\IpnConverterInterface
     */
    public function createIpnArrayConverter();
}
