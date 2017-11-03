<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk;

class ConfirmOrderReferenceResponse extends AbstractResponse
{
    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        parent::__construct($requestParameters);

        $this->responseBodyXml =
            '<ConfirmOrderReferenceResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <ConfirmOrderReferenceResult/>
  <ResponseMetadata>
    <RequestId>bf6766fa-0828-489a-abdc-919d78563364</RequestId>
  </ResponseMetadata>
</ConfirmOrderReferenceResponse>';
    }
}
