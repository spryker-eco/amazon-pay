<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk;

use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AbstractAdapter;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\AbstractConverter;

class GetRefundDetailsResponse extends AbstractResponse
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
            '<GetRefundDetailsResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <GetRefundDetailsResult>
    <RefundDetails>
      <SellerRefundNote/>
      <RefundStatus>
        <LastUpdateTimestamp>2017-08-31T12:13:24.941Z</LastUpdateTimestamp>
        <State>'.$status.'</State>
      </RefundStatus>
      <SoftDescriptor>AMZ*spryker</SoftDescriptor>
      <RefundReferenceId>S02-9741363-429898559a7fd6462a8e</RefundReferenceId>
      <RefundType>SellerInitiated</RefundType>
      <CreationTimestamp>2017-08-31T12:13:24.941Z</CreationTimestamp>
      <FeeRefunded>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>0.00</Amount>
      </FeeRefunded>
      <RefundAmount>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>224.09</Amount>
      </RefundAmount>
      <AmazonRefundId>S02-9741363-4298985-R056377</AmazonRefundId>
    </RefundDetails>
  </GetRefundDetailsResult>
  <ResponseMetadata>
    <RequestId>21a79433-6a27-44ae-a614-6207e4846a4c</RequestId>
  </ResponseMetadata>
</GetRefundDetailsResponse>
';
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
