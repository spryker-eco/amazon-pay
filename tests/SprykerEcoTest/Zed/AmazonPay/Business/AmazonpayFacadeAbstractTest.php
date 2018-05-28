<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Orm\Zed\AmazonPay\Persistence\Base\SpyPaymentAmazonpaySalesOrderItemQuery;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\Currency\Business\CurrencyFacade;
use Spryker\Zed\Store\Business\StoreFacade;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Shared\AmazonPay\AmazonPayConstants;
use SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayEntityToTransferConverter;
use SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainer;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk\AbstractResponse;
use SprykerEcoTest\Zed\AmazonPay\Business\Mock\AmazonPayFacadeMock;
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

        $config[AmazonPayConstants::CLIENT_ID] = '';
        $config[AmazonPayConstants::CLIENT_SECRET] = '';
        $config[AmazonPayConstants::SELLER_ID] = '';
        $config[AmazonPayConstants::ACCESS_KEY_ID] = '';
        $config[AmazonPayConstants::SECRET_ACCESS_KEY] = '';
        $config[AmazonPayConstants::REGION] = 'DE';
        $config[AmazonPayConstants::STORE_NAME] = '';
        $config[AmazonPayConstants::SANDBOX] = true;
        $config[AmazonPayConstants::AUTH_TRANSACTION_TIMEOUT] = 1000;
        $config[AmazonPayConstants::CAPTURE_NOW] = true;
        $config[AmazonPayConstants::ERROR_REPORT_LEVEL] = 'ERRORS_ONLY';
        $config[AmazonPayConstants::PAYMENT_REJECT_ROUTE] = 'cart';
        $config[AmazonPayConstants::WIDGET_SCRIPT_PATH] = '';
        $config[AmazonPayConstants::WIDGET_SCRIPT_PATH_SANDBOX] = '';
        $config[AmazonPayConstants::WIDGET_POPUP_LOGIN] = true;
        $config[AmazonPayConstants::WIDGET_BUTTON_TYPE] = AmazonPayConfig::WIDGET_BUTTON_TYPE_FULL;
        $config[AmazonPayConstants::WIDGET_BUTTON_SIZE] = AmazonPayConfig::WIDGET_BUTTON_SIZE_MEDIUM;
        $config[AmazonPayConstants::WIDGET_BUTTON_COLOR] = AmazonPayConfig::WIDGET_BUTTON_COLOR_DARK_GRAY;
        $config[AmazonPayConstants::ENABLE_ISOLATE_LEVEL_READ] = false;

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
            AbstractResponse::ORDER_REFERENCE_ID_1 => AmazonPayConfig::STATUS_OPEN,
            AbstractResponse::ORDER_REFERENCE_ID_2 => AmazonPayConfig::STATUS_OPEN,
            AbstractResponse::ORDER_REFERENCE_ID_3 => AmazonPayConfig::STATUS_OPEN,
            AbstractResponse::ORDER_REFERENCE_ID_4 => AmazonPayConfig::STATUS_CLOSED,
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

        $carrier = new SpyShipmentCarrier();
        $carrier->setName('test-carrier');
        $carrier->save();

        if (count($ids) < $count) {
            $storeCurrency = $this->getCurrentCurrencyStore();

            for ($i = 0; $i < $count; $i++) {
                $shipmentMethod = new SpyShipmentMethod();
                $shipmentMethod
                    ->setName($i)
                    ->setFkShipmentCarrier($carrier->getIdShipmentCarrier())
                    ->save();
                $ids[] = $shipmentMethod->getIdShipmentMethod();
                $storePrice = new SpyShipmentMethodPrice();
                $storePrice->setFkStore($storeCurrency->getStore()->getIdStore())
                    ->setFkShipmentMethod($shipmentMethod->getIdShipmentMethod())
                    ->setFkCurrency($storeCurrency->getCurrencies()[0]->getIdCurrency())
                    ->setDefaultGrossPrice(10)
                    ->setDefaultNetPrice(10);
                $storePrice->save();
            }
        }

        return $ids;
    }

    protected function getCurrentCurrencyStore()
    {
        $facade = new CurrencyFacade();

        return $facade->getCurrentStoreWithCurrencies();
    }

    /**
     * @param string $orderReference
     * @param int $index
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
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
        $payment->setIsSandbox(1);
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
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay
     */
    protected function getAmazonPayPayment($orderReferenceId)
    {
        return $this->createAmazonPayQueryContainer()->queryPaymentByOrderReferenceId($orderReferenceId)->findOne();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface
     */
    protected function createAmazonPayQueryContainer()
    {
        return new AmazonPayQueryContainer();
    }

    /**
     * @param string $orderReferenceId
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    protected function getAmazonPayCallTransferByOrderReferenceId($orderReferenceId)
    {
        $paymentAmazonpayEntity = $this->getAmazonPayPayment($orderReferenceId);

        return $this->buildOrderTransfer($paymentAmazonpayEntity);
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentEntity
     * @param array $salesOrderItems
     * @param int $requestedAmount
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    protected function buildOrderTransfer(SpyPaymentAmazonpay $paymentEntity, array $salesOrderItems = [], $requestedAmount = 5000)
    {
        $converter = new AmazonPayEntityToTransferConverter();

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
     * @return \SprykerEcoTest\Zed\AmazonPay\Business\Mock\AmazonPayFacadeMock
     */
    protected function createFacade($additionalConfig = null)
    {
        return new AmazonPayFacadeMock($additionalConfig);
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

        $payment = $this->getAmazonPayPayment($result->getAmazonpayPayment()->getOrderReferenceId());

        $this->assertEquals($expectedStatus, $payment->getStatus());
    }
}
