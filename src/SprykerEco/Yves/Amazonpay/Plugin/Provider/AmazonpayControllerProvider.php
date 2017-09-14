<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Amazonpay\Plugin\Provider;

use Pyz\Yves\Application\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class AmazonpayControllerProvider extends AbstractYvesControllerProvider
{

    const CHECKOUT = 'amazonpay_checkout';
    const ENDPOINT = 'amazonpay_endpoint';
    const CONFIRM_PURCHASE = 'amazonpay_confirm_purchase';
    const SUCCESS = 'amazonpay_success';
    const PAYMENT_FAILED = 'amazonpay_payment_failed';

    const SET_ORDER_REFERENCE = 'amazonpay_set_order_reference';
    const UPDATE_SHIPMENT_METHOD = 'amazonpay_update_shipment_method';
    const GET_SHIPMENT_METHODS = 'amazonpay_get_shipment_methods';

    const PAY_BUTTON = 'amazonpay_paybutton';
    const CHECKOUT_WIDGET = 'amazonpay_checkout_widget';

    const BUNDLE_NAME = 'Amazonpay';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();
        $this->createController('/{amazonpay}/checkout', static::CHECKOUT, self::BUNDLE_NAME, 'Payment', 'checkout')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        $this->createController('/{amazonpay}/confirm/purchase', static::CONFIRM_PURCHASE, self::BUNDLE_NAME, 'Payment', 'confirmPurchase')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        $this->createController('/{amazonpay}/success', static::SUCCESS, self::BUNDLE_NAME, 'Payment', 'success')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        $this->createController('/{amazonpay}/payment-failed', static::PAYMENT_FAILED, self::BUNDLE_NAME, 'Payment', 'paymentFailed')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        // ajax
        $this->createController('/amazonpay/set-order-reference', static::SET_ORDER_REFERENCE, self::BUNDLE_NAME, 'Payment', 'setOrderReference');
        $this->createController('/amazonpay/update-shipment-method', static::UPDATE_SHIPMENT_METHOD, self::BUNDLE_NAME, 'Payment', 'updateShipmentMethod');
        $this->createController('/{amazonpay}/get-shipment-methods', static::GET_SHIPMENT_METHODS, self::BUNDLE_NAME, 'Payment', 'getShipmentMethods')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        // widgets
        $this->createController('/{amazonpay}/paybutton', static::PAY_BUTTON, self::BUNDLE_NAME, 'Widget', 'payButton')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        $this->createController('/{amazonpay}/checkout-widget', static::CHECKOUT_WIDGET, self::BUNDLE_NAME, 'Widget', 'checkoutWidget')
            ->assert('amazonpay', $allowedLocalesPattern . 'amazonpay|amazonpay')
            ->value('amazonpay', 'amazonpay');

        // endpoint
        $this->createController('/amazonpay/endpoint', static::ENDPOINT, self::BUNDLE_NAME, 'Payment', 'endpoint');
    }

}
