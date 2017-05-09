<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentInterface;

class QuoteUpdateFactory implements QuoteUpdateFactoryInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactory
     */
    protected $adapterFactory;

    /**
     * @var \SprykerEco\Shared\Amazonpay\AmazonpayConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AdapterFactoryInterface $adapterFactory
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToShipmentInterface $shipmentFacade
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
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShippingAddressQuoteDataUpdater()
    {
        return new ShippingAddressDataQuoteUpdater(
            $this->adapterFactory->createSetOrderReferenceDetailsAmazonpayAdapter(),
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
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
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createAmazonpayDataQuoteInitializer()
    {
        return new AmazonpayDataQuoteInitializer();
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteInitializer()
    {
        return new ShipmentDataQuoteInitializer();
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteUpdater()
    {
        return new ShipmentDataQuoteUpdater(
            $this->shipmentFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    protected function createCustomerDataQuoteUpdater()
    {
        return new CustomerDataQuoteUpdater(
            $this->adapterFactory->createObtainProfileInformationAdapter(),
            $this->config
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Quote\QuoteUpdaterInterface
     */
    protected function createPaymentDataQuoteUpdater()
    {
        return new PaymentDataQuoteUpdater();
    }

}
