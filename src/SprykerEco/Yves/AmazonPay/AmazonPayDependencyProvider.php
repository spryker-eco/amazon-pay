<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToCalculationBridge;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToCheckoutBridge;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToCustomerBridge;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToQuoteBridge;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToShipmentBridge;

class AmazonPayDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_QUOTE = 'cart client';
    const CLIENT_SHIPMENT = 'shipment client';
    const CLIENT_CHECKOUT = 'checkout client';
    const CLIENT_CUSTOMER = 'customer client';
    const CLIENT_CALCULATION = 'calculation client';
    const PLUGIN_CHECKOUT_BREADCRUMB = 'plugin checkout breadcrumb';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $this->addQuoteClient($container);
        $this->addShipmentClient($container);
        $this->addCheckoutClient($container);
        $this->addCalculationClient($container);
        $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addQuoteClient(Container $container)
    {
        $container[self::CLIENT_QUOTE] = function () use ($container) {
            return new AmazonPayToQuoteBridge($container->getLocator()->quote()->client());
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addShipmentClient(Container $container)
    {
        $container[self::CLIENT_SHIPMENT] = function () use ($container) {
            return new AmazonPayToShipmentBridge($container->getLocator()->shipment()->client());
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addCheckoutClient(Container $container)
    {
        $container[self::CLIENT_CHECKOUT] = function () use ($container) {
            return new AmazonPayToCheckoutBridge($container->getLocator()->checkout()->client());
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addCalculationClient(Container $container)
    {
        $container[self::CLIENT_CALCULATION] = function () use ($container) {
            return new AmazonPayToCalculationBridge($container->getLocator()->calculation()->client());
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addCustomerClient(Container $container)
    {
        $container[self::CLIENT_CUSTOMER] = function () use ($container) {
            return new AmazonPayToCustomerBridge($container->getLocator()->customer()->client());
        };
    }
}
