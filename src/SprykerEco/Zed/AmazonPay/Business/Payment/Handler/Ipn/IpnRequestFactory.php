<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;
use SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Authorize\IpnPaymentAuthorizeClosedHandler;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Authorize\IpnPaymentAuthorizeDeclineHandler;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Authorize\IpnPaymentAuthorizeOpenHandler;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Authorize\IpnPaymentAuthorizeSuspendedHandler;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Capture\IpnPaymentCaptureCompletedHandler;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Capture\IpnPaymentCaptureDeclineHandler;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\OrderReference\IpnOrderReferenceCancelledHandler;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\OrderReference\IpnOrderReferenceClosedHandler;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\OrderReference\IpnOrderReferenceOpenHandler;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\OrderReference\IpnOrderReferenceSuspendedHandler;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Refund\IpnPaymentRefundCompletedHandler;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Refund\IpnPaymentRefundDeclineHandler;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface;
use SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface;

class IpnRequestFactory implements IpnRequestFactoryInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface
     */
    protected $amazonpayQueryContainer;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface
     */
    protected $ipnRequestLogger;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface
     */
    protected $refundOrderModel;

    /**
     * @var AmazonPayConfigInterface
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface $omsFacade
     * @param \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface $amazonpayQueryContainer
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface $ipnRequestLogger
     * @param \SprykerEco\Zed\AmazonPay\Business\Order\RefundOrderInterface $refundOrderModel
     * @param AmazonPayConfigInterface $config
     */
    public function __construct(
        AmazonPayToOmsInterface $omsFacade,
        AmazonPayQueryContainerInterface $amazonpayQueryContainer,
        IpnRequestLoggerInterface $ipnRequestLogger,
        RefundOrderInterface $refundOrderModel,
        AmazonPayConfigInterface $config
    ) {
        $this->omsFacade = $omsFacade;
        $this->amazonpayQueryContainer = $amazonpayQueryContainer;
        $this->ipnRequestLogger = $ipnRequestLogger;
        $this->refundOrderModel = $refundOrderModel;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    public function getConcreteIpnRequestHandler(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer)
    {
        $handlerMap = $this->getNotificationTypeToHandlerMap();
        $notificationType = $paymentRequestTransfer->getMessage()->getNotificationType();

        return $handlerMap[$notificationType]($paymentRequestTransfer);
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface[]
     */
    protected function getNotificationTypeToHandlerMap()
    {
        return [
            AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE => function (AmazonpayIpnPaymentRequestTransfer $ipnRequest) {
                return $this->getIpnPaymentAuthorizeHandler($ipnRequest);
            },
            AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_CAPTURE => function (AmazonpayIpnPaymentRequestTransfer $ipnRequest) {
                return $this->getIpnPaymentCaptureHandler($ipnRequest);
            },
            AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_REFUND => function (AmazonpayIpnPaymentRequestTransfer $ipnRequest) {
                return $this->getIpnPaymentRefundHandler($ipnRequest);
            },
            AmazonPayConfig::IPN_REQUEST_TYPE_ORDER_REFERENCE_NOTIFICATION => function (AmazonpayIpnPaymentRequestTransfer $ipnRequest) {
                return $this->getIpnOrderReferenceHandler($ipnRequest);
            },
        ];
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface[]
     */
    protected function getAuthorizeHandlerMap()
    {
        return [
            AmazonPayConfig::STATUS_TRANSACTION_TIMED_OUT => function () {
                return $this->createIpnPaymentAuthorizeSuspendedHandler();
            },
            AmazonPayConfig::STATUS_SUSPENDED => function () {
                return $this->createIpnPaymentAuthorizeSuspendedHandler();
            },
            AmazonPayConfig::STATUS_PAYMENT_METHOD_INVALID => function () {
                return $this->createIpnPaymentAuthorizeSuspendedHandler();
            },
            AmazonPayConfig::STATUS_DECLINED => function () {
                return $this->createIpnPaymentAuthorizeDeclineHandler();
            },
            AmazonPayConfig::STATUS_OPEN => function () {
                return $this->createIpnPaymentAuthorizeOpenHandler();
            },
            AmazonPayConfig::STATUS_CLOSED => function () {
                return $this->createIpnPaymentAuthorizeClosedHandler();
            },
            AmazonPayConfig::STATUS_EXPIRED => function () {
                return $this->createIpnPaymentAuthorizeClosedHandler();
            },
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $ipnRequest
     *
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function getIpnPaymentAuthorizeHandler(AmazonpayIpnPaymentRequestTransfer $ipnRequest)
    {
        $authStatus = $ipnRequest->getAuthorizationDetails()->getAuthorizationStatus()->getState();

        return $this->getAuthorizeHandlerMap()[$authStatus]();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentAuthorizeSuspendedHandler()
    {
        return new IpnPaymentAuthorizeSuspendedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentAuthorizeDeclineHandler()
    {
            return new IpnPaymentAuthorizeDeclineHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger,
                $this->config
            );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentAuthorizeOpenHandler()
    {
        return new IpnPaymentAuthorizeOpenHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentAuthorizeClosedHandler()
    {
        return new IpnPaymentAuthorizeClosedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface[]
     */
    protected function getCaptureHandlerMap()
    {
        return [
            AmazonPayConfig::STATUS_DECLINED => function () {
                return $this->createIpnPaymentCaptureDeclineHandler();
            },
            AmazonPayConfig::STATUS_COMPLETED => function () {
                return $this->createIpnPaymentCaptureCompletedHandler();
            },
            AmazonPayConfig::STATUS_CLOSED => function () {
                return $this->createIpnEmptyHandler();
            },
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $ipnRequest
     *
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function getIpnPaymentCaptureHandler(AmazonpayIpnPaymentRequestTransfer $ipnRequest)
    {
        $captureStatus = $ipnRequest->getCaptureDetails()->getCaptureStatus()->getState();

        return $this->getCaptureHandlerMap()[$captureStatus]();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentCaptureDeclineHandler()
    {
        return new IpnPaymentCaptureDeclineHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentCaptureCompletedHandler()
    {
        return new IpnPaymentCaptureCompletedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnEmptyHandler()
    {
        return new IpnEmptyHandler();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface[]
     */
    protected function getRefundHandlerMap()
    {
        return [
            AmazonPayConfig::STATUS_DECLINED => function () {
                return $this->createIpnPaymentRefundDeclineHandler();
            },
            AmazonPayConfig::STATUS_COMPLETED => function () {
                return $this->createIpnPaymentRefundCompletedHandler();
            },
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $ipnRequest
     *
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function getIpnPaymentRefundHandler(AmazonpayIpnPaymentRequestTransfer $ipnRequest)
    {
        $refundStatus = $ipnRequest->getRefundDetails()->getRefundStatus()->getState();

        return $this->getRefundHandlerMap()[$refundStatus]();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentRefundDeclineHandler()
    {
        return new IpnPaymentRefundDeclineHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentRefundCompletedHandler()
    {
        return new IpnPaymentRefundCompletedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->config,
            $this->refundOrderModel
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface[]
     */
    protected function getOrderReferenceHandlerMap()
    {
        return [
            AmazonPayConfig::STATUS_OPEN => function () {
                return $this->createIpnOrderReferenceOpenHandler();
            },
            AmazonPayConfig::STATUS_AMAZON_CLOSED => function () {
                return $this->createIpnOrderReferenceClosedHandler();
            },
            AmazonPayConfig::STATUS_EXPIRED => function () {
                return $this->createIpnPaymentAuthorizeClosedHandler();
            },
            AmazonPayConfig::STATUS_CLOSED => function () {
                return $this->createIpnEmptyHandler();
            },
            AmazonPayConfig::STATUS_PAYMENT_METHOD_INVALID => function () {
                return $this->createIpnOrderReferenceSuspendedHandler();
            },
            AmazonPayConfig::STATUS_SUSPENDED => function () {
                return $this->createIpnOrderReferenceSuspendedHandler();
            },
            AmazonPayConfig::STATUS_CANCELLED => function () {
                return $this->createIpnOrderReferenceCancelledHandler();
            },
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $ipnRequest
     *
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function getIpnOrderReferenceHandler(AmazonpayIpnPaymentRequestTransfer $ipnRequest)
    {
        $orderReferenceStatus = $ipnRequest->getOrderReferenceStatus()->getState();

        return $this->getOrderReferenceHandlerMap()[$orderReferenceStatus]();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnOrderReferenceOpenHandler()
    {
        return new IpnOrderReferenceOpenHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnOrderReferenceClosedHandler()
    {
        return new IpnOrderReferenceClosedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnOrderReferenceSuspendedHandler()
    {
        return new IpnOrderReferenceSuspendedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnOrderReferenceCancelledHandler()
    {
        return new IpnOrderReferenceCancelledHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->config
        );
    }
}
