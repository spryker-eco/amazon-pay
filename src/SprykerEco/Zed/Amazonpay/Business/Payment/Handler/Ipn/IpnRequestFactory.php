<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;
use SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;

class IpnRequestFactory implements IpnRequestFactoryInterface
{
    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface
     */
    protected $amazonpayQueryContainer;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface
     */
    protected $ipnRequestLogger;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface
     */
    protected $refundOrderModel;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface $omsFacade
     * @param \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface $ipnRequestLogger
     * @param \SprykerEco\Zed\Amazonpay\Business\Order\RefundOrderInterface $refundOrderModel
     */
    public function __construct(
        AmazonpayToOmsInterface $omsFacade,
        AmazonpayQueryContainerInterface $amazonpayQueryContainer,
        IpnRequestLoggerInterface $ipnRequestLogger,
        RefundOrderInterface $refundOrderModel
    ) {
        $this->omsFacade = $omsFacade;
        $this->amazonpayQueryContainer = $amazonpayQueryContainer;
        $this->ipnRequestLogger = $ipnRequestLogger;
        $this->refundOrderModel = $refundOrderModel;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer | \Generated\Shared\Transfer\AmazonpayIpnPaymentAuthorizeRequestTransfer $ipnRequest
     *
     * @throws \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnHandlerNotFoundException
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    public function createConcreteIpnRequestHandler(AbstractTransfer $ipnRequest)
    {
        switch ($ipnRequest->getMessage()->getNotificationType()) {
            case AmazonpayConfig::IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE:
                return $this->createIpnPaymentAuthorizeHandler($ipnRequest);

            case AmazonpayConfig::IPN_REQUEST_TYPE_PAYMENT_CAPTURE:
                return $this->createIpnPaymentCaptureHandler($ipnRequest);

            case AmazonpayConfig::IPN_REQUEST_TYPE_PAYMENT_REFUND:
                return $this->createIpnPaymentRefundHandler($ipnRequest);

            case AmazonpayConfig::IPN_REQUEST_TYPE_ORDER_REFERENCE_NOTIFICATION:
                return $this->createIpnOrderReferenceHandler($ipnRequest);
        }

        throw new IpnHandlerNotFoundException('Unknown IPN Notification type: ' .
            $ipnRequest->getMessage()->getNotificationType());
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer | \Generated\Shared\Transfer\AmazonpayIpnPaymentAuthorizeRequestTransfer $ipnRequest
     *
     * @throws \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnHandlerNotFoundException
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentAuthorizeHandler(AbstractTransfer $ipnRequest)
    {
        if ($ipnRequest->getAuthorizationDetails()->getAuthorizationStatus()->getIsSuspended()) {
            return $this->createIpnPaymentAuthorizeSuspendedHandler();
        }

        if ($ipnRequest->getAuthorizationDetails()->getAuthorizationStatus()->getIsDeclined()) {
            return $this->createIpnPaymentAuthorizeDeclineHandler();
        }

        if ($ipnRequest->getAuthorizationDetails()->getAuthorizationStatus()->getIsOpen()) {
            return $this->createIpnPaymentAuthorizeOpenHandler();
        }

        if ($ipnRequest->getAuthorizationDetails()->getAuthorizationStatus()->getIsClosed()) {
            return $this->createIpnPaymentAuthorizeClosedHandler();
        }

        throw new IpnHandlerNotFoundException('No IPN handler for auth payment and status ' .
            $ipnRequest->getAuthorizationDetails()->getAuthorizationStatus()->getState());
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentAuthorizeSuspendedHandler()
    {
        return new IpnPaymentAuthorizeSuspendedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentAuthorizeDeclineHandler()
    {
            return new IpnPaymentAuthorizeDeclineHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentAuthorizeOpenHandler()
    {
        return new IpnPaymentAuthorizeOpenHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentAuthorizeClosedHandler()
    {
        return new IpnPaymentAuthorizeClosedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger
        );
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer | \Generated\Shared\Transfer\AmazonpayIpnPaymentCaptureRequestTransfer $ipnRequest
     *
     * @throws \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnHandlerNotFoundException
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentCaptureHandler(AbstractTransfer $ipnRequest)
    {
        if ($ipnRequest->getCaptureDetails()->getCaptureStatus()->getIsDeclined()) {
            return $this->createIpnPaymentCaptureDeclineHandler();
        }

        if ($ipnRequest->getCaptureDetails()->getCaptureStatus()->getIsCompleted()) {
            return $this->createIpnPaymentCaptureCompletedHandler();
        }

        if ($ipnRequest->getCaptureDetails()->getCaptureStatus()->getIsClosed()) {
            return $this->createIpnEmptyHandler();
        }

        throw new IpnHandlerNotFoundException('No IPN handler for capture and status ' .
            $ipnRequest->getCaptureDetails()->getCaptureStatus()->getState());
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentCaptureDeclineHandler()
    {
        return new IpnPaymentCaptureDeclineHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentCaptureCompletedHandler()
    {
        return new IpnPaymentCaptureCompletedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnEmptyHandler()
    {
        return new IpnEmptyHandler();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer | \Generated\Shared\Transfer\AmazonpayIpnPaymentRefundRequestTransfer $ipnRequest
     *
     * @throws \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnHandlerNotFoundException
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentRefundHandler(AbstractTransfer $ipnRequest)
    {
        if ($ipnRequest->getRefundDetails()->getRefundStatus()->getIsDeclined()) {
            return $this->createIpnPaymentRefundDeclineHandler();
        }

        if ($ipnRequest->getRefundDetails()->getRefundStatus()->getIsCompleted()) {
            return $this->createIpnPaymentRefundCompletedHandler();
        }

        throw new IpnHandlerNotFoundException('No IPN handler for payment refund and status ' .
            $ipnRequest->getRefundDetails()->getRefundStatus()->getState());
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentRefundDeclineHandler()
    {
        return new IpnPaymentRefundDeclineHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnPaymentRefundCompletedHandler()
    {
        return new IpnPaymentRefundCompletedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger,
            $this->refundOrderModel
        );
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer | \Generated\Shared\Transfer\AmazonpayIpnOrderReferenceNotificationTransfer $ipnRequest
     *
     * @throws \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnHandlerNotFoundException
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnOrderReferenceHandler(AbstractTransfer $ipnRequest)
    {
        if ($ipnRequest->getOrderReferenceStatus()->getIsOpen()) {
            return $this->createIpnOrderReferenceOpenHandler();
        }

        if ($ipnRequest->getOrderReferenceStatus()->getIsClosed()) {
            if ($ipnRequest->getOrderReferenceStatus()->getIsClosedByAmazon()) {
                return $this->createIpnOrderReferenceClosedHandler();
            }

            return $this->createIpnEmptyHandler();
        }

        if ($ipnRequest->getOrderReferenceStatus()->getIsSuspended()) {
            return $this->createIpnOrderReferenceSuspendedHandler();
        }

        if ($ipnRequest->getOrderReferenceStatus()->getIsCancelled()) {
            return $this->createIpnOrderReferenceCancelledHandler();
        }

        throw new IpnHandlerNotFoundException('No IPN handler for order reference and status ' .
            $ipnRequest->getOrderReferenceStatus()->getState());
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnOrderReferenceOpenHandler()
    {
        return new IpnOrderReferenceOpenHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnOrderReferenceClosedHandler()
    {
        return new IpnOrderReferenceClosedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnOrderReferenceSuspendedHandler()
    {
        return new IpnOrderReferenceSuspendedHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestHandlerInterface
     */
    protected function createIpnOrderReferenceCancelledHandler()
    {
        return new IpnOrderReferenceCancelledHandler(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->ipnRequestLogger
        );
    }
}
