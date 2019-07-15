<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Zed\AmazonPay\AmazonPayDependencyProvider;
use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AdapterFactory;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\ConverterFactory;
use SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayConverter;
use SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverter;
use SprykerEco\Zed\AmazonPay\Business\Order\AmazonpayOrderInfoHydrator;
use SprykerEco\Zed\AmazonPay\Business\Order\PaymentProcessorModel;
use SprykerEco\Zed\AmazonPay\Business\Order\Placement;
use SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderModel;
use SprykerEco\Zed\AmazonPay\Business\Order\RelatedItemsUpdateModel;
use SprykerEco\Zed\AmazonPay\Business\Order\Saver;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnFactory;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLogger;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\TransactionFactory;
use SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdateFactory;

/**
 * @method \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface getQueryContainer()
 */
class AmazonPayBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\TransactionFactoryInterface
     */
    public function createTransactionFactory()
    {
        return new TransactionFactory(
            $this->createAdapterFactory(),
            $this->createAmazonpayConfig(),
            $this->createTransactionLogger(),
            $this->createAmazonpayConverter(),
            $this->createAmazonpayTransferToEntityConverter(),
            $this->createRefundOrderModel(),
            $this->createPaymentProcessorModel()
        );
    }

    /**
     * @return \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface
     */
    public function createAmazonpayConfig()
    {
        return new AmazonPayConfig();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdateFactoryInterface
     */
    public function createQuoteUpdateFactory()
    {
        return new QuoteUpdateFactory(
            $this->createAdapterFactory(),
            $this->getShipmentFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnFactoryInterface
     */
    public function createIpnFactory()
    {
        return new IpnFactory(
            $this->getOmsFacade(),
            $this->getQueryContainer(),
            $this->getUtilEncodingService(),
            $this->createRefundOrderModel(),
            $this->createAmazonpayConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToRefundInterface
     */
    public function getRefundFacade()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::FACADE_REFUND);
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToShipmentInterface
     */
    protected function getShipmentFacade()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::SERVICE_UTIL_ENCODING);
    }

   /**
    * @return \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AdapterFactoryInterface
    */
    public function createAdapterFactory()
    {
        return new AdapterFactory(
            $this->createAmazonpayConfig(),
            $this->createConverterFactory(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ConverterFactoryInterface
     */
    protected function createConverterFactory()
    {
        return new ConverterFactory();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Order\SaverInterface
     */
    public function createOrderSaver()
    {
        return new Saver();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface
     */
    public function createTransactionLogger()
    {
        return new TransactionLogger($this->createAmazonpayConfig());
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(
            AmazonPayDependencyProvider::FACADE_SALES
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayConverterInterface
     */
    public function createAmazonpayConverter()
    {
        return new AmazonPayConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayTransferToEntityConverterInterface
     */
    protected function createAmazonpayTransferToEntityConverter()
    {
        return new AmazonPayTransferToEntityConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface
     */
    protected function createRefundOrderModel()
    {
        return new RefundOrderModel($this->getRefundFacade());
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Order\AmazonpayOrderInfoHydratorInterface
     */
    public function createAmazonpayOrderInfoHydrator()
    {
        return new AmazonpayOrderInfoHydrator(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Order\RelatedItemsUpdateInterface
     */
    public function createRelatedItemsUpdateModel()
    {
        return new RelatedItemsUpdateModel(
            $this->getQueryContainer(),
            $this->getOmsFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Order\PaymentProcessorInterface
     */
    protected function createPaymentProcessorModel()
    {
        return new PaymentProcessorModel(
            $this->getQueryContainer(),
            $this->createAmazonpayTransferToEntityConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Order\PlacementInterface
     */
    public function createPlacement()
    {
        return new Placement(
            $this->createTransactionFactory()
                ->createConfirmPurchaseTransaction()
        );
    }
}
