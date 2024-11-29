<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class AmazonPayRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const CHECKOUT = 'amazonpay_checkout';
    public const ENDPOINT = 'amazonpay_endpoint';
    public const CONFIRM_PURCHASE = 'amazonpay_confirm_purchase';
    public const SUCCESS = 'amazonpay_success';
    public const PAYMENT_FAILED = 'amazonpay_payment_failed';

    public const SET_ORDER_REFERENCE = 'amazonpay_set_order_reference';
    public const UPDATE_SHIPMENT_METHOD = 'amazonpay_update_shipment_method';
    public const GET_SHIPMENT_METHODS = 'amazonpay_get_shipment_methods';

    public const PAY_BUTTON = 'amazonpay_paybutton';
    public const CHECKOUT_WIDGET = 'amazonpay_checkout_widget';

    public const BUNDLE_NAME = 'AmazonPay';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $this->addCheckoutRoute($routeCollection);
        $this->addConfirmPurchaseRoute($routeCollection);
        $this->addSuccessRoute($routeCollection);
        $this->addPaymentFailedRoute($routeCollection);

        // ajax
        $this->addSetOrderReferenceRoute($routeCollection);
        $this->addUpdateShipmentMethodRoute($routeCollection);
        $this->addGetShipmentMethodsRoute($routeCollection);

        // widgets
        $this->addPayButtonRoute($routeCollection);
        $this->addCheckoutWidgetRoute($routeCollection);

        // endpoint
        $this->addEndpointRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param RouteCollection $routeCollection
     */
    protected function addCheckoutRoute(RouteCollection $routeCollection): void
    {
        $checkoutRoute = $this->buildRoute('/amazonpay/checkout', static::BUNDLE_NAME, 'Payment', 'checkoutAction');
        $routeCollection->add(static::CHECKOUT, $checkoutRoute);
    }

    /**
     * @param RouteCollection $routeCollection
     */
    protected function addConfirmPurchaseRoute(RouteCollection $routeCollection): void
    {
        $confirmPurchaseRoute = $this->buildRoute('/amazonpay/confirm/purchase', static::BUNDLE_NAME, 'Payment', 'confirmPurchaseAction');
        $routeCollection->add(static::CONFIRM_PURCHASE, $confirmPurchaseRoute);
    }

    /**
     * @param RouteCollection $routeCollection
     */
    protected function addSuccessRoute(RouteCollection $routeCollection): void
    {
        $successRoute = $this->buildRoute('/amazonpay/success', static::BUNDLE_NAME, 'Payment', 'successAction');
        $routeCollection->add(static::SUCCESS, $successRoute);
    }

    /**
     * @param RouteCollection $routeCollection
     */
    protected function addPaymentFailedRoute(RouteCollection $routeCollection): void
    {
        $paymentFailedRoute = $this->buildRoute('/amazonpay/payment-failed', static::BUNDLE_NAME, 'Payment', 'paymentFailedAction');
        $routeCollection->add(static::PAYMENT_FAILED, $paymentFailedRoute);
    }

    /**
     * @param RouteCollection $routeCollection
     */
    protected function addSetOrderReferenceRoute(RouteCollection $routeCollection): void
    {
        $setOrderReferenceRoute = $this->buildRoute('/amazonpay/set-order-reference', static::BUNDLE_NAME, 'Payment', 'setOrderReferenceAction');
        $routeCollection->add(static::SET_ORDER_REFERENCE, $setOrderReferenceRoute);
    }

    /**
     * @param RouteCollection $routeCollection
     */
    protected function addUpdateShipmentMethodRoute(RouteCollection $routeCollection): void
    {
        $updateShipmentMethodRoute = $this->buildRoute('/amazonpay/update-shipment-method', static::BUNDLE_NAME, 'Payment', 'updateShipmentMethodAction');
        $routeCollection->add(static::UPDATE_SHIPMENT_METHOD, $updateShipmentMethodRoute);
    }

    /**
     * @param RouteCollection $routeCollection
     */
    protected function addGetShipmentMethodsRoute(RouteCollection $routeCollection): void
    {
        $updateShipmentMethodRoute = $this->buildRoute('/amazonpay/get-shipment-methods', static::BUNDLE_NAME, 'Payment', 'getShipmentMethodsAction');
        $routeCollection->add(static::GET_SHIPMENT_METHODS, $updateShipmentMethodRoute);
    }

    /**
     * @param RouteCollection $routeCollection
     */
    protected function addPayButtonRoute(RouteCollection $routeCollection): void
    {
        $payButtonRoute = $this->buildRoute('/amazonpay/paybutton', static::BUNDLE_NAME, 'Widget', 'payButtonAction');
        $routeCollection->add(static::PAY_BUTTON, $payButtonRoute);
    }

    /**
     * @param RouteCollection $routeCollection
     */
    protected function addCheckoutWidgetRoute(RouteCollection $routeCollection): void
    {
        $checkoutWidgetRoute = $this->buildRoute('/amazonpay/checkout-widget', static::BUNDLE_NAME, 'Widget', 'checkoutWidgetAction');
        $routeCollection->add(static::CHECKOUT_WIDGET, $checkoutWidgetRoute);
    }

    /**
     * @param RouteCollection $routeCollection
     */
    protected function addEndpointRoute(RouteCollection $routeCollection): void
    {
        $endpointRoute = $this->buildRoute('/amazonpay/endpoint', static::BUNDLE_NAME, 'Payment', 'endpointAction');
        $routeCollection->add(static::ENDPOINT, $endpointRoute);
    }
}
