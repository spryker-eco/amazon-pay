<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\AmazonPay\Controller;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Shared\AmazonPay\AmazonPayConstants;
use SprykerEco\Yves\AmazonPay\Plugin\Provider\AmazonPayControllerProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Client\AmazonPay\AmazonPayClientInterface getClient()
 * @method \SprykerEco\Yves\AmazonPay\AmazonPayFactory getFactory()
 */
class PaymentController extends AbstractController
{
    public const URL_PARAM_REFERENCE_ID = 'reference_id';
    public const URL_PARAM_SELLER_ID = 'seller_id';
    public const URL_PARAM_ACCESS_TOKEN = 'access_token';
    public const URL_PARAM_SHIPMENT_METHOD_ID = 'shipment_method_id';
    public const QUOTE_TRANSFER = 'quoteTransfer';
    public const SHIPMENT_METHODS = 'shipmentMethods';
    public const SELECTED_SHIPMENT_METHOD_ID = 'selectedShipmentMethodId';
    public const AMAZONPAY_CONFIG = 'amazonpayConfig';
    public const IS_ASYNCHRONOUS = 'isAsynchronous';
    public const CART_ITEMS = 'cartItems';
    public const SUCCESS = 'success';
    public const ERROR_AMAZONPAY_PAYMENT_FAILED = 'amazonpay.payment.failed';
    public const IS_AMAZON_PAYMENT_INVALID = 'isAmazonPaymentInvalid';
    public const ADDRESS_BOOK_MODE = 'addressBookMode';
    public const ORDER_REFERENCE = 'orderReferenceId';

    public const PSD2_DATA = 'psd2Data';
    public const PSD2_DATA_KEY_AJAX_ENDPOINT = 'psd2AjaxEndpoint';
    public const PSD2_DATA_KEY_SELLER_ID = 'amazonSellerId';
    public const PSD2_DATA_KEY_AMAZON_ORDER_REFERENCE_ID = 'amazonOrderReferenceId';
    public const PSD2_DATA_KEY_AMAZON_FAILURE_URL = 'amazonFailureUrl';

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
            $this->addAmazonPayErrorFromQuote($quoteTransfer);

