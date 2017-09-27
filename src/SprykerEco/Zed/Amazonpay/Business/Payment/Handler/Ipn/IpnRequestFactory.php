<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
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
            case AmazonpayConstants::IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE:
                return $this->createIpnPaymentAuthorizeHandler($ipnRequest);

            case AmazonpayConstants::IPN_REQUEST_TYPE_PAYMENT_CAPTURE:
                return $this->createIpnPaymentCaptureHandler($ipnRequest);

            case AmazonpayConstants::IPN_REQUEST_TYPE_PAYMENT_REFUND:
                return $this->createIpnPaymentRefundHandler($ipnRequest);

            case AmazonpayConstants::IPN_REQUEST_TYPE_ORDER_REFERENCE_NOTIFICATION:
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
            return new IpnPaymentAuthorizeSuspendedHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
        }

        if ($ipnRequest->getAuthorizationDetails()->getAuthorizationStatus()->getIsDeclined()) {
            return new IpnPaymentAuthorizeDeclineHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
        }

        if ($ipnRequest->getAuthorizationDetails()->getAuthorizationStatus()->getIsOpen()) {
            return new IpnPaymentAuthorizeOpenHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
        }

        if ($ipnRequest->getAuthorizationDetails()->getAuthorizationStatus()->getIsClosed()) {
            return new IpnPaymentAuthorizeClosedHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
        }

        throw new IpnHandlerNotFoundException('No IPN handler for auth payment and status ' .
            $ipnRequest->getAuthorizationDetails()->getAuthorizationStatus()->getState());
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
            return new IpnPaymentCaptureDeclineHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
        }

        if ($ipnRequest->getCaptureDetails()->getCaptureStatus()->getIsCompleted()) {
            return new IpnPaymentCaptureCompletedHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
        }

        if ($ipnRequest->getCaptureDetails()->getCaptureStatus()->getIsClosed()) {
            return new IpnEmptyHandler();
        }

        throw new IpnHandlerNotFoundException('No IPN handler for capture and status ' .
            $ipnRequest->getCaptureDetails()->getCaptureStatus()->getState());
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
            return new IpnPaymentRefundDeclineHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
        }

        if ($ipnRequest->getRefundDetails()->getRefundStatus()->getIsCompleted()) {
            return new IpnPaymentRefundCompletedHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger,
                $this->refundOrderModel
            );
        }

        throw new IpnHandlerNotFoundException('No IPN handler for payment refund and status ' .
            $ipnRequest->getRefundDetails()->getRefundStatus()->getState());
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
            return new IpnOrderReferenceOpenHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
        }

        if ($ipnRequest->getOrderReferenceStatus()->getIsClosed()) {
            if ($ipnRequest->getOrderReferenceStatus()->getIsClosedByAmazon()) {
                return new IpnOrderReferenceClosedHandler(
                    $this->omsFacade,
                    $this->amazonpayQueryContainer,
                    $this->ipnRequestLogger
                );
            }

            return new IpnEmptyHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
        }

        if ($ipnRequest->getOrderReferenceStatus()->getIsSuspended()) {
            return new IpnOrderReferenceSuspendedHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
        }

        if ($ipnRequest->getOrderReferenceStatus()->getIsCancelled()) {
            return new IpnOrderReferenceCancelledHandler(
                $this->omsFacade,
                $this->amazonpayQueryContainer,
                $this->ipnRequestLogger
            );
        }

        throw new IpnHandlerNotFoundException('No IPN handler for order reference and status ' .
            $ipnRequest->getOrderReferenceStatus()->getState());
    }

}
