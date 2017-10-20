<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business;

use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayIpnOrderReferenceNotificationTransfer;
use Generated\Shared\Transfer\AmazonpayIpnPaymentAuthorizeRequestTransfer;
use Generated\Shared\Transfer\AmazonpayIpnPaymentCaptureRequestTransfer;
use Generated\Shared\Transfer\AmazonpayIpnPaymentRefundRequestTransfer;
use Generated\Shared\Transfer\AmazonpayIpnRequestMessageTransfer;
use Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AmazonpayFacadeHandleAmazonpayIpnRequestTest extends AmazonpayFacadeAbstractTest
{

    const REFERENCE_1 = 'asdasd-asdasd-asdasd';

    /**
     * @dataProvider updateRefundStatusAuthDataProvider
     *
     * @param string $referenceId
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testFacadeHandleAmazonpayIpnRequestAuth($referenceId, AbstractTransfer $transfer, $expectedStatus)
    {
        $this->createPaymentEntityWithAuthIdAndStatus($referenceId, AmazonpayConstants::OMS_STATUS_NEW);
        $this->createFacade()->handleAmazonpayIpnRequest($transfer);
        $this->assertEquals($expectedStatus, $this->getPaymentEntityByAuthId($referenceId)->getStatus());
    }

    /**
     * @return array
     */
    public function updateRefundStatusAuthDataProvider()
    {
        return [
            'Auth Declined' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentAuthorizeRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsDeclined(1)
                ),
                AmazonpayConstants::OMS_STATUS_AUTH_DECLINED,
            ],
            'Auth Closed' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentAuthorizeRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsClosed(1)
                ),
                AmazonpayConstants::OMS_STATUS_AUTH_CLOSED,
            ],
            'Auth Suspended' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentAuthorizeRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsSuspended(1)
                ),
                AmazonpayConstants::OMS_STATUS_AUTH_SUSPENDED,
            ],
            'Auth Open' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentAuthorizeRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsOpen(1)
                ),
                AmazonpayConstants::OMS_STATUS_AUTH_OPEN,
            ],
            'Capture Completed' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentCaptureRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsCompleted(1)
                ),
                AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED,
            ],
        ];
    }

    /**
     * @dataProvider updateStatusCaptureDataProvider
     *
     * @param string $referenceId
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testFacadeHandleAmazonpayIpnRequestCapture($referenceId, AbstractTransfer $transfer, $expectedStatus)
    {
        $this->createPaymentEntityWithCaptureIdAndStatus($referenceId, AmazonpayConstants::OMS_STATUS_NEW);
        $this->createFacade()->handleAmazonpayIpnRequest($transfer);
        $this->assertEquals($expectedStatus, $this->getPaymentEntityByCaptureId($referenceId)->getStatus());
    }

    /**
     * @return array
     */
    public function updateStatusCaptureDataProvider()
    {
        return [
            'Capture Declined' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentCaptureRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsDeclined(1)
                ),
                AmazonpayConstants::OMS_STATUS_CAPTURE_DECLINED,
            ],
            'Capture Completed' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentCaptureRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsCompleted(1)
                ),
                AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED,
            ],
        ];
    }

    /**
     * @dataProvider updateStatusRefundDataProvider
     *
     * @param string $referenceId
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testFacadeHandleAmazonpayIpnRequestRefund($referenceId, AbstractTransfer $transfer, $expectedStatus)
    {
        $this->createPaymentEntityWithRefundIdAndStatus($referenceId, AmazonpayConstants::OMS_STATUS_NEW);
        $this->createFacade()->handleAmazonpayIpnRequest($transfer);
        $this->assertEquals($expectedStatus, $this->getPaymentEntityByRefundId($referenceId)->getStatus());
    }

    /**
     * @return array
     */
    public function updateStatusRefundDataProvider()
    {
        return [
            'Refund Completed' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentRefundRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsCompleted(1)
                ),
                AmazonpayConstants::OMS_STATUS_REFUND_COMPLETED,
            ],
            'Refund Declined' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentRefundRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsDeclined(1)
                ),
                AmazonpayConstants::OMS_STATUS_REFUND_DECLINED,
            ],
        ];
    }

    /**
     * @dataProvider updateStatusOrderReferenceDataProvider
     *
     * @param string $referenceId
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testFacadeHandleAmazonpayIpnRequestOrderReference($referenceId, AbstractTransfer $transfer, $expectedStatus)
    {
        $this->createPaymentEntityWithRefundIdAndStatus($referenceId, AmazonpayConstants::OMS_STATUS_NEW);
        $this->createFacade()->handleAmazonpayIpnRequest($transfer);
        $this->assertEquals($expectedStatus, $this->getPaymentEntityByRefundId($referenceId)->getStatus());
    }

    /**
     * @return array
     */
    public function updateStatusOrderReferenceDataProvider()
    {
        return [
            'Order reference Cancelled' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentOrderReferenceRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsCancelled(1)
                ),
                AmazonpayConstants::OMS_STATUS_CANCELLED,
            ],
            'Order reference Closed' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentOrderReferenceRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsClosed(1)
                        ->setIsClosedByAmazon(1)
                ),
                AmazonpayConstants::OMS_STATUS_AUTH_DECLINED,
            ],
            'Order reference Open' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentOrderReferenceRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsOpen(1)
                ),
                AmazonpayConstants::OMS_STATUS_PAYMENT_METHOD_CHANGED,
            ],
            'Order reference Suspended' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentOrderReferenceRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setIsSuspended(1)
                ),
                AmazonpayConstants::OMS_STATUS_AUTH_SUSPENDED,
            ],
        ];
    }

    /**
     * @param string $reference
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $status
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createAmazonpayIpnPaymentAuthorizeRequestTransfer($reference, AmazonpayStatusTransfer $status)
    {
        return (new AmazonpayIpnPaymentAuthorizeRequestTransfer())
            ->setMessage(
                (new AmazonpayIpnRequestMessageTransfer())
                ->setNotificationType(AmazonpayConstants::IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE)
            )
            ->setAuthorizationDetails(
                (new AmazonpayAuthorizationDetailsTransfer())
                ->setAuthorizationStatus($status)
                ->setAuthorizationReferenceId($reference)
            );
    }

    /**
     * @param string $authReferenceId
     * @param string $statusName
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function createPaymentEntityWithAuthIdAndStatus($authReferenceId, $statusName)
    {
        $paymentEntity = $this->findPaymentEntityByAuthId($authReferenceId)->findOneOrCreate();

        $paymentEntity->setStatus($statusName);
        $paymentEntity->setSellerOrderId($authReferenceId);
        $paymentEntity->setOrderReferenceId($authReferenceId);
        $paymentEntity->setIsSandbox(1);
        $paymentEntity->save();

        return $paymentEntity;
    }

    /**
     * @param string $authReferenceId
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function getPaymentEntityByAuthId($authReferenceId)
    {
        return $this->findPaymentEntityByAuthId($authReferenceId)->findOne();
    }

    /**
     * @param string $authReferenceId
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery
     */
    protected function findPaymentEntityByAuthId($authReferenceId)
    {
        return SpyPaymentAmazonpayQuery::create()
            ->filterByAuthorizationReferenceId($authReferenceId);
    }

    /**
     * @param string $reference
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $status
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createAmazonpayIpnPaymentCaptureRequestTransfer($reference, AmazonpayStatusTransfer $status)
    {
        return (new AmazonpayIpnPaymentCaptureRequestTransfer())
            ->setMessage(
                (new AmazonpayIpnRequestMessageTransfer())
                    ->setNotificationType(AmazonpayConstants::IPN_REQUEST_TYPE_PAYMENT_CAPTURE)
            )
            ->setCaptureDetails(
                (new AmazonpayCaptureDetailsTransfer())
                    ->setCaptureStatus($status)
                    ->setCaptureReferenceId($reference)
            );
    }

    /**
     * @param string $referenceId
     * @param string $statusName
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function createPaymentEntityWithCaptureIdAndStatus($referenceId, $statusName)
    {
        $paymentEntity = $this->findPaymentEntityByCaptureId($referenceId)->findOneOrCreate();

        $paymentEntity->setStatus($statusName);
        $paymentEntity->setSellerOrderId($referenceId);
        $paymentEntity->setOrderReferenceId($referenceId);
        $paymentEntity->setIsSandbox(1);
        $paymentEntity->save();

        return $paymentEntity;
    }

    /**
     * @param string $referenceId
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function getPaymentEntityByCaptureId($referenceId)
    {
        return $this->findPaymentEntityByCaptureId($referenceId)->findOne();
    }

    /**
     * @param string $referenceId
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery
     */
    protected function findPaymentEntityByCaptureId($referenceId)
    {
        return SpyPaymentAmazonpayQuery::create()
            ->filterByCaptureReferenceId($referenceId);
    }

    /**
     * @param string $reference
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $status
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createAmazonpayIpnPaymentRefundRequestTransfer($reference, AmazonpayStatusTransfer $status)
    {
        return (new AmazonpayIpnPaymentRefundRequestTransfer())
            ->setMessage(
                (new AmazonpayIpnRequestMessageTransfer())
                    ->setNotificationType(AmazonpayConstants::IPN_REQUEST_TYPE_PAYMENT_REFUND)
            )
            ->setRefundDetails(
                (new AmazonpayRefundDetailsTransfer())
                    ->setRefundStatus($status)
                    ->setRefundReferenceId($reference)
            );
    }

    /**
     * @param string $referenceId
     * @param string $statusName
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function createPaymentEntityWithRefundIdAndStatus($referenceId, $statusName)
    {
        $paymentEntity = $this->findPaymentEntityByRefundId($referenceId)->findOneOrCreate();

        $paymentEntity->setStatus($statusName);
        $paymentEntity->setSellerOrderId($referenceId);
        $paymentEntity->setOrderReferenceId($referenceId);
        $paymentEntity->setIsSandbox(1);
        $paymentEntity->save();

        return $paymentEntity;
    }

    /**
     * @param string $referenceId
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function getPaymentEntityByRefundId($referenceId)
    {
        return $this->findPaymentEntityByRefundId($referenceId)->findOne();
    }

    /**
     * @param string $referenceId
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery
     */
    protected function findPaymentEntityByRefundId($referenceId)
    {
        return SpyPaymentAmazonpayQuery::create()
            ->filterByRefundReferenceId($referenceId);
    }

    /**
     * @param string $reference
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $status
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createAmazonpayIpnPaymentOrderReferenceRequestTransfer($reference, AmazonpayStatusTransfer $status)
    {
        return (new AmazonpayIpnOrderReferenceNotificationTransfer())
            ->setMessage(
                (new AmazonpayIpnRequestMessageTransfer())
                    ->setNotificationType(AmazonpayConstants::IPN_REQUEST_TYPE_ORDER_REFERENCE_NOTIFICATION)
            )
            ->setAmazonOrderReferenceId($reference)
            ->setOrderReferenceStatus($status);
    }

    /**
     * @param string $referenceId
     * @param string $statusName
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function createPaymentEntityWithOrderReferenceIdAndStatus($referenceId, $statusName)
    {
        $paymentEntity = $this->findPaymentEntityByOrderReferenceId($referenceId)->findOneOrCreate();

        $paymentEntity->setStatus($statusName);
        $paymentEntity->setSellerOrderId($referenceId);
        $paymentEntity->setIsSandbox(1);
        $paymentEntity->save();

        return $paymentEntity;
    }

    /**
     * @param string $referenceId
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function getPaymentEntityByOrderReferenceId($referenceId)
    {
        return $this->findPaymentEntityByOrderReferenceId($referenceId)->findOne();
    }

    /**
     * @param string $referenceId
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery
     */
    protected function findPaymentEntityByOrderReferenceId($referenceId)
    {
        return SpyPaymentAmazonpayQuery::create()
            ->filterByOrderReferenceId($referenceId);
    }

}