            return $this->buildRedirectInternalResponse();
        }

        $this->storeAmazonPaymentIntoQuote($request, $quoteTransfer);

        $data = [
            static::QUOTE_TRANSFER => $quoteTransfer,
            static::CART_ITEMS => $this->getCartItems($quoteTransfer),
            static::AMAZONPAY_CONFIG => $this->getAmazonPayConfig(),
            static::PSD2_DATA => $this->preparePSD2Data($quoteTransfer),
        ];

        if ($this->isAmazonPaymentInvalid($quoteTransfer)) {
            $data[static::ORDER_REFERENCE] = $this->getAmazonPaymentOrderReferenceId($quoteTransfer);
            $data[static::ADDRESS_BOOK_MODE] = AmazonPayConfig::DISPLAY_MODE_READONLY;
        }

        if ($quoteTransfer->getAmazonpayPayment()->getOrderReferenceStatus()) {
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

        $this->saveQuote($quoteTransfer);

        return $this->createJsonResponse([static::SUCCESS => true]);
    }

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmPurchaseAction(Request $request): Response
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer = $this->getClient()->confirmPurchase($quoteTransfer);

        $amazonpayPaymentTransfer = $quoteTransfer->getAmazonpayPayment();

        if (!$amazonpayPaymentTransfer->getResponseHeader()->getIsSuccess()
            && $amazonpayPaymentTransfer->getResponseHeader()->getConstraints()->count()
        ) {
            $this->addErrorMessage($amazonpayPaymentTransfer->getResponseHeader()->getErrorMessage());
            $payload = [
                static::SUCCESS => false,
                'url' => $this->getApplication()->url(AmazonPayControllerProvider::CHECKOUT, [
                    static::URL_PARAM_REFERENCE_ID => $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId(),
                    static::URL_PARAM_ACCESS_TOKEN => $quoteTransfer->getAmazonpayPayment()->getAddressConsentToken(),
                ]),
            ];

            return $this->createJsonResponse($payload, 302);
        }

        $this->saveQuote($quoteTransfer);

        if (!$this->isOrderStatusOpen($quoteTransfer)) {
            return $this->createJsonResponse([static::SUCCESS => false], 400);
        }

        return $this->createJsonResponse([static::SUCCESS => true]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeShipmentMethodsAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        if (!$this->isAmazonPayment($quoteTransfer)) {
            $this->addAmazonPayErrorFromQuote($quoteTransfer);

            return $this->buildRedirectInternalResponse();
        }

        $quoteTransfer = $this->getClient()
            ->addSelectedAddressToQuote($quoteTransfer);
        $this->saveQuote($quoteTransfer);
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
            $this->addAmazonPayErrorFromQuote($quoteTransfer);

            return $this->buildRedirectInternalResponse();
        }

        $quoteTransfer->getShipment()->setShipmentSelection(
            $request->request->get(static::URL_PARAM_SHIPMENT_METHOD_ID)
        );
        $quoteTransfer = $this->getClient()
            ->addSelectedShipmentMethodToQuote($quoteTransfer);
        $quoteTransfer = $this->getFactory()
            ->getCalculationClient()->recalculate($quoteTransfer);
        $this->saveQuote($quoteTransfer);

        return [
            static::QUOTE_TRANSFER => $quoteTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successAction(Request $request)
    {
        $response = $this->executeSuccessAction($request);

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if ($quoteTransfer->getTotals() === null) {
            return $this->view($response, [], '@AmazonPay/views/success/success.twig');
        }

        $checkoutResponseTransfer = $this->getFactory()->getCheckoutClient()->placeOrder($quoteTransfer);

        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->redirectToAmazonCheckoutPage($quoteTransfer);
        }

        $quoteTransfer = $this->getClient()->authorizeOrder($quoteTransfer);

        if ($this->isAuthorizationExpired($quoteTransfer)) {
            $quoteTransfer = $this->reauthorizeOrder($quoteTransfer);
        }

        $this->saveQuote($quoteTransfer);

        $authorizationStatusTransfer = $this->getAuthorizationTransfer($quoteTransfer);

        if ($this->isAuthorizationFailed($authorizationStatusTransfer)) {
            return $this->redirectResponseInternal(AmazonPayControllerProvider::PAYMENT_FAILED);
        }

        if (!$this->isAuthSucceeded($authorizationStatusTransfer) || $checkoutResponseTransfer->getIsSuccess() === false) {
            return $this->redirectToAmazonCheckoutPage($quoteTransfer);
        }

        $this->getFactory()->getCustomerClient()->markCustomerAsDirty();
        $this->getFactory()->getCartClient()->clearQuote();

        return $this->view($response, [], '@AmazonPay/views/success/success.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeSuccessAction(Request $request)
    {
        return [
            static::IS_ASYNCHRONOUS => $this->isAsynchronous(),
            static::AMAZONPAY_CONFIG => $this->getAmazonPayConfig(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paymentFailedAction(Request $request): Response
    {
        $this->getFactory()->getMessengerClient()->addErrorMessage(static::ERROR_AMAZONPAY_PAYMENT_FAILED);

        $this->clearAmazonpayQuoteData();

        return $this->buildRedirectInternalResponse();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function storeAmazonPaymentIntoQuote(Request $request, QuoteTransfer $quoteTransfer)
    {
        $amazonPaymentTransfer = $this->buildAmazonPaymentTransfer($request, $quoteTransfer);

        $quoteTransfer->setAmazonpayPayment($amazonPaymentTransfer);
        $quoteTransfer = $this->getClient()
            ->handleCartWithAmazonPay($quoteTransfer);
        $this->saveQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function saveQuote(QuoteTransfer $quoteTransfer)
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayPaymentTransfer
     */
    protected function buildAmazonPaymentTransfer(Request $request, QuoteTransfer $quoteTransfer)
    {
        $amazonPaymentTransfer = $quoteTransfer->getAmazonpayPayment() ?: new AmazonpayPaymentTransfer();

        $amazonPaymentTransfer->setOrderReferenceId($request->query->get(static::URL_PARAM_REFERENCE_ID));
        $amazonPaymentTransfer->setAddressConsentToken($request->query->get(static::URL_PARAM_ACCESS_TOKEN));
        $amazonPaymentTransfer->setFailureMFARedirectUrl($this->getApplication()->url(AmazonPayControllerProvider::CHECKOUT, [
            static::URL_PARAM_REFERENCE_ID => $request->query->get(static::URL_PARAM_REFERENCE_ID),
            static::URL_PARAM_ACCESS_TOKEN => $request->query->get(static::URL_PARAM_ACCESS_TOKEN),
        ]));
        $amazonPaymentTransfer->setSuccessMFARedirectUrl($this->getApplication()->url(AmazonPayControllerProvider::SUCCESS));

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
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function setCheckoutErrorMessages(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        foreach ($checkoutResponseTransfer->getErrors() as $checkoutErrorTransfer) {
            $this->addErrorMessage(
                $this->translateCheckoutErrorMessage($checkoutErrorTransfer)
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function translateCheckoutErrorMessage(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
        $checkoutErrorMessage = $checkoutErrorTransfer->getMessage();

        return $this->getFactory()->getGlossaryStorageClient()->translate(
            $checkoutErrorMessage,
            $this->getLocale(),
            $checkoutErrorTransfer->getParameters()
        ) ?: $checkoutErrorMessage;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addAmazonPayErrorFromQuote(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getAmazonpayPayment() === null
            || $quoteTransfer->getAmazonpayPayment()->getResponseHeader() === null
            || $quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getErrorMessage() === null) {
            return;
        }

        $this->addErrorMessage(
            $quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getErrorMessage()
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
     * @return string|null
     */
    protected function getAmazonPaymentOrderReferenceId(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getAmazonpayPayment() !== null && $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId() !== null) {
            return $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function preparePSD2Data(QuoteTransfer $quoteTransfer): array
    {
        return [
            static::PSD2_DATA_KEY_AJAX_ENDPOINT => $this->getApplication()->path(AmazonPayControllerProvider::CONFIRM_PURCHASE),
            static::PSD2_DATA_KEY_SELLER_ID => $this->getAmazonPayConfig()->getSellerId(),
            static::PSD2_DATA_KEY_AMAZON_ORDER_REFERENCE_ID => $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            static::PSD2_DATA_KEY_AMAZON_FAILURE_URL => $this->getApplication()->path(AmazonPayControllerProvider::PAYMENT_FAILED),
        ];
    }

    /**
     * @return void
     */
    protected function clearAmazonpayQuoteData(): void
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        $amazonpayPaymentTransfer = new AmazonpayPaymentTransfer();

        $quoteTransfer->setAmazonpayPayment($amazonpayPaymentTransfer);

        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $amazonpayStatusTransfer
     *
     * @return bool
     */
    protected function isAuthSucceeded(AmazonpayStatusTransfer $amazonpayStatusTransfer): bool
    {
        return in_array($amazonpayStatusTransfer->getState(), [
            AmazonPayConfig::STATUS_OPEN,
            AmazonPayConfig::STATUS_PENDING,
            AmazonPayConfig::STATUS_CLOSED,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isOrderStatusOpen(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getAmazonpayPayment()->getOrderReferenceStatus()->getState() === AmazonPayConfig::STATUS_OPEN;
    }

    /**
     * @param array $payload
     * @param int $statusCode
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createJsonResponse(array $payload, int $statusCode = 200): JsonResponse
    {
        return new JsonResponse($payload, $statusCode);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $amazonpayStatusTransfer
     *
     * @return bool
     */
    protected function isAuthorizationFailed(AmazonpayStatusTransfer $amazonpayStatusTransfer): bool
    {
        return in_array($amazonpayStatusTransfer->getState(), [
            AmazonPayConfig::STATUS_TRANSACTION_TIMED_OUT,
            AmazonPayConfig::STATUS_DECLINED,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isAuthorizationExpired(QuoteTransfer $quoteTransfer): bool
    {
        $authorizationStatusTransfer = $this->getAuthorizationTransfer($quoteTransfer);

        return $authorizationStatusTransfer->getState() === AmazonPayConfig::STATUS_TRANSACTION_TIMED_OUT
        && $authorizationStatusTransfer->getReasonCode() === AmazonPayConfig::REASON_CODE_TRANSACTION_TIMED_OUT
        && !$this->getAmazonPayConfig()->getCaptureNow();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function reauthorizeOrder(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->getAmazonpayPayment()->setIsReauthorizingAsync(true);

        return $this->getClient()->authorizeOrder($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayStatusTransfer
     */
    protected function getAuthorizationTransfer(QuoteTransfer $quoteTransfer): AmazonpayStatusTransfer
    {
        return $quoteTransfer
            ->getAmazonpayPayment()
            ->getAuthorizationDetails()
            ->getAuthorizationStatus();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToAmazonCheckoutPage(QuoteTransfer $quoteTransfer): RedirectResponse
    {
        return $this->redirectResponseInternal(AmazonPayControllerProvider::CHECKOUT, [
            static::URL_PARAM_REFERENCE_ID => $quoteTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            static::URL_PARAM_ACCESS_TOKEN => $quoteTransfer->getAmazonpayPayment()->getAddressConsentToken(),
        ]);
    }
}
