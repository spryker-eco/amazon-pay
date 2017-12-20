<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk;

class CloseOrderResponse extends AbstractResponse
{
    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        parent::__construct($requestParameters);

        $this->responseBodyXml =
            '<CloseOrderReferenceResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <CloseOrderReferenceResult/>
  <ResponseMetadata>
    <RequestId>73a4ee52-b440-461c-b40b-03dc7691443f</RequestId>
  </ResponseMetadata>
</CloseOrderReferenceResponse>
';
    }
}
