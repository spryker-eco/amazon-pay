<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayAuthorizationDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayCaptureDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use Generated\Shared\Transfer\AmazonpayIpnRequestMessageTransfer;
use Generated\Shared\Transfer\AmazonpayRefundDetailsTransfer;
use Generated\Shared\Transfer\AmazonpayStatusTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery;
use Propel\Runtime\Propel;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;

class AmazonpayFacadeHandleAmazonpayIpnRequestTest extends AmazonpayFacadeAbstractTest
{
    const REFERENCE_1 = 'asdasd-asdasd-asdasd';
    const STATUS_NEW = 'STATUS_NEW';

    /**
     * @dataProvider updateRefundStatusAuthDataProvider
     *
     * @param string $referenceId
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $transfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testFacadeHandleAmazonpayIpnRequestAuth($referenceId, AmazonpayIpnPaymentRequestTransfer $transfer, $expectedStatus)
    {
        $this->createPaymentEntityWithAuthIdAndStatus($referenceId, static::STATUS_NEW);
        $this->createFacade()->handleAmazonPayIpnRequest($transfer);
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
                $this->createAmazonpayIpnAuthorizeRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_DECLINED)
                ),
                AmazonPayConfig::STATUS_DECLINED,
            ],
            'Auth Closed' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnAuthorizeRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_CLOSED)
                ),
                AmazonPayConfig::STATUS_CLOSED,
            ],
            'Auth Suspended' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnAuthorizeRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_SUSPENDED)
                ),
                AmazonPayConfig::STATUS_SUSPENDED,
            ],
            'Auth Open' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnAuthorizeRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_OPEN)
                ),
                AmazonPayConfig::STATUS_OPEN,
            ],
            'Capture Completed' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnCaptureRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_COMPLETED)
                ),
                AmazonPayConfig::STATUS_COMPLETED,
            ],
        ];
    }

    /**
     * @dataProvider updateStatusCaptureDataProvider
     *
     * @param string $referenceId
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $transfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testFacadeHandleAmazonpayIpnRequestCapture($referenceId, AmazonpayIpnPaymentRequestTransfer $transfer, $expectedStatus)
    {
        $this->createPaymentEntityWithCaptureIdAndStatus($referenceId, static::STATUS_NEW);
        $this->createFacade()->handleAmazonPayIpnRequest($transfer);
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
                $this->createAmazonpayIpnCaptureRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_DECLINED)
                ),
                AmazonPayConfig::STATUS_DECLINED,
            ],
            'Capture Completed' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnCaptureRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_COMPLETED)
                ),
                AmazonPayConfig::STATUS_COMPLETED,
            ],
        ];
    }

    /**
     * @dataProvider updateStatusRefundDataProvider
     *
     * @param string $referenceId
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $transfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testFacadeHandleAmazonpayIpnRequestRefund($referenceId, AmazonpayIpnPaymentRequestTransfer $transfer, $expectedStatus)
    {
        $this->createPaymentEntityWithRefundIdAndStatus($referenceId, static::STATUS_NEW);
        $this->createFacade()->handleAmazonPayIpnRequest($transfer);
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
                $this->createAmazonpayIpnRefundRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_COMPLETED)
                ),
                AmazonPayConfig::STATUS_COMPLETED,
            ],
            'Refund Declined' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnRefundRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_DECLINED)
                ),
                AmazonPayConfig::STATUS_DECLINED,
            ],
        ];
    }

    /**
     * @dataProvider updateStatusOrderReferenceDataProvider
     *
     * @param string $referenceId
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $transfer
     * @param string $expectedStatus
     *
     * @return void
     */
    public function testFacadeHandleAmazonpayIpnRequestOrderReference($referenceId, AmazonpayIpnPaymentRequestTransfer $transfer, $expectedStatus)
    {
        $this->createPaymentEntityWithRefundIdAndStatus($referenceId, static::STATUS_NEW);
        $this->createFacade()->handleAmazonPayIpnRequest($transfer);
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
                        ->setState(AmazonPayConfig::STATUS_CANCELLED)
                ),
                AmazonPayConfig::STATUS_CANCELLED,
            ],
            'Order reference Closed' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentOrderReferenceRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_AMAZON_CLOSED)
                ),
                AmazonPayConfig::STATUS_DECLINED,
            ],
            'Order reference Open' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentOrderReferenceRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_OPEN)
                ),
                AmazonPayConfig::STATUS_PAYMENT_METHOD_CHANGED,
            ],
            'Order reference Suspended' => [
                self::REFERENCE_1,
                $this->createAmazonpayIpnPaymentOrderReferenceRequestTransfer(
                    self::REFERENCE_1,
                    (new AmazonpayStatusTransfer())
                        ->setState(AmazonPayConfig::STATUS_SUSPENDED)
                ),
                AmazonPayConfig::STATUS_SUSPENDED,
            ],
        ];
    }

    /**
     * @param string $reference
     * @param \Generated\Shared\Transfer\AmazonpayStatusTransfer $status
     *
     * @return \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer
     */
    protected function createAmazonpayIpnAuthorizeRequestTransfer($reference, AmazonpayStatusTransfer $status)
    {
        return (new AmazonpayIpnPaymentRequestTransfer())
            ->setMessage(
                (new AmazonpayIpnRequestMessageTransfer())
                ->setNotificationType(AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE)
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
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
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
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function getPaymentEntityByAuthId($authReferenceId)
    {
        return $this->findPaymentEntityByAuthId($authReferenceId)->findOne();
    }

    /**
     * @param string $authReferenceId
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
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
    protected function createAmazonpayIpnCaptureRequestTransfer($reference, AmazonpayStatusTransfer $status)
    {
        return (new AmazonpayIpnPaymentRequestTransfer())
            ->setMessage(
                (new AmazonpayIpnRequestMessageTransfer())
                    ->setNotificationType(AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_CAPTURE)
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
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
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
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function getPaymentEntityByCaptureId($referenceId)
    {
        return $this->findPaymentEntityByCaptureId($referenceId)->findOne();
    }

    /**
     * @param string $referenceId
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
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
    protected function createAmazonpayIpnRefundRequestTransfer($reference, AmazonpayStatusTransfer $status)
    {
        return (new AmazonpayIpnPaymentRequestTransfer())
            ->setMessage(
                (new AmazonpayIpnRequestMessageTransfer())
                    ->setNotificationType(AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_REFUND)
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
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
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
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function getPaymentEntityByRefundId($referenceId)
    {
        return $this->findPaymentEntityByRefundId($referenceId)->findOne();
    }

    /**
     * @param string $referenceId
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
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
        return (new AmazonpayIpnPaymentRequestTransfer())
            ->setMessage(
                (new AmazonpayIpnRequestMessageTransfer())
                    ->setNotificationType(AmazonPayConfig::IPN_REQUEST_TYPE_ORDER_REFERENCE_NOTIFICATION)
            )
            ->setAmazonOrderReferenceId($reference)
            ->setOrderReferenceStatus($status);
    }

    /**
     * @param string $referenceId
     * @param string $statusName
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
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
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay|null
     */
    protected function getPaymentEntityByOrderReferenceId($referenceId)
    {
        return $this->findPaymentEntityByOrderReferenceId($referenceId)->findOne();
    }

    /**
     * @param string $referenceId
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
     */
    protected function findPaymentEntityByOrderReferenceId($referenceId)
    {
        return SpyPaymentAmazonpayQuery::create()
            ->filterByOrderReferenceId($referenceId);
    }
}
