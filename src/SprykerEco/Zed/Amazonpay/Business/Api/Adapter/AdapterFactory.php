<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\Sdk\AmazonpaySdkAdapterFactory;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\ConverterFactoryInterface;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface;

class AdapterFactory implements AdapterFactoryInterface
{

    /**
     * @var \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ConverterFactoryInterface
     */
    protected $converterFactory;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ConverterFactoryInterface $converterFactory
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface $moneyFacade
     */
    public function __construct(
        AmazonpayConfigInterface $config,
        ConverterFactoryInterface $converterFactory,
        AmazonpayToMoneyInterface $moneyFacade
    ) {
        $this->config = $config;
        $this->converterFactory = $converterFactory;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createObtainProfileInformationAdapter()
    {
        return new ObtainProfileInformationAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createObtainProfileInformationConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createSetOrderReferenceDetailsAmazonpayAdapter()
    {
        return new SetOrderReferenceDetailsAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createSetOrderReferenceDetailsConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createConfirmOrderReferenceAmazonpayAdapter()
    {
        return new ConfirmQuoteReferenceAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createConfirmOrderReferenceConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderReferenceDetailsAmazonpayAdapter()
    {
        return new GetOrderReferenceDetailsAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createGetOrderReferenceDetailsConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createAuthorizeAdapter()
    {
        return new AuthorizeAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createAuthorizeOrderConverter(),
            $this->moneyFacade,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createAuthorizeCaptureNowAdapter()
    {
        return new AuthorizeAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createAuthorizeOrderConverter(),
            $this->moneyFacade,
            $this->config,
            true
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createCaptureOrderAdapter()
    {
        return new CaptureOrderAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createCaptureOrderConverter(),
            $this->moneyFacade,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createCloseOrderAdapter()
    {
        return new CloseOrderAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createCloseOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createCancelOrderAdapter()
    {
        return new CancelOrderAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createCancelOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createRefundOrderAdapter()
    {
        return new RefundOrderAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createRefundOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderAuthorizationDetailsAdapter()
    {
        return new GetOrderAuthorizationDetailsAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createGetAuthorizationDetailsOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderCaptureDetailsAdapter()
    {
        return new GetOrderCaptureDetailsAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createGetCaptureOrderDetailsConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderRefundDetailsAdapter()
    {
        return new GetOrderRefundDetailsAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createGetRefundOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\Sdk\AmazonpaySdkAdapterFactoryInterface
     */
    protected function createSdkAdapterFactory()
    {
        return new AmazonpaySdkAdapterFactory();
    }

    /**
     * @param array $headers
     * @param string $body
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\IpnRequestAdapterInterface
     */
    public function createIpnRequestAdapter(array $headers, $body)
    {
        return new IpnRequestAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayIpnHandler($headers, $body),
            $this->converterFactory->createIpnArrayConverter()
        );
    }

}
