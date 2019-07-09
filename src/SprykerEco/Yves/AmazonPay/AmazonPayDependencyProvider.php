<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToCalculationBridge;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToCartBridge;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToCheckoutBridge;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToCustomerBridge;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToGlossaryStorageBridge;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToMessengerClientBridge;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToQuoteBridge;
use SprykerEco\Yves\AmazonPay\Dependency\Client\AmazonPayToShipmentBridge;

class AmazonPayDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_QUOTE = 'quote client';
    public const CLIENT_CART = 'cart client';
    public const CLIENT_SHIPMENT = 'shipment client';
    public const CLIENT_CHECKOUT = 'checkout client';
    public const CLIENT_CUSTOMER = 'customer client';
    public const CLIENT_CALCULATION = 'calculation client';
    public const CLIENT_GLOSSARY_STORAGE = 'glossary storage client';
    public const CLIENT_MESSENGER = 'messenger client';
    public const PLUGIN_CHECKOUT_BREADCRUMB = 'plugin checkout breadcrumb';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $this->addQuoteClient($container);
        $this->addCartClient($container);
        $this->addShipmentClient($container);
        $this->addCheckoutClient($container);
        $this->addCalculationClient($container);
        $this->addCustomerClient($container);
        $this->addGlossaryStorageClient($container);

        $container = $this->addMessengerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addQuoteClient(Container $container)
    {
        $container[static::CLIENT_QUOTE] = function () use ($container) {
            return new AmazonPayToQuoteBridge($container->getLocator()->quote()->client());
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addCartClient(Container $container)
    {
        $container[static::CLIENT_CART] = function () use ($container) {
            return new AmazonPayToCartBridge($container->getLocator()->cart()->client());
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addShipmentClient(Container $container)
    {
        $container[static::CLIENT_SHIPMENT] = function () use ($container) {
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
        $container[static::CLIENT_CHECKOUT] = function () use ($container) {
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
        $container[static::CLIENT_CALCULATION] = function () use ($container) {
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
        $container[static::CLIENT_CUSTOMER] = function () use ($container) {
            return new AmazonPayToCustomerBridge($container->getLocator()->customer()->client());
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    protected function addGlossaryStorageClient(Container $container)
    {
        $container[static::CLIENT_GLOSSARY_STORAGE] = function () use ($container) {
            return new AmazonPayToGlossaryStorageBridge($container->getLocator()->glossaryStorage()->client());
        };
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMessengerClient(Container $container): Container
    {
        $container->set(static::CLIENT_MESSENGER, function() use ($container) {
            return new AmazonPayToMessengerClientBridge($container->getLocator()->messenger()->client());
        });

        return $container;
    }
}
