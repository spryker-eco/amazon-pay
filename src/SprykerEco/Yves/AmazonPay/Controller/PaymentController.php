<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay\Controller;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Shared\AmazonPay\AmazonPayConstants;
use SprykerEco\Yves\AmazonPay\Plugin\Provider\AmazonPayControllerProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Client\AmazonPay\AmazonPayClientInterface getClient()
 * @method \SprykerEco\Yves\AmazonPay\AmazonPayFactory getFactory()
 */
class PaymentController extends AbstractController
{
    const URL_PARAM_REFERENCE_ID = 'reference_id';
    const URL_PARAM_ACCESS_TOKEN = 'access_token';
    const URL_PARAM_SHIPMENT_METHOD_ID = 'shipment_method_id';
    const QUOTE_TRANSFER = 'quoteTransfer';
    const SHIPMENT_METHODS = 'shipmentMethods';
    const SELECTED_SHIPMENT_METHOD_ID = 'selectedShipmentMethodId';
    const AMAZONPAY_CONFIG = 'amazonpayConfig';
    const IS_ASYNCHRONOUS = 'isAsynchronous';
    const CART_ITEMS = 'cartItems';
    const SUCCESS = 'success';
    const ERROR_AMAZONPAY_PAYMENT_FAILED = 'amazonpay.payment.failed';
    const IS_AMAZON_PAYMENT_INVALID = 'isAmazonPaymentInvalid';
    const ADDRESS_BOOK_MODE = 'addressBookMode';
    const ORDER_REFERENCE = 'orderReferenceId';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function checkoutAction(Request $request)
    {
        $response = $this->executeCheckoutAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@AmazonPay/views/checkout/checkout.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCheckoutAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        if (!$this->isAllowedCheckout($quoteTransfer) || !$this->isRequestComplete($request)) {
            $this->addErrorFromQuote($quoteTransfer);

            return $this->buildRedirectInternalResponse();
        }

        $this->storeAmazonPaymentIntoQuote($request, $quoteTransfer);

        $data = [
            static::QUOTE_TRANSFER => $quoteTransfer,
            static::CART_ITEMS => $this->getCartItems($quoteTransfer),
            static::AMAZONPAY_CONFIG => $this->getAmazonPayConfig(),
        ];

        if ($this->isAmazonPaymentInvalid($quoteTransfer)) {
            $data[static::ORDER_REFERENCE] = $this->getAmazonPaymentOrderReferenceId($quoteTransfer);
            $data[static::ADDRESS_BOOK_MODE] = AmazonPayConfig::DISPLAY_MODE_READONLY;
        }

        return $data;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function setOrderReferenceAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        if (!$this->isAmazonPayment($quoteTransfer)) {
            return $this->buildRedirectInternalResponse();
        }

        $quoteTransfer->getAmazonpayPayment()
            ->setOrderReferenceId(
                $request->request->get(static::URL_PARAM_REFERENCE_ID)
            );

        return new JsonResponse([static::SUCCESS => true]);
    }


<<<<<<< Updated upstream
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getShipmentMethodsAction(Request $request)
    {
        $response = $this->executeShipmentMethodsAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@AmazonPay/views/get-shipment-methods/get-shipment-methods.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
=======
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getShipmentMethodsAction(Request $request)
    {
        $response = $this->executeShipmentMethodsAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@AmazonPay/views/get-shipment-methods/get-shipment-methods.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
>>>>>>> Stashed changes
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeShipmentMethodsAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        if (!$this->isAmazonPayment($quoteTransfer)) {
            $this->addErrorFromQuote($quoteTransfer);

            return $this->buildRedirectInternalResponse();
        }

        $quoteTransfer = $this->getClient()
            ->addSelectedAddressToQuote($quoteTransfer);
        $this->saveQuoteIntoSession($quoteTransfer);
        $shipmentMethods = $this->getFactory()
            ->getShipmentClient()
            ->getAvailableMethods($quoteTransfer);

        return [
            static::SELECTED_SHIPMENT_METHOD_ID => $this->getCurrentShipmentMethodId($quoteTransfer),
            static::SHIPMENT_METHODS => $shipmentMethods->getMethods(),
            static::IS_AMAZON_PAYMENT_INVALID => $this->isAmazonPaymentInvalid($quoteTransfer),
        ];
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateShipmentMethodAction(Request $request)
    {
        $response = $this->executeShipmentMethodAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@AmazonPay/views/shipment-method/shipment-method.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeShipmentMethodAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if (!$this->isAmazonPayment($quoteTransfer)) {
            $this->addErrorFromQuote($quoteTransfer);

            return $this->buildRedirectInternalResponse();
        }
        
        $quoteTransfer->getShipment()->setShipmentSelection(
            $request->request->get(static::URL_PARAM_SHIPMENT_METHOD_ID)
        );
        $quoteTransfer = $this->getClient()
            ->addSelectedShipmentMethodToQuote($quoteTransfer);
        $quoteTransfer = $this->getFactory()
            ->getCalculationClient()->recalculate($quoteTransfer);
        $this->saveQuoteIntoSession($quoteTransfer);

        return [
            static::QUOTE_TRANSFER => $quoteTransfer,
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

        if (!$this->isAmazonPayment($quoteTransfer)) {
            $this->addErrorFromQuote($quoteTransfer);

            return $this->buildRedirectInternalResponse();
        }

        $quoteTransfer = $this->getClient()->confirmPurchase($quoteTransfer);

        if (!$quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            $this->addErrorFromQuote($quoteTransfer);
            $this->saveQuoteIntoSession($quoteTransfer);

            if ($this->isLogoutRedirect($quoteTransfer)) {
                return $this->buildRedirectInternalResponse();
            }

            return $this->buildRedirectExternalResponse($request);
        }

        $quoteTransfer = $this->getFactory()->getCalculationClient()->recalculate($quoteTransfer);
        $this->saveQuoteIntoSession($quoteTransfer);

        $checkoutResponseTransfer = $this->getFactory()->getCheckoutClient()->placeOrder($quoteTransfer);

        if ($checkoutResponseTransfer->getIsSuccess()) {
            return $this->redirectResponseInternal(AmazonPayControllerProvider::SUCCESS);
        }

        $this->addErrorFromQuote($quoteTransfer);

        return $this->buildRedirectInternalResponse();
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successAction(Request $request)
    {
        $response = $this->executeSuccessAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@AmazonPay/views/success/success.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeSuccessAction(Request $request)
    {
        $this->getFactory()->getCustomerClient()->markCustomerAsDirty();
        $this->getFactory()->getCartClient()->clearQuote();

        return [
            static::IS_ASYNCHRONOUS => $this->isAsynchronous(),
            static::AMAZONPAY_CONFIG => $this->getAmazonPayConfig(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function storeAmazonPaymentIntoQuote(Request $request, QuoteTransfer $quoteTransfer)
    {
        $amazonPaymentTransfer = $this->buildAmazonPaymentTransfer($request);

        $quoteTransfer->setAmazonpayPayment($amazonPaymentTransfer);
        $quoteTransfer = $this->getClient()
            ->handleCartWithAmazonPay($quoteTransfer);
        $this->saveQuoteIntoSession($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function saveQuoteIntoSession(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->getQuoteClient()
            ->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function getCurrentShipmentMethodId(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getShipment() === null || $quoteTransfer->getShipment()->getMethod() === null) {
            return null;
        }

        return $quoteTransfer->getShipment()->getMethod()->getIdShipmentMethod();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getCartItems(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getItems();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isRequestComplete(Request $request)
    {
        return (
            $request->query->get(static::URL_PARAM_REFERENCE_ID) !== null &&
            $request->query->get(static::URL_PARAM_ACCESS_TOKEN) !== null
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\AmazonpayPaymentTransfer
     */
    protected function buildAmazonPaymentTransfer(Request $request)
    {
        $amazonPaymentTransfer = new AmazonpayPaymentTransfer();
        $amazonPaymentTransfer->setOrderReferenceId($request->query->get(static::URL_PARAM_REFERENCE_ID));
        $amazonPaymentTransfer->setAddressConsentToken($request->query->get(static::URL_PARAM_ACCESS_TOKEN));

        return $amazonPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isAllowedCheckout(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getTotals() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isLogoutRedirect(QuoteTransfer $quoteTransfer)
    {
        if ($this->isAmazonPaymentInvalid($quoteTransfer)) {
            return false;
        }

        if ($this->getAmazonPayConfig()->getCaptureNow() &&
            $quoteTransfer->getAmazonpayPayment() !== null
            && $quoteTransfer->getAmazonpayPayment()->getResponseHeader() !== null
            && !$quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function buildRedirectExternalResponse(Request $request)
    {
        if ($request->headers->get('Referer') === null) {
            return $this->buildRedirectInternalResponse();
        }

        return $this->redirectResponseExternal($request->headers->get('Referer'));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addErrorFromQuote(QuoteTransfer $quoteTransfer)
    {
        $this->addErrorMessage(
            $this->getErrorMessageFromQuote($quoteTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isAmazonPayment(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getAmazonpayPayment() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getErrorMessageFromQuote(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getAmazonpayPayment() === null
            || $quoteTransfer->getAmazonpayPayment()->getResponseHeader() === null
            || $quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getErrorMessage() === null) {
            return static::ERROR_AMAZONPAY_PAYMENT_FAILED;
        }

        return $quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getErrorMessage();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function buildRedirectInternalResponse()
    {
        return $this->redirectResponseInternal($this->getPaymentRejectRoute());
    }

    /**
     * @return string
     */
    protected function getPaymentRejectRoute()
    {
        return Config::get(AmazonPayConstants::PAYMENT_REJECT_ROUTE);
    }

    /**
     * @return bool
     */
    protected function isAsynchronous()
    {
        return $this->getAmazonPayConfig()->getAuthTransactionTimeout() > 0
            && !$this->getAmazonPayConfig()->getCaptureNow();
    }

    /**
     * @return \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface
     */
    protected function getAmazonPayConfig()
    {
        return $this->getFactory()->createAmazonPayConfig();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isAmazonPaymentInvalid(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getAmazonpayPayment()->getResponseHeader() !== null
            && $quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getIsInvalidPaymentMethod()) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return null|string
     */
    protected function getAmazonPaymentOrderReferenceId(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getAmazonpayPayment() !== null && $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId() !== null) {
            return $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId();
        }

        return null;
    }
}
