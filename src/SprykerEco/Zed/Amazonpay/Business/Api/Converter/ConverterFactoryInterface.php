<?php
/**
 * Created by PhpStorm.
 * User: dmitrikadykov
 * Date: 03/05/2017
 * Time: 15:41
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

interface ConverterFactoryInterface
{
    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCloseOrderConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface
     */
    public function createObtainProfileInformationConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createSetOrderReferenceDetailsConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createConfirmOrderReferenceConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetOrderReferenceDetailsConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createAuthorizeOrderConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetAuthorizationDetailsOrderConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCaptureOrderConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetCaptureOrderDetailsConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createRefundOrderConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetRefundOrderConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCancelOrderConverter();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\Ipn\IpnConverterFactoryInterface
     */
    public function createIpnConverterFactory();

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface
     */
    public function createIpnArrayConverter();
}