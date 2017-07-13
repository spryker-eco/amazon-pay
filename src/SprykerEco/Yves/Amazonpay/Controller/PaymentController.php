<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Amazonpay\Controller;

use InvalidArgumentException;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use SprykerEco\Yves\Amazonpay\Plugin\Provider\AmazonpayControllerProvider;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Spryker\Shared\Config\Config;

/**
 * @method \SprykerEco\Yves\Amazonpay\AmazonpayFactory getFactory()
 * @method \SprykerEco\Client\Amazonpay\AmazonpayClient getClient()
 */
class PaymentController extends AbstractController
{

    const URL_PARAM_REFERENCE_ID = 'reference_id';
    const URL_PARAM_ACCESS_TOKEN = 'access_token';
    const URL_PARAM_SHIPMENT_METHOD_ID = 'shipment_method_id';
    const QUOTE_TRANSFER = 'quoteTransfer';
    const SHIPMENT_METHODS = 'shipmentMethods';
    const AMAZONPAY_CONFIG = 'amazonpayConfig';
    const IS_ASYNCHRONOUS = 'isAsynchronous';
    const CART_ITEMS = 'cartItems';
    const SUCCESS = 'success';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function checkoutAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if (!$this->isAllowedCheckout($quoteTransfer)) {
            return $this->getFailedRedirectResponse();
        }

        $amazonPaymentTransfer = $this->buildAmazonPaymentTransfer($request);

        if (!$amazonPaymentTransfer) {
            return $this->getFailedRedirectResponse();
        }

        $quoteTransfer->setAmazonpayPayment($amazonPaymentTransfer);
        $quoteTransfer = $this->getClient()->handleCartWithAmazonpay($quoteTransfer);
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);

        $cartItems = $this->getFactory()->createProductBundleGrouper()->getGroupedBundleItems(
            $quoteTransfer->getItems(),
            $quoteTransfer->getBundleItems()
        );

        return [
            self::QUOTE_TRANSFER => $quoteTransfer,
            self::CART_ITEMS => $cartItems,
            self::AMAZONPAY_CONFIG => $this->getAmazonPayConfig(),
        ];
    }

    /**
     * @param Request $request
     *
     * @return AmazonpayPaymentTransfer|null
     */
    protected function buildAmazonPaymentTransfer(Request $request)
    {
        try {
            $amazonPaymentTransfer = new AmazonpayPaymentTransfer();
            $amazonPaymentTransfer->setOrderReferenceId($request->query->get(static::URL_PARAM_REFERENCE_ID));
            $amazonPaymentTransfer->setAddressConsentToken($request->query->get(static::URL_PARAM_ACCESS_TOKEN));

            return $amazonPaymentTransfer;
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isAllowedCheckout(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getTotals() !== null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setOrderReferenceAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if ($this->notAnAmazonPayment($quoteTransfer)) {
            return $this->getFailedRedirectResponse();
        }

        $quoteTransfer->getAmazonpayPayment()->setOrderReferenceId($request->request->get(static::URL_PARAM_REFERENCE_ID));

        return new JsonResponse([self::SUCCESS => true]);
    }

    /**
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function getShipmentMethodsAction()
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if ($this->notAnAmazonPayment($quoteTransfer)) {
            return $this->getFailedRedirectResponse();
        }

        $quoteTransfer = $this->getClient()->addSelectedAddressToQuote($quoteTransfer);
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);
        $shipmentMethods = $this->getFactory()->getShipmentClient()->getAvailableMethods($quoteTransfer);

        return [
            self::SHIPMENT_METHODS => $shipmentMethods->getMethods(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateShipmentMethodAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if ($this->notAnAmazonPayment($quoteTransfer)) {
            return $this->getFailedRedirectResponse();
        }

        $quoteTransfer->getShipment()->setShipmentSelection(
            (int)$request->request->get(static::URL_PARAM_SHIPMENT_METHOD_ID)
        );
        $quoteTransfer = $this->getClient()->addSelectedShipmentMethodToQuote($quoteTransfer);
        $quoteTransfer = $this->getFactory()->getCalculationClient()->recalculate($quoteTransfer);
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);

        return [
            self::QUOTE_TRANSFER => $quoteTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmPurchaseAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if ($this->notAnAmazonPayment($quoteTransfer)) {
            return $this->getFailedRedirectResponse();
        }

        $quoteTransfer = $this->getClient()->confirmPurchase($quoteTransfer);
        $quoteTransfer = $this->getFactory()->getCalculationClient()->recalculate($quoteTransfer);
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);

        if ($quoteTransfer->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus()
            ->getIsPaymentMethodInvalid()
        ) {
            return $this->redirectResponseInternal(AmazonpayControllerProvider::CHANGE_PAYMENT_METHOD);
        }

        if (!$quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            return $this->getFailedRedirectResponse(
                $quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getErrorCode()
            );
        }

        $checkoutResponseTransfer = $this->getFactory()->getCheckoutClient()->placeOrder($quoteTransfer);

        if ($checkoutResponseTransfer->getIsSuccess()) {
            return $this->redirectResponseInternal(AmazonpayControllerProvider::SUCCESS);
        }

        return $this->getFailedRedirectResponse('amazonpay.payment.place-order.failed');
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function notAnAmazonPayment(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getAmazonpayPayment() === null;
    }

    /**
     * @param string|null $message
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getFailedRedirectResponse($message = null)
    {
        $this->addErrorMessage($message ?? 'amazonpay.payment.failed');

        return $this->redirectResponseInternal($this->getPaymentRejectRoute());
    }

    /**
     * @return string
     */
    protected function getPaymentRejectRoute()
    {
        return Config::get(AmazonpayConstants::PAYMENT_REJECT_ROUTE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function changePaymentMethodAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        return [
            self::QUOTE_TRANSFER => $quoteTransfer,
            self::AMAZONPAY_CONFIG => $this->getAmazonPayConfig(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function successAction(Request $request)
    {
        $this->getFactory()->getQuoteClient()->clearQuote();

        $isAsynchronous =
            ($this->getFactory()->getConfig()->getAuthTransactionTimeout() > 0)
            && (!$this->getFactory()->getConfig()->getCaptureNow());

        return [
            self::IS_ASYNCHRONOUS => $isAsynchronous,
            self::AMAZONPAY_CONFIG => $this->getAmazonPayConfig(),
        ];
    }

    /**
     * @return \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface
     */
    protected function getAmazonPayConfig()
    {
        return $this->getFactory()->getConfig();
    }

}
