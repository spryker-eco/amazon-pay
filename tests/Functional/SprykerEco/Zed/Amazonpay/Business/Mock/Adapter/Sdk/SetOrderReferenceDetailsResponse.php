<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk;


class SetOrderReferenceDetailsResponse extends AbstractResponse
{

    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        parent::__construct($requestParameters);

        $this->responseBodyXml =
            '<SetOrderReferenceDetailsResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <SetOrderReferenceDetailsResult>
    <OrderReferenceDetails>
      <OrderReferenceStatus>
        <State>Draft</State>
      </OrderReferenceStatus>
      <OrderLanguage>de-DE</OrderLanguage>
      <Destination>
        <DestinationType>Physical</DestinationType>
        <PhysicalDestination>
        ' . $this->getPhysicalDestinationXml() . '
        </PhysicalDestination>
      </Destination>
      <ExpirationTimestamp>2017-11-12T08:23:08.575Z</ExpirationTimestamp>
      <PlatformId>A36VZZYZOVN3S6</PlatformId>
      <SellerOrderAttributes/>
      <OrderTotal>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>179.98</Amount>
      </OrderTotal>
      <ReleaseEnvironment>Sandbox</ReleaseEnvironment>
      <AmazonOrderReferenceId>S02-0519064-7425266</AmazonOrderReferenceId>
      <CreationTimestamp>2017-05-16T08:23:08.575Z</CreationTimestamp>
      <RequestPaymentAuthorization>false</RequestPaymentAuthorization>
    </OrderReferenceDetails>
  </SetOrderReferenceDetailsResult>
  <ResponseMetadata>
    <RequestId>0adcea56-de1b-447c-9114-86a6aa74cdf8</RequestId>
  </ResponseMetadata>
</SetOrderReferenceDetailsResponse>';
    }

    /**
     * @return string
     */
    protected function getPhysicalDestinationXml()
    {
        $destinationXml = '<City>%s</City>
          <CountryCode>%s</CountryCode>
          <PostalCode>%s</PostalCode>';

        switch ($this->orderReferenceId) {
            case 'S02-1234567-0000001':
                return sprintf($destinationXml, 'Barcelona', 'ES', '0895');

            case 'S02-1234567-0000002':
                return sprintf($destinationXml, 'London', 'GB', 'SE1 2BY');

            case 'S02-1234567-0000003':
                return sprintf($destinationXml, 'Wien', 'AT', '1050');
                break;
        }
    }
}
