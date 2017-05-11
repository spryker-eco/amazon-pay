<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Amazonpay\Controller;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use SprykerEco\Yves\Amazonpay\Plugin\Provider\AmazonpayControllerProvider;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Yves\Amazonpay\AmazonpayFactory getFactory()
 * @method \SprykerEco\Client\Amazonpay\AmazonpayClient getClient()
 */
class PaymentController extends AbstractController
{

    const URL_PARAM_REFERENCE_ID = 'reference_id';
    const URL_PARAM_ACCESS_TOKEN = 'access_token';
    const URL_PARAM_SHIPMENT_METHOD_ID = 'shipment_method_id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function checkoutAction(Request $request)
    {
        $amazonPaymentTransfer = new AmazonpayPaymentTransfer();
        $amazonPaymentTransfer->setOrderReferenceId($request->query->get(static::URL_PARAM_REFERENCE_ID));
        $amazonPaymentTransfer->setAddressConsentToken($request->query->get(static::URL_PARAM_ACCESS_TOKEN));

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer->setAmazonpayPayment($amazonPaymentTransfer);
        $quoteTransfer = $this->getClient()->handleCartWithAmazonpay($quoteTransfer);
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);

        return [
            'quoteTransfer' => $quoteTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function setOrderReferenceAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer->getAmazonpayPayment()->setOrderReferenceId($request->request->get(static::URL_PARAM_REFERENCE_ID));

        return new JsonResponse(['success' => true]);
    }

    /**
     * @return array
     */
    public function getShipmentMethodsAction()
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer = $this->getClient()->addSelectedAddressToQuote($quoteTransfer);
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);
        $shipmentMethods = $this->getFactory()->getShipmentClient()->getAvailableMethods($quoteTransfer);

        return [
            'shipmentMethods' => $shipmentMethods->getMethods(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function updateShipmentMethodAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer->getShipment()->setShipmentSelection(
            (int)$request->request->get(static::URL_PARAM_SHIPMENT_METHOD_ID)
        );
        $quoteTransfer = $this->getClient()->addSelectedShipmentMethodToQuote($quoteTransfer);
        $quoteTransfer = $this->getFactory()->getCalculationClient()->recalculate($quoteTransfer);
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);

        return [
            'quoteTransfer' => $quoteTransfer,
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

        if (!$quoteTransfer) {
            return $this->getFailedRedirectResponse();
        }

        $quoteTransfer = $this->getClient()->confirmPurchase($quoteTransfer);
        $quoteTransfer = $this->getFactory()->getCalculationClient()->recalculate($quoteTransfer);
        $this->getFactory()->getQuoteClient()->setQuote($quoteTransfer);

        if ($quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getIsSuccess()) {
            if (!$quoteTransfer->getAmazonpayPayment()
                    ->getAuthorizationDetails()
                    ->getAuthorizationStatus()
                    ->getIsDeclined()
            ) {
                $checkoutResponseTransfer = $this->getFactory()->getCheckoutClient()->placeOrder($quoteTransfer);

                if ($checkoutResponseTransfer->getIsSuccess()) {
                    return $this->redirectResponseInternal(AmazonpayControllerProvider::SUCCESS);
                }

                return new Response('Persisting Order Error');
            }

            if ($quoteTransfer->getAmazonpayPayment()
                    ->getAuthorizationDetails()
                    ->getAuthorizationStatus()
                    ->getIsPaymentMethodInvalid()
            ) {
                return $this->redirectResponseInternal(AmazonpayControllerProvider::CHANGE_PAYMENT_METHOD);
            } else {
                return $this->getFailedRedirectResponse();
            }
        }

        if ($quoteTransfer->getAmazonpayPayment()->getResponseHeader()->getConstraints()) {
            $this->addErrorMessage($this->getApplication()->trans('amazonpay.payment.wrong'));
            return $this->redirectResponseExternal($request->headers->get('referer'));
        }

        return $this->getFailedRedirectResponse();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getFailedRedirectResponse()
    {
        $this->addErrorMessage($this->getApplication()->trans('amazonpay.payment.failed'));

        return $this->redirectResponseInternal('cart');
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
            'quoteTransfer' => $quoteTransfer,
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

        return [];
    }

}
