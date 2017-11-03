<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Amazonpay\Business\Mock\Adapter\Sdk;

class CancelOrderReferenceResponse extends AbstractResponse
{
    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        parent::__construct($requestParameters);

        $this->responseBodyXml =
            '<CancelOrderReferenceResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <CancelOrderReferenceResult/>
  <ResponseMetadata>
    <RequestId>5a92d880-d75a-43ab-afcd-8cc2821121cc</RequestId>
  </ResponseMetadata>
</CancelOrderReferenceResponse>';
    }
}
