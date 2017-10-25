<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\Amazonpay\Persistence\Base\SpyPaymentAmazonpaySalesOrderItemQuery;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use SprykerEco\Zed\Amazonpay\Business\Converter\AmazonpayEntityToTransferConverter;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainer;
use SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use SprykerEcoTest\Zed\Amazonpay\Business\Mock\AmazonpayFacadeMock;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class AmazonpayFacadeAbstractTest extends Test
{
    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        /** @var \SprykerTest\Shared\Testify\Helper\ConfigHelper $configHelper */
        $configHelper = $this->getModule('\\' . ConfigHelper::class);

        $config[AmazonpayConstants::CLIENT_ID] = '';
        $config[AmazonpayConstants::CLIENT_SECRET] = '';
        $config[AmazonpayConstants::SELLER_ID] = '';
        $config[AmazonpayConstants::ACCESS_KEY_ID] = '';
        $config[AmazonpayConstants::SECRET_ACCESS_KEY] = '';
        $config[AmazonpayConstants::REGION] = 'DE';
        $config[AmazonpayConstants::STORE_NAME] = '';
        $config[AmazonpayConstants::SANDBOX] = true;
        $config[AmazonpayConstants::AUTH_TRANSACTION_TIMEOUT] = 1000;
        $config[AmazonpayConstants::CAPTURE_NOW] = true;
        $config[AmazonpayConstants::ERROR_REPORT_LEVEL] = 'ERRORS_ONLY';
        $config[AmazonpayConstants::PAYMENT_REJECT_ROUTE] = 'cart';
        $config[AmazonpayConstants::WIDGET_SCRIPT_PATH] = '';
        $config[AmazonpayConstants::WIDGET_SCRIPT_PATH_SANDBOX] = '';
        $config[AmazonpayConstants::WIDGET_POPUP_LOGIN] = true;
        $config[AmazonpayConstants::WIDGET_BUTTON_TYPE] = AmazonpayConfig::WIDGET_BUTTON_TYPE_FULL;
        $config[AmazonpayConstants::WIDGET_BUTTON_SIZE] = AmazonpayConfig::WIDGET_BUTTON_SIZE_MEDIUM;
        $config[AmazonpayConstants::WIDGET_BUTTON_COLOR] = AmazonpayConfig::WIDGET_BUTTON_COLOR_DARK_GRAY;

        foreach ($config as $key => $value) {
            $configHelper->setConfig($key, $value);
        }
    }

    /**
     * @return array
     */
    protected function getOrderStatusMap()
    {
        return [
            AbstractResponse::ORDER_REFERENCE_ID_1 => AmazonpayConfig::OMS_STATUS_AUTH_OPEN,
            AbstractResponse::ORDER_REFERENCE_ID_2 => AmazonpayConfig::OMS_STATUS_AUTH_OPEN,
            AbstractResponse::ORDER_REFERENCE_ID_3 => AmazonpayConfig::OMS_STATUS_AUTH_OPEN,
            AbstractResponse::ORDER_REFERENCE_ID_4 => AmazonpayConfig::OMS_STATUS_AUTH_CLOSED,
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
            $this->createPaymentAmazonpay($orderReference, $i);
        }
    }

    /**
     * @param int $count
     *
     * @return array
     */
    protected function createShipmentMethods($count)
    {
        $shipmentMethods = SpyShipmentMethodQuery::create()
            ->limit($count)
            ->find();

        $ids = [];
        foreach ($shipmentMethods as $shipmentMethod) {
            $ids[] = $shipmentMethod->getIdShipmentMethod();
        }

        if (count($ids) < $count) {
            for ($i = count($ids); $i < $count; $i++) {
                $shipmentMethod = new SpyShipmentMethod();
                $shipmentMethod->save();
                $ids[] = $shipmentMethod->getIdShipmentMethod();
            }
        }

        return $ids;
    }

    /**
     * @param string $orderReference
     * @param int $index
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    protected function createPaymentAmazonpay($orderReference, $index)
    {
        $payment = new SpyPaymentAmazonpay();
        $payment->setOrderReferenceId($orderReference);
        $payment->setSellerOrderId(sprintf('S02-5989383-0864061-000000%sR00%s', $index, $index));

        $payment->setAmazonCaptureId(sprintf('S02-5989383-0864061-C00000%s', $index));
        $payment->setCaptureReferenceId(sprintf('S02-5989383-0864061-C00000%sR00%s', $index, $index));

        $payment->setAmazonAuthorizationId(sprintf('S02-5989383-0864061-A00000%s', $index));
        $payment->setAuthorizationReferenceId(sprintf('S02-5989383-0864061-A00000%sR00%s', $index, $index));

        $payment->setAmazonRefundId(sprintf('S02-5989383-0864061-R00000%s', $index));
        $payment->setRefundReferenceId(sprintf('S02-5989383-0864061-R00000%sR00%s', $index, $index));
        $payment->setStatus($this->getOrderStatusMap()[$payment->getOrderReferenceId()]);
        $payment->setIsSandbox(true);
        $payment->save();

        return $payment;
    }

    /**
     * @return int
     */
    protected function getCountryId()
    {
        $countries = SpyCountryQuery::create()->limit(1)->find();
        if (count($countries)) {
            return $countries[0]->getIdCountry();
        }

        $country = new SpyCountry();
        $country->setIso2Code('XX');
        $country->save();

        return $country->getIdCountry();
    }

    /**
     * @param string $orderReference
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder $orderReference
     */
    protected function createSalesOrder($orderReference)
    {
        $countryId = $this->getCountryId();

        $address = new SpySalesOrderAddress();
        $address->setFkCountry($countryId);
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
     * @param array|null $additionalConfig
     *
     * @return \SprykerEcoTest\Zed\Amazonpay\Business\Mock\AmazonpayFacadeMock
     */
    protected function createFacade($additionalConfig = null)
    {
        return new AmazonpayFacadeMock($additionalConfig);
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
