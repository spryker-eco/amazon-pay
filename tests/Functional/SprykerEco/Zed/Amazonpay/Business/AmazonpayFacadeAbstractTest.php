<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Codeception\TestCase\Test;
use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\AmazonpayFacadeMock;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainer;

class AmazonpayFacadeAbstractTest extends Test
{

    /**
     * @var bool
     */
    protected $setUpCommpleted = false;

    /**
     * @return array
     */
    protected function getOrderStatusMap()
    {
        return [
            AbstractResponse::ORDER_REFERENCE_ID_FIRST => 'auth open',
            AbstractResponse::ORDER_REFERENCE_ID_SECOND => 'auth open',
            AbstractResponse::ORDER_REFERENCE_ID_THIRD => 'auth open',
        ];
    }

    protected function _before()
    {
        if ($this->setUpCommpleted) {
            return ;
        }

        $this->cleanup();

        $address = new SpySalesOrderAddress();
        $address->setFkCountry(1);
        $address->setCity('Berlin');
        $address->setFirstName('John');
        $address->setLastName('Doe');
        $address->setZipCode('10009');
        $address->save();

        for ($i = 1; $i <= 3; ++$i) {
            $order = new SpySalesOrder();
            $order->setOrderReference(sprintf('S02-5989383-0864061-000000%s', $i));
            $order->setShippingAddress($address);
            $order->setBillingAddress($address);
            $order->save();

            $payment = new SpyPaymentAmazonpay();
            $payment->setSpySalesOrder($order);
            $payment->setOrderReferenceId(sprintf('S02-5989383-0864061-000000%s', $i));
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

        $this->setUpCommpleted = true;
    }

    protected function _after()
    {
    }

    protected function cleanup()
    {
        SpyPaymentAmazonpayQuery::create()->deleteAll();
        SpySalesOrderQuery::create()->deleteAll();
        SpySalesOrderAddressQuery::create()->deleteAll();
    }

    /**
     * @param $orderReferenceId
     * @return array|\Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer($orderReferenceId)
    {
        self::_before();

        $qc = new AmazonpayQueryContainer();
        $orderEntity = $qc->queryPaymentByOrderReferenceId($orderReferenceId)->findOne();

        // that's awkward but no can do
        $command = new GetOrderTransferCommandPlugin();
        return $command->run([], $orderEntity->getSpySalesOrder(), new ReadOnlyArrayObject());
    }

    /**
     * @return \Functional\SprykerEco\Zed\Amazonpay\Business\Mock\AmazonpayFacadeMock
     */
    protected function createFacade()
    {
        return new AmazonpayFacadeMock();
    }

}