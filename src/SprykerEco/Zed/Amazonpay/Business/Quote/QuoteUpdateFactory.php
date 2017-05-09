<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use Spryker\Shared\Amazonpay\AmazonpayConfigInterface;
use Spryker\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface;
use Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentInterface;

class QuoteUpdateFactory implements QuoteUpdateFactoryInterface
{

    /**
     * @var \Spryker\Zed\Amazonpay\Business\Api\Adapter\AdapterFactory
     */
    protected $adapterFactory;

    /**
     * @var \Spryker\Shared\Amazonpay\AmazonpayConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface $adapterFactory
     * @param \Spryker\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentInterface $shipmentFacade
     */
    public function __construct(
        AdapterFactoryInterface $adapterFactory,
        AmazonpayConfigInterface $config,
        AmazonpayToShipmentInterface $shipmentFacade
    ) {
        $this->adapterFactory = $adapterFactory;
        $this->config = $config;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShippingAddressQuoteDataUpdater()
    {
        return new ShippingAddressDataQuoteUpdater(
            $this->adapterFactory->createSetOrderReferenceDetailsAmazonpayAdapter(),
            $this->config
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createQuoteDataInitializer()
    {
        return new PrepareQuoteCollection(
            [
                $this->createAmazonpayDataQuoteInitializer(),
                $this->createCustomerDataQuoteUpdater(),
                $this->createShipmentDataQuoteInitializer(),
                $this->createPaymentDataQuoteUpdater(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createAmazonpayDataQuoteInitializer()
    {
        return new AmazonpayDataQuoteInitializer();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteInitializer()
    {
        return new ShipmentDataQuoteInitializer();
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteUpdater()
    {
        return new ShipmentDataQuoteUpdater(
            $this->shipmentFacade
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    protected function createCustomerDataQuoteUpdater()
    {
        return new CustomerDataQuoteUpdater(
            $this->adapterFactory->createObtainProfileInformationAdapter(),
            $this->config
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    protected function createPaymentDataQuoteUpdater()
    {
        return new PaymentDataQuoteUpdater();
    }

}
