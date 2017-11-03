<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Quote;

use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AdapterFactoryInterface;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMessengerInterface;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToShipmentInterface;

class QuoteUpdateFactory implements QuoteUpdateFactoryInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AdapterFactoryInterface
     */
    protected $adapterFactory;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AdapterFactoryInterface $adapterFactory
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToShipmentInterface $shipmentFacade
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMessengerInterface $messengerFacade
     */
    public function __construct(
        AdapterFactoryInterface $adapterFactory,
        AmazonPayToShipmentInterface $shipmentFacade,
        AmazonPayToMessengerInterface $messengerFacade
    ) {
        $this->adapterFactory = $adapterFactory;
        $this->shipmentFacade = $shipmentFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShippingAddressQuoteDataUpdater()
    {
        return new ShippingAddressDataQuoteUpdater(
            $this->adapterFactory->createSetOrderReferenceDetailsAmazonpayAdapter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface
     */
    public function createQuoteDataInitializer()
    {
        return new PrepareQuoteCollection(
            $this->messengerFacade,
            [
                $this->createAmazonpayDataQuoteInitializer(),
                $this->createCustomerDataQuoteUpdater(),
                $this->createShipmentDataQuoteInitializer(),
                $this->createPaymentDataQuoteUpdater(),
            ]
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface
     */
    public function createAmazonpayDataQuoteInitializer()
    {
        return new AmazonpayDataQuoteInitializer();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteInitializer()
    {
        return new ShipmentDataQuoteInitializer();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface
     */
    public function createShipmentDataQuoteUpdater()
    {
        return new ShipmentDataQuoteUpdater(
            $this->shipmentFacade
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface
     */
    protected function createCustomerDataQuoteUpdater()
    {
        return new CustomerDataQuoteUpdater(
            $this->adapterFactory->createObtainProfileInformationAdapter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Quote\QuoteUpdaterInterface
     */
    protected function createPaymentDataQuoteUpdater()
    {
        return new PaymentDataQuoteUpdater();
    }
}
