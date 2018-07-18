<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business\Mock\Adapter\Sdk;

use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\AuthorizeAdapter;

class AuthorizeResponse extends AbstractResponse
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var boolean
     */
    protected $captureNow;

    /**
     * @var int
     */
    protected $transactionTimeout;

    /**
     * @param array $requestParameters
     */
    public function __construct(array $requestParameters)
    {
        parent::__construct($requestParameters);

        $this->captureNow = $requestParameters[AuthorizeAdapter::CAPTURE_NOW];
        $this->transactionTimeout = $requestParameters[AuthorizeAdapter::TRANSACTION_TIMEOUT];

        $this->responseBodyXml =
            '<AuthorizeResponse xmlns="http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01">
  <AuthorizeResult>
    <AuthorizationDetails>
      <AuthorizationAmount>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>' . $requestParameters['authorization_amount'] . '</Amount>
      </AuthorizationAmount>
      <CapturedAmount>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>' . $requestParameters['authorization_amount'] . '</Amount>
      </CapturedAmount>
      <SoftDescriptor>AMZ*spryker</SoftDescriptor>
      <ExpirationTimestamp>2017-06-14T09:52:52.911Z</ExpirationTimestamp>'
            . $this->getAuthorizationStatusXml() .
        '<AuthorizationFee>
        <CurrencyCode>EUR</CurrencyCode>
        <Amount>0.00</Amount>
      </AuthorizationFee>
      <AuthorizationBillingAddress>
        <City>Neunkirchen</City>
        <CountryCode>DE</CountryCode>
        <PostalCode>66538</PostalCode>
        <Name>Liam Barker</Name>
        <AddressLine1/>
        <AddressLine2>Meininger Strasse 58</AddressLine2>
      </AuthorizationBillingAddress>
      <CaptureNow>' . $requestParameters['capture_now'] . '</CaptureNow>
      <SellerAuthorizationNote/>
      <CreationTimestamp>2017-05-15T09:52:52.911Z</CreationTimestamp>
      <AmazonAuthorizationId>' . $this->orderReferenceId . '</AmazonAuthorizationId>
      <AuthorizationReferenceId>S02-6182376-418949759197a6b8ba90</AuthorizationReferenceId>
    </AuthorizationDetails>
  </AuthorizeResult>
  <ResponseMetadata>
    <RequestId>4bcc7524-e749-446b-93c7-e33636f461b6</RequestId>
  </ResponseMetadata>
</AuthorizeResponse>';
    }

    /**
     * @return string
     */
    protected function getAuthorizationStatusXml()
    {
        $idList = '<IdList>
        <member>S02-6182376-4189497-C003388</member>
      </IdList>';

        $authorizationStatus = '
      <SoftDecline>false</SoftDecline>
      <AuthorizationStatus>
        <LastUpdateTimestamp>2017-05-15T09:52:52.911Z</LastUpdateTimestamp>
        <State>%s</State>
        <ReasonCode>%s</ReasonCode>
      </AuthorizationStatus>';

        // synchronous with captureNow
        if ($this->captureNow && $this->transactionTimeout === 0) {
            switch ($this->orderReferenceId) {
                case AbstractResponse::ORDER_REFERENCE_ID_1:
                    return $idList . sprintf($authorizationStatus, 'Closed', 'MaxCapturesProcessed');

                case AbstractResponse::ORDER_REFERENCE_ID_2:
                    return sprintf($authorizationStatus, 'Declined', 'AmazonRejected');

                case AbstractResponse::ORDER_REFERENCE_ID_3:
                    return sprintf($authorizationStatus, 'Declined', 'InvalidPaymentMethod');
            }
        }

        // asyncronous with captureNow
        if ($this->captureNow && $this->transactionTimeout > 0) {
            return $idList . sprintf($authorizationStatus, 'Pending', '');
        }

        // synchronous without capturenow
        if (!$this->captureNow && $this->transactionTimeout === 0) {
            switch ($this->orderReferenceId) {
                case AbstractResponse::ORDER_REFERENCE_ID_1:
                    return sprintf($authorizationStatus, 'Open', '');

                case AbstractResponse::ORDER_REFERENCE_ID_2:
                    return sprintf($authorizationStatus, 'Declined', 'AmazonRejected');

                case AbstractResponse::ORDER_REFERENCE_ID_3:
                    return sprintf($authorizationStatus, 'Declined', 'InvalidPaymentMethod');
            }
        }

        // asynchronous without capturenow
        if (!$this->captureNow && $this->transactionTimeout > 0) {
            return sprintf($authorizationStatus, 'Pending', '');
        }
    }
}
