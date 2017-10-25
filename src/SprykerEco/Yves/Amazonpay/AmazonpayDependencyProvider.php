<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Amazonpay;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerEco\Zed\Amazonpay\Dependency\Client\AmazonpayToCalculationBridge;
use SprykerEco\Zed\Amazonpay\Dependency\Client\AmazonpayToCheckoutBridge;
use SprykerEco\Zed\Amazonpay\Dependency\Client\AmazonpayToCustomerBridge;
use SprykerEco\Zed\Amazonpay\Dependency\Client\AmazonpayToQuoteBridge;
use SprykerEco\Zed\Amazonpay\Dependency\Client\AmazonpayToShipmentBridge;

class AmazonpayDependencyProvider extends AbstractBundleDependencyProvider
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
        $container[self::CLIENT_QUOTE] = function () use ($container) {
            return new AmazonpayToQuoteBridge(
                $container->getLocator()->quote()->client()
            );
        };

        $container[self::CLIENT_SHIPMENT] = function () use ($container) {
            return new AmazonpayToShipmentBridge(
                $container->getLocator()->shipment()->client()
            );
        };

        $container[self::CLIENT_CHECKOUT] = function () use ($container) {
            return new AmazonpayToCheckoutBridge(
                $container->getLocator()->checkout()->client()
            );
        };

        $container[self::CLIENT_CALCULATION] = function () use ($container) {
            return new AmazonpayToCalculationBridge(
                $container->getLocator()->calculation()->client()
            );
        };

        $container[self::CLIENT_CUSTOMER] = function () use ($container) {
            return new AmazonpayToCustomerBridge(
                $container->getLocator()->customer()->client()
            );
        };

        return $container;
    }
}
