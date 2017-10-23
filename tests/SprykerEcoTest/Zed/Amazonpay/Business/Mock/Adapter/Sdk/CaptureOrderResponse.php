<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\Sdk;

use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CaptureOrderAdapter;

class CaptureOrderResponse extends AbstractResponse
{
    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        parent::__construct($requestParameters);

        $authorizationId = $requestParameters[CaptureOrderAdapter::AMAZON_AUTHORIZATION_ID];
        $referenceId = $requestParameters[CaptureOrderAdapter::CAPTURE_REFERENCE_ID];

        $this->responseBodyXml =
            '<CaptureResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <CaptureResult>
    <CaptureDetails>
      <CaptureReferenceId>' . $referenceId . '</CaptureReferenceId>
      <CaptureFee>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>0.00</Amount>
      </CaptureFee>
      <AmazonCaptureId>' . $authorizationId . 'C' . '</AmazonCaptureId>
      <CreationTimestamp>2017-05-17T07:56:20.134Z</CreationTimestamp>
      <SoftDescriptor>AMZ*spryker</SoftDescriptor>
      <IdList/>
      <CaptureStatus>
        <LastUpdateTimestamp>2017-05-17T07:56:20.134Z</LastUpdateTimestamp>
        <State>' . $this->getStatus($authorizationId) . '</State>
      </CaptureStatus>
      <SellerCaptureNote/>
      <RefundedAmount>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>0</Amount>
      </RefundedAmount>
      <CaptureAmount>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>105.89</Amount>
      </CaptureAmount>
    </CaptureDetails>
  </CaptureResult>
  <ResponseMetadata>
    <RequestId>9f9fb88f-4cea-48e4-8d3c-0445d6878ea7</RequestId>
  </ResponseMetadata>
</CaptureResponse>';
    }

    /**
     * @param string $authorizationId
     *
     * @return string
     */
    protected function getStatus($authorizationId)
    {
        switch ($authorizationId) {
            case 'S02-5989383-0864061-A000001':
                return 'Completed';
            case 'S02-5989383-0864061-A000002':
                return 'Pending';
            case 'S02-5989383-0864061-A000003':
                return 'Declined';
        }
    }
}
