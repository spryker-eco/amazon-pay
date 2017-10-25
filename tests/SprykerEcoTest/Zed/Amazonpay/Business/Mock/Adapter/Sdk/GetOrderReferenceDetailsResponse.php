<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\Sdk;

class GetOrderReferenceDetailsResponse extends AbstractResponse
{
    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        parent::__construct($requestParameters);

        $this->responseBodyXml =
            '<GetOrderReferenceDetailsResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <GetOrderReferenceDetailsResult>
    <OrderReferenceDetails>
      <OrderReferenceStatus>
        <LastUpdateTimestamp>2017-05-16T15:10:56.533Z</LastUpdateTimestamp>
        <State>Open</State>
      </OrderReferenceStatus>
      <OrderLanguage>de-DE</OrderLanguage>
      <Destination>
        <DestinationType>Physical</DestinationType>
        <PhysicalDestination>
        ' . $this->getPhysicalDestinationXml() . '
        </PhysicalDestination>
      </Destination>
      <ExpirationTimestamp>2017-11-12T15:09:50.447Z</ExpirationTimestamp>
      <PlatformId>A36VZZYZOVN3S6</PlatformId>
      <IdList/>
      <SellerOrderAttributes>
        <SellerOrderId>S02-9733147-1514483591b167fcc363</SellerOrderId>
      </SellerOrderAttributes>
      <OrderTotal>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>105.89</Amount>
      </OrderTotal>
      <Buyer>
        <Name>John Doe</Name>
        <Email>john@doe.xxx</Email>
      </Buyer>
      <ReleaseEnvironment>Sandbox</ReleaseEnvironment>
      <AmazonOrderReferenceId>S02-9733147-1514483</AmazonOrderReferenceId>
      <CreationTimestamp>2017-05-16T15:09:50.447Z</CreationTimestamp>
      <BillingAddress>
        <PhysicalAddress>
          <City>Neunkirchen</City>
          <CountryCode>DE</CountryCode>
          <PostalCode>66538</PostalCode>
          <Name>Liam Barker</Name>
          <AddressLine1/>
          <AddressLine2>Meininger Strasse 58</AddressLine2>
        </PhysicalAddress>
        <AddressType>Physical</AddressType>
      </BillingAddress>
      <RequestPaymentAuthorization>false</RequestPaymentAuthorization>
    </OrderReferenceDetails>
  </GetOrderReferenceDetailsResult>
  <ResponseMetadata>
    <RequestId>cc2cb8b7-519d-4060-8314-dd0eb2be7b20</RequestId>
  </ResponseMetadata>
</GetOrderReferenceDetailsResponse>';
    }

    /**
     * @return string
     */
    protected function getPhysicalDestinationXml()
    {
        $destinationXml = '<City>%s</City>
          <Phone>%s</Phone>
          <CountryCode>%s</CountryCode>
          <PostalCode>%s</PostalCode>
          <Name>%s</Name>
          <AddressLine2>%s</AddressLine2>';

        switch ($this->orderReferenceId) {
            case AbstractResponse::ORDER_REFERENCE_ID_1:
                return sprintf($destinationXml, 'Barcelona', '+880 9900-111111', 'ES', '08915', 'Maria Garcia', 'Carrer del Torrent Vallmajor, 100 Badalona');

            case AbstractResponse::ORDER_REFERENCE_ID_2:
                return sprintf($destinationXml, 'London', '+44774999888', 'GB', 'SE1 2BY', 'Elisabeth Harrison', '4973 Primrose Lane');

            case AbstractResponse::ORDER_REFERENCE_ID_3:
                return sprintf($destinationXml, 'Wien', '+4319999999', 'AT', '1050', 'Karl Küfer', 'Matzleinsdorferplatz 9999');
        }
    }
}
