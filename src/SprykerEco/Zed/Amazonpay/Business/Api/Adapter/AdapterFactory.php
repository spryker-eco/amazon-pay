<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Spryker\Shared\Amazonpay\AmazonpayConfigInterface;
use Spryker\Zed\Amazonpay\Business\Api\Adapter\Sdk\AmazonpaySdkAdapterFactory;
use Spryker\Zed\Amazonpay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Amazonpay\Business\Api\Converter\ConverterFactoryInterface;
use Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface;

class AdapterFactory implements AdapterFactoryInterface
{

    /**
     * @var \Spryker\Shared\Amazonpay\AmazonpayConfigInterface
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Amazonpay\Business\Api\Converter\ConverterFactory
     */
    protected $converterFactory;

    /**
     * @var \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \Spryker\Zed\Amazonpay\Business\Api\Converter\ConverterFactoryInterface $converterFactory
     * @param \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface $moneyFacade
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
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
     */
    public function createObtainProfileInformationAdapter()
    {
        return new ObtainProfileInformationAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createObtainProfileInformationConverter()
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
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
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
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
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
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
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
     */
    public function createAuthorizeQuoteAdapter()
    {
        return new AuthorizeQuoteAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createAuthorizeOrderConverter(),
            $this->moneyFacade,
            $this->config
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
     */
    public function createAuthorizeOrderAdapter()
    {
        return new AuthorizeOrderAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createAuthorizeOrderConverter(),
            $this->moneyFacade,
            $this->config
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
     */
    public function createAuthorizeCaptureNowOrderAdapter()
    {
        return new AuthorizeOrderAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createAuthorizeOrderConverter(),
            $this->moneyFacade,
            $this->config,
            true
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
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
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
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
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
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
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
     */
    public function createCancelPreOrderAdapter()
    {
        return new CancelPreOrderAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayClient($this->config),
            $this->converterFactory->createCancelOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
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
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
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
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
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
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\OrderAdapterInterface
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
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\Sdk\AmazonpaySdkAdapterFactoryInterface
     */
    protected function createSdkAdapterFactory()
    {
        return new AmazonpaySdkAdapterFactory();
    }

    /**
     * @param array $headers
     * @param string $body
     *
     * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\IpnRequestAdapterInterface
     */
    public function createIpnRequestAdapter(array $headers, $body)
    {
        return new IpnRequestAdapter(
            $this->createSdkAdapterFactory()->createAmazonpayIpnHandler($headers, $body),
            $this->converterFactory->createIpnArrayConverter()
        );
    }

}
