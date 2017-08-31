<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk;

use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AbstractAdapter;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\AbstractConverter;

class GetCaptureDetailsResponse extends AbstractResponse
{

    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        parent::__construct($requestParameters);

        $amazonCaptureId = $requestParameters[AbstractAdapter::AMAZON_CAPTURE_ID];
        $status = $this->getStatus($amazonCaptureId);

        $this->responseBodyXml =
            '<GetCaptureDetailsResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <GetCaptureDetailsResult>
    <CaptureDetails>
      <CaptureReferenceId>S02-8184717-938223659a7e23782832</CaptureReferenceId>
      <CaptureFee>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>0.00</Amount>
      </CaptureFee>
      <SoftDescriptor>AMZ*spryker</SoftDescriptor>
      <IdList/>
      <CaptureAmount>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>200.16</Amount>
      </CaptureAmount>
      <AmazonCaptureId>'.$amazonCaptureId.'</AmazonCaptureId>
      <CreationTimestamp>2017-08-31T10:17:33.516Z</CreationTimestamp>
      <CaptureStatus>
        <LastUpdateTimestamp>2017-08-31T10:17:33.516Z</LastUpdateTimestamp>
        <State>'.$status.'</State>
      </CaptureStatus>
      <SellerCaptureNote/>
      <RefundedAmount>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>0</Amount>
      </RefundedAmount>
    </CaptureDetails>
  </GetCaptureDetailsResult>
  <ResponseMetadata>
    <RequestId>fee65e77-8f80-440d-8424-4dbb7d19b9e6</RequestId>
  </ResponseMetadata>
</GetCaptureDetailsResponse>';
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    protected function getStatus($reference)
    {
        switch ($reference) {
            case 'S02-5989383-0864061-C000001':
                return AbstractConverter::STATUS_COMPLETED;
            case 'S02-5989383-0864061-C000002':
                return AbstractConverter::STATUS_DECLINED;
            case 'S02-5989383-0864061-C000003':
                return AbstractConverter::STATUS_PENDING;
            case 'S02-5989383-0864061-C000004':
                return AbstractConverter::STATUS_CLOSED;
        }

        throw new \Exception('Not mocked request.');
    }

}
