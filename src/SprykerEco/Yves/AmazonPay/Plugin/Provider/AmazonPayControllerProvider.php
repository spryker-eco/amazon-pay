<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay\Plugin\Provider;

use Silex\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Application\Plugin\Provider\YvesControllerProvider;

class AmazonPayControllerProvider extends YvesControllerProvider
{
    public const CHECKOUT = 'amazonpay_checkout';
    public const ENDPOINT = 'amazonpay_endpoint';
    public const CONFIRM_PURCHASE = 'amazonpay_confirm_purchase';
    public const SUCCESS = 'amazonpay_success';
    public const PAYMENT_FAILED = 'amazonpay_payment_failed';

    public const SET_ORDER_REFERENCE = 'amazonpay_set_order_reference';
    public const UPDATE_SHIPMENT_METHOD = 'amazonpay_update_shipment_method';
    public const GET_SHIPMENT_METHODS = 'amazonpay_get_shipment_methods';

    public const PLACE_ORDER = 'amazonpay_place_order';

    public const PAY_BUTTON = 'amazonpay_paybutton';
    public const CHECKOUT_WIDGET = 'amazonpay_checkout_widget';

    public const BUNDLE_NAME = 'AmazonPay';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();
        $this->createController('/{amazonpay}/checkout', static::CHECKOUT, static::BUNDLE_NAME, 'Payment', 'checkout')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        $this->createController('/{amazonpay}/confirm/purchase', static::CONFIRM_PURCHASE, static::BUNDLE_NAME, 'Payment', 'confirmPurchase')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        $this->createController('/{amazonpay}/success', static::SUCCESS, static::BUNDLE_NAME, 'Payment', 'success')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        $this->createController('/{amazonpay}/payment-failed', static::PAYMENT_FAILED, static::BUNDLE_NAME, 'Payment', 'paymentFailed')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        // ajax
        $this->createController('/amazonpay/set-order-reference', static::SET_ORDER_REFERENCE, static::BUNDLE_NAME, 'Payment', 'setOrderReference');
        $this->createController('/amazonpay/update-shipment-method', static::UPDATE_SHIPMENT_METHOD, static::BUNDLE_NAME, 'Payment', 'updateShipmentMethod');
        $this->createController('/{amazonpay}/get-shipment-methods', static::GET_SHIPMENT_METHODS, static::BUNDLE_NAME, 'Payment', 'getShipmentMethods')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');
        $this->createPostController('/amazonpay/place-order', static::PLACE_ORDER, static::BUNDLE_NAME, 'Payment', 'placeOrder');

        // widgets
        $this->createController('/{amazonpay}/paybutton', static::PAY_BUTTON, static::BUNDLE_NAME, 'Widget', 'payButton')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        $this->createController('/{amazonpay}/checkout-widget', static::CHECKOUT_WIDGET, static::BUNDLE_NAME, 'Widget', 'checkoutWidget')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        // endpoint
        $this->createController('/amazonpay/endpoint', static::ENDPOINT, static::BUNDLE_NAME, 'Payment', 'endpoint');
    }

    /**
     * @return string
     */
    public function getAllowedLocalesPattern()
    {
        $systemLocales = Store::getInstance()->getLocales();
        $implodedLocales = implode('|', array_keys($systemLocales));

        return '(' . $implodedLocales . ')\/';
    }
}
