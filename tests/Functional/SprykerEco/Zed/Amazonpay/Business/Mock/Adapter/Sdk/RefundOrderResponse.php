<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk;

use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AbstractAdapter;

class RefundOrderResponse extends AbstractResponse
{

    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        parent::__construct($requestParameters);

        $orderReferenceId = $requestParameters[AbstractAdapter::AMAZON_ORDER_REFERENCE_ID];

        $this->responseBodyXml =
            '<RefundResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <RefundResult>
    <RefundDetails>
      <RefundReferenceId>S02-4495306-3410374591c69427e8af</RefundReferenceId>
      <RefundType>SellerInitiated</RefundType>
      <SellerRefundNote/>
      <CreationTimestamp>2017-05-17T15:16:29.157Z</CreationTimestamp>
      <RefundStatus>
        <LastUpdateTimestamp>2017-05-17T15:16:29.157Z</LastUpdateTimestamp>
        <State>' . $this->getStatus($orderReferenceId) . '</State>
      </RefundStatus>
      <SoftDescriptor>AMZ*spryker</SoftDescriptor>
      <FeeRefunded>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>0.00</Amount>
      </FeeRefunded>
      <RefundAmount>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>104.89</Amount>
      </RefundAmount>
      <AmazonRefundId>S02-4495306-3410374-R069304</AmazonRefundId>
    </RefundDetails>
  </RefundResult>
  <ResponseMetadata>
    <RequestId>15beeaa3-3ec6-4ccb-a29e-feb5df0aa11e</RequestId>
  </ResponseMetadata>
</RefundResponse>';
    }

    /**
     * @param string $orderReferenceId
     *
     * @return string
     */
    protected function getStatus($orderReferenceId)
    {
        switch ($orderReferenceId)
        {
            case 'S02-5989383-0864061-0000001':
                return 'Completed';
            case 'S02-5989383-0864061-0000002':
                return 'Pending';
            case 'S02-5989383-0864061-0000003':
                return 'Declined';
        }
    }

}
