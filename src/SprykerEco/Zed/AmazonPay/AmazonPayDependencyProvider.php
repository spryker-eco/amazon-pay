<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyBridge;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsBridge;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToRefundBridge;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToSalesBridge;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToShipmentBridge;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToUtilEncodingBridge;

class AmazonPayDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_MONEY = 'money facade';
    const FACADE_SHIPMENT = 'shipment facade';
    const FACADE_SALES = 'sales facade';
    const FACADE_REFUND = 'refund facade';
    const FACADE_OMS = 'oms facade';
    const SERVICE_UTIL_ENCODING = 'encoding service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addMoneyFacade($container);
        $this->addShipmentFacade($container);
        $this->addRefundFacade($container);
        $this->addOmsFacade($container);
        $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->addRefundFacade($container);
        $this->addSalesFacade($container);
        $this->addOmsFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addSalesFacade(Container $container)
    {
        $container[static::FACADE_SALES] = function (Container $container) {
            return new AmazonPayToSalesBridge($container->getLocator()->sales()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addMoneyFacade(Container $container)
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new AmazonPayToMoneyBridge($container->getLocator()->money()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addShipmentFacade(Container $container)
    {
        $container[static::FACADE_SHIPMENT] = function (Container $container) {
            return new AmazonPayToShipmentBridge($container->getLocator()->shipment()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUtilEncodingService(Container $container)
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new AmazonPayToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addRefundFacade(Container $container)
    {
        $container[static::FACADE_REFUND] = function (Container $container) {
            return new AmazonPayToRefundBridge($container->getLocator()->refund()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addOmsFacade(Container $container)
    {
        $container[static::FACADE_OMS] = function (Container $container) {
            return new AmazonPayToOmsBridge($container->getLocator()->oms()->facade());
        };
    }
}
