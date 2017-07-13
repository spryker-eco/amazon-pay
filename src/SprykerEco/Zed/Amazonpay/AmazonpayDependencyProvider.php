<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay;

use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMessengerBridge;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyBridge;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsBridge;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToRefundBridge;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToSalesBridge;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentBridge;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AmazonpayDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_MONEY = 'money facade';
    const FACADE_SHIPMENT = 'shipment facade';
    const FACADE_SALES = 'sales facade';
    const FACADE_REFUND = 'refund facade';
    const FACADE_OMS = 'oms facade';
    const FACADE_MESSENGER = 'messenger facade';
    const SERVICE_UTIL_ENCODING = 'encoding service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addMoneyFacade($container);
        $container = $this->addShipmentFacade($container);
        $container = $this->addRefundFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addMessengerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addRefundFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addSalesFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container)
    {
        $container[self::FACADE_SALES] = function (Container $container) {
            return new AmazonpayToSalesBridge($container->getLocator()->sales()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container)
    {
        $container[self::FACADE_MONEY] = function (Container $container) {
            return new AmazonpayToMoneyBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentFacade(Container $container)
    {
        $container[self::FACADE_SHIPMENT] = function (Container $container) {
            return new AmazonpayToShipmentBridge($container->getLocator()->shipment()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container)
    {
        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new AmazonpayToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRefundFacade(Container $container)
    {
        $container[self::FACADE_REFUND] = function (Container $container) {
            return new AmazonpayToRefundBridge($container->getLocator()->refund()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container)
    {
        $container[self::FACADE_OMS] = function (Container $container) {
            return new AmazonpayToOmsBridge($container->getLocator()->oms()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container)
    {
        $container[self::FACADE_MESSENGER] = function (Container $container) {
            return new AmazonpayToMessengerBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

}
