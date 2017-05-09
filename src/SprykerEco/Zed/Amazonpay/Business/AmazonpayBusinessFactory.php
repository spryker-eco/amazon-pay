<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business;

use Spryker\Shared\Amazonpay\AmazonpayConfig;
use Spryker\Zed\Amazonpay\AmazonpayDependencyProvider;
use Spryker\Zed\Amazonpay\Business\Api\Adapter\AdapterFactory;
use Spryker\Zed\Amazonpay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Amazonpay\Business\Order\Saver;
use Spryker\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnFactory;
use Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLogger;
use Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\TransactionFactory;
use Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdateFactory;
use Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Amazonpay\Persistence\AmazonpayQueryContainer getQueryContainer()
 */
class AmazonpayBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\TransactionFactoryInterface
     */
    public function createTransactionFactory()
    {
        return new TransactionFactory(
            $this->createAdapterFactory(),
            $this->getConfig(),
            $this->createTransactionLogger(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Shared\Amazonpay\AmazonpayConfig
     */
    public function getConfig()
    {
        return new AmazonpayConfig();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdateFactoryInterface
     */
    public function createQuoteUpdateFactory()
    {
        return new QuoteUpdateFactory(
            $this->createAdapterFactory(),
            $this->getConfig(),
            $this->getShipmentFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnFactoryInterface
     */
    public function createIpnFactory()
    {
        return new IpnFactory(
            $this->getOmsFacade(),
            $this->getQueryContainer(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToRefundInterface
     */
    public function getRefundFacade()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::FACADE_REFUND);
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentInterface
     */
    protected function getShipmentFacade()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::SERVICE_UTIL_ENCODING);
    }

   /**
    * @return \Spryker\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface
    */
    public function createAdapterFactory()
    {
        return new AdapterFactory(
            $this->getConfig(),
            $this->createConverterFactory(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Api\Converter\ConverterFactoryInterface
     */
    protected function createConverterFactory()
    {
        return new ConverterFactory();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Order\SaverInterface
     */
    public function createOrderSaver()
    {
        return new Saver();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface
     */
    public function createTransactionLogger()
    {
        return new TransactionLogger($this->getConfig()->getErrorReportLevel());
    }

}
