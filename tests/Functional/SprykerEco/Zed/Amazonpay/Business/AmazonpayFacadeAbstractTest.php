<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\AmazonpayFacadeMock;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\Amazonpay\Persistence\Base\SpyPaymentAmazonpaySalesOrderItemQuery;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayEntityToTransferConverter;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainer;

class AmazonpayFacadeAbstractTest extends Test
{

    /**
     * @return array
     */
    protected function getOrderStatusMap()
    {
        return [
            AbstractResponse::ORDER_REFERENCE_ID_1 => AmazonpayConstants::OMS_STATUS_AUTH_OPEN,
            AbstractResponse::ORDER_REFERENCE_ID_2 => AmazonpayConstants::OMS_STATUS_AUTH_OPEN,
            AbstractResponse::ORDER_REFERENCE_ID_3 => AmazonpayConstants::OMS_STATUS_AUTH_OPEN,
            AbstractResponse::ORDER_REFERENCE_ID_4 => AmazonpayConstants::OMS_STATUS_AUTH_CLOSED,
        ];
    }

    /**
     * @return void
     */
    protected function prepareFixtures()
    {
        $this->cleanup();

        $i = 0;

        foreach ($this->getOrderStatusMap() as $orderReference => $status) {
            $i++;

            $payment = new SpyPaymentAmazonpay();
            $payment->setOrderReferenceId($orderReference);
            $payment->setSellerOrderId(sprintf('S02-5989383-0864061-000000%sR00%s', $i, $i));

            $payment->setAmazonCaptureId(sprintf('S02-5989383-0864061-C00000%s', $i));
            $payment->setCaptureReferenceId(sprintf('S02-5989383-0864061-C00000%sR00%s', $i, $i));

            $payment->setAmazonAuthorizationId(sprintf('S02-5989383-0864061-A00000%s', $i));
            $payment->setAuthorizationReferenceId(sprintf('S02-5989383-0864061-A00000%sR00%s', $i, $i));

            $payment->setAmazonRefundId(sprintf('S02-5989383-0864061-R00000%s', $i));
            $payment->setRefundReferenceId(sprintf('S02-5989383-0864061-R00000%sR00%s', $i, $i));
            $payment->setStatus($this->getOrderStatusMap()[$payment->getOrderReferenceId()]);
            $payment->setIsSandbox(true);
            $payment->save();
        }
    }

    /**
     * @param string $orderReference
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder $orderReference
     */
    protected function createSalesOrder($orderReference)
    {
        $address = new SpySalesOrderAddress();
        $address->setFkCountry(1);
        $address->setCity('Berlin');
        $address->setFirstName('John');
        $address->setLastName('Doe');
        $address->setZipCode('10009');
        $address->save();

        $order = (new SpySalesOrderQuery())
            ->filterByOrderReference($orderReference)
            ->findOneOrCreate();

        $order->setShippingAddress($address)
            ->setBillingAddress($address)
            ->save();

        return $order;
    }

    /**
     * @return void
     */
    protected function cleanup()
    {
        SpyPaymentAmazonpaySalesOrderItemQuery::create()->deleteAll();
        SpyPaymentAmazonpayQuery::create()->deleteAll();
        SpyOmsOrderItemStateHistoryQuery::create()->deleteAll();
        SpySalesOrderItemQuery::create()->deleteAll();
        SpySalesOrderQuery::create()->deleteAll();
        SpySalesOrderAddressQuery::create()->deleteAll();
    }

    /**
     * @param string $orderReferenceId
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function getAmazonpayPayment($orderReferenceId)
    {
        return $this->createAmazonpayQueryContainer()->queryPaymentByOrderReferenceId($orderReferenceId)->findOne();
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface
     */
    protected function createAmazonpayQueryContainer()
    {
        return new AmazonpayQueryContainer();
    }

    /**
     * @param string $orderReferenceId
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    protected function getAmazonpayCallTransferByOrderReferenceId($orderReferenceId)
    {
        $paymentAmazonpayEntity = $this->getAmazonpayPayment($orderReferenceId);

        return $this->buildOrderTransfer($paymentAmazonpayEntity);
    }

    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $paymentEntity
     * @param array $salesOrderItems
     * @param int $requestedAmount
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    protected function buildOrderTransfer(SpyPaymentAmazonpay $paymentEntity, array $salesOrderItems = [], $requestedAmount = 5000)
    {
        $converter = new AmazonpayEntityToTransferConverter();

        $paymentTransfer = $converter->mapEntityToTransfer($paymentEntity);

        $amazonpayCallTransfer = new AmazonpayCallTransfer();
        $amazonpayCallTransfer->setRequestedAmount($requestedAmount)
            ->setAmazonpayPayment($paymentTransfer)
            ->setItems(new ArrayObject($salesOrderItems));

        return $amazonpayCallTransfer;
    }

    /**
     * @return \Functional\SprykerEco\Zed\Amazonpay\Business\Mock\AmazonpayFacadeMock
     */
    protected function createFacade()
    {
        return new AmazonpayFacadeMock();
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $result
     * @param string $expectedStatus
     *
     * @return void
     */
    protected function validateResult(AmazonpayCallTransfer $result, $expectedStatus)
    {
        $this->assertTrue($result->getAmazonpayPayment()->getResponseHeader()->getIsSuccess());

        $payment = $this->getAmazonpayPayment($result->getAmazonpayPayment()->getOrderReferenceId());

        $this->assertEquals($expectedStatus, $payment->getStatus());
    }

}
