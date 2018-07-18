<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk;

use Exception;
use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AbstractAdapter;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\AbstractConverter;

class GetRefundDetailsResponse extends AbstractResponse
{
    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        parent::__construct($requestParameters);

        $reference = $requestParameters[AbstractAdapter::AMAZON_REFUND_ID];
        $status = $this->getStatus($reference);

        $this->responseBodyXml =
            '<GetRefundDetailsResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <GetRefundDetailsResult>
    <RefundDetails>
      <SellerRefundNote/>
      <RefundStatus>
        <LastUpdateTimestamp>2017-08-31T12:13:24.941Z</LastUpdateTimestamp>
        <State>' . $status . '</State>
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
      <AmazonRefundId>' . $reference . '</AmazonRefundId>
    </RefundDetails>
  </GetRefundDetailsResult>
  <ResponseMetadata>
    <RequestId>21a79433-6a27-44ae-a614-6207e4846a4c</RequestId>
  </ResponseMetadata>
</GetRefundDetailsResponse>';
    }

    /**
     * @param string $reference
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getStatus($reference)
    {
        switch ($reference) {
            case 'S02-5989383-0864061-R000001':
                return AbstractConverter::STATUS_COMPLETED;
            case 'S02-5989383-0864061-R000002':
                return AbstractConverter::STATUS_DECLINED;
            case 'S02-5989383-0864061-R000003':
                return AbstractConverter::STATUS_PENDING;
        }

        throw new Exception('Not mocked request.');
    }
}
