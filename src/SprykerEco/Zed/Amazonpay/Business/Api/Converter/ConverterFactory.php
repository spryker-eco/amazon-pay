<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use Spryker\Zed\Amazonpay\Business\Api\Converter\Details\AuthorizationDetailsConverter;
use Spryker\Zed\Amazonpay\Business\Api\Converter\Details\CaptureDetailsConverter;
use Spryker\Zed\Amazonpay\Business\Api\Converter\Details\RefundDetailsConverter;
use Spryker\Zed\Amazonpay\Business\Api\Converter\Ipn\IpnArrayConverter;
use Spryker\Zed\Amazonpay\Business\Api\Converter\Ipn\IpnConverterFactory;

class ConverterFactory implements ConverterFactoryInterface
{

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCloseOrderConverter()
    {
        return new CloseOrderConverter();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface
     */
    public function createObtainProfileInformationConverter()
    {
        return new ObtainProfileInformationConverter();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createSetOrderReferenceDetailsConverter()
    {
        return new SetOrderReferenceDetailsConverter();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createConfirmOrderReferenceConverter()
    {
        return new ConfirmOrderReferenceConverter();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetOrderReferenceDetailsConverter()
    {
        return new GetOrderReferenceDetailsConverter();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createAuthorizeOrderConverter()
    {
        return new AuthorizeOrderConverter(
            $this->createAuthorizationDetailsConverter()
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetAuthorizationDetailsOrderConverter()
    {
        return new GetAuthorizationDetailsOrderConverter(
            $this->createAuthorizationDetailsConverter()
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCaptureOrderConverter()
    {
        return new CaptureOrderConverter(
            $this->createCaptureDetailsConverter()
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetCaptureOrderDetailsConverter()
    {
        return new GetCaptureOrderDetailsConverter(
            $this->createCaptureDetailsConverter()
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createRefundOrderConverter()
    {
        return new RefundOrderConverter(
            $this->createRefundDetailsConverter()
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createGetRefundOrderConverter()
    {
        return new GetRefundOrderDetailsConverter(
            $this->createRefundDetailsConverter()
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    public function createCancelOrderConverter()
    {
        return new CancelOrderConverter();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\Ipn\IpnConverterFactoryInterface
     */
    public function createIpnConverterFactory()
    {
        return new IpnConverterFactory();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface
     */
    public function createIpnArrayConverter()
    {
        return new IpnArrayConverter(
            $this->createIpnConverterFactory()
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createAuthorizationDetailsConverter()
    {
        return new AuthorizationDetailsConverter();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createCaptureDetailsConverter()
    {
        return new CaptureDetailsConverter();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createRefundDetailsConverter()
    {
        return new RefundDetailsConverter();
    }

}
