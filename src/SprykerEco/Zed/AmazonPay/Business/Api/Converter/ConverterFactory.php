<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

use SprykerEco\Zed\AmazonPay\Business\Api\Converter\Details\AuthorizationDetailsConverter;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\Details\CaptureDetailsConverter;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\Details\RefundDetailsConverter;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn\IpnArrayConverter;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn\IpnConverterFactory;

class ConverterFactory implements ConverterFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCloseOrderConverter()
    {
        return new CloseOrderConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    public function createObtainProfileInformationConverter()
    {
        return new ObtainProfileInformationConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createSetOrderReferenceDetailsConverter()
    {
        return new SetOrderReferenceDetailsConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createConfirmOrderReferenceConverter()
    {
        return new ConfirmOrderReferenceConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetOrderReferenceDetailsConverter()
    {
        return new GetOrderReferenceDetailsConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createAuthorizeOrderConverter()
    {
        return new AuthorizeOrderConverter(
            $this->createAuthorizationDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetAuthorizationDetailsOrderConverter()
    {
        return new GetAuthorizationDetailsOrderConverter(
            $this->createAuthorizationDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCaptureOrderConverter()
    {
        return new CaptureOrderConverter(
            $this->createCaptureDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetCaptureOrderDetailsConverter()
    {
        return new GetCaptureOrderDetailsConverter(
            $this->createCaptureDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createRefundOrderConverter()
    {
        return new RefundOrderConverter(
            $this->createRefundDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetRefundOrderConverter()
    {
        return new GetRefundOrderDetailsConverter(
            $this->createRefundDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCancelOrderConverter()
    {
        return new CancelOrderConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn\IpnConverterFactoryInterface
     */
    public function createIpnConverterFactory()
    {
        return new IpnConverterFactory();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    public function createIpnArrayConverter()
    {
        return new IpnArrayConverter(
            $this->createIpnConverterFactory()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createAuthorizationDetailsConverter()
    {
        return new AuthorizationDetailsConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createCaptureDetailsConverter()
    {
        return new CaptureDetailsConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createRefundDetailsConverter()
    {
        return new RefundDetailsConverter();
    }
}
