<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;
use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\Sdk\AmazonPaySdkAdapterFactory;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\ConverterFactoryInterface;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyInterface;

class AdapterFactory implements AdapterFactoryInterface
{
    /**
     * @var \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ConverterFactoryInterface
     */
    protected $converterFactory;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ConverterFactoryInterface $converterFactory
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyInterface $moneyFacade
     */
    public function __construct(
        AmazonPayConfigInterface $config,
        ConverterFactoryInterface $converterFactory,
        AmazonPayToMoneyInterface $moneyFacade
    ) {
        $this->config = $config;
        $this->converterFactory = $converterFactory;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createObtainProfileInformationAdapter()
    {
        return new ObtainProfileInformationAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createObtainProfileInformationConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createSetOrderReferenceDetailsAmazonpayAdapter()
    {
        return new SetOrderReferenceDetailsAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createSetOrderReferenceDetailsConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createConfirmOrderReferenceAmazonpayAdapter()
    {
        return new ConfirmQuoteReferenceAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createConfirmOrderReferenceConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderReferenceDetailsAmazonpayAdapter()
    {
        return new GetOrderReferenceDetailsAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createGetOrderReferenceDetailsConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createAuthorizeAdapter()
    {
        return new AuthorizeAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createAuthorizeOrderConverter(),
            $this->moneyFacade,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createAuthorizeCaptureNowAdapter()
    {
        return new AuthorizeAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createAuthorizeOrderConverter(),
            $this->moneyFacade,
            $this->config,
            true
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createCaptureOrderAdapter()
    {
        return new CaptureOrderAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createCaptureOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createCloseOrderAdapter()
    {
        return new CloseOrderAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createCloseOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createCancelOrderAdapter()
    {
        return new CancelOrderAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createCancelOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createRefundOrderAdapter()
    {
        return new RefundOrderAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createRefundOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderAuthorizationDetailsAdapter()
    {
        return new GetOrderAuthorizationDetailsAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createGetAuthorizationDetailsOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderCaptureDetailsAdapter()
    {
        return new GetOrderCaptureDetailsAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createGetCaptureOrderDetailsConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    public function createGetOrderRefundDetailsAdapter()
    {
        return new GetOrderRefundDetailsAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayClient($this->config),
            $this->converterFactory->createGetRefundOrderConverter(),
            $this->moneyFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\Sdk\AmazonPaySdkAdapterFactoryInterface
     */
    protected function createSdkAdapterFactory()
    {
        return new AmazonPaySdkAdapterFactory();
    }

    /**
     * @param array $headers
     * @param string $body
     *
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\IpnRequestAdapterInterface
     */
    public function createIpnRequestAdapter(array $headers, $body)
    {
        return new IpnRequestAdapter(
            $this->createSdkAdapterFactory()->createAmazonPayIpnHandler($headers, $body),
            $this->converterFactory->createIpnArrayConverter()
        );
    }
}
