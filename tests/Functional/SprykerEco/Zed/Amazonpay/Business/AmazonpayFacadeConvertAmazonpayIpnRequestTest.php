<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Amazonpay\Business;

use Functional\SprykerEco\Zed\Amazonpay\Business\Mock\Adapter\Sdk\AbstractResponse;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class AmazonpayFacadeConvertAmazonpayIpnRequestTest extends AmazonpayFacadeAbstractTest
{

    /**
     * @dataProvider updateRefundStatusDataProvider
     *
     * @param array $headers
     * @param string $body
     */
    public function testConvertAmazonpayIpnRequest(array $headers, $body)
    {
        $result = $this->createFacade()->convertAmazonpayIpnRequest($headers, $body);
        $this->assertInstanceOf(TransferInterface::class, $result);
    }

    /**
     * @return array
     */
    public function updateRefundStatusDataProvider()
    {
        return [
            [
                [
                    'Content-Length' => '2637',
                    'Total-Route-Time' => '24176',
                    'X-Request-Start' => '1504277042247',
                    'Connect-Time' => '0',
                    'Via' => '1.1 vegur',
                    'X-Forwarded-Port' => '443',
                    'X-Forwarded-Proto' => 'https',
                    'X-Forwarded-For' => '54.240.197.103',
                    'X-Request-Id' => '29fd8b95-34b2-4459-8d5f-7ec7394aa750',
                    'Accept-Encoding' => 'gzip,deflate',
                    'User-Agent' => 'Amazon Simple Notification Service Agent',
                    'Content-Type' => 'text/plain; charset=UTF-8',
                    'X-Amz-Sns-Subscription-Arn' => 'arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128',
                    'X-Amz-Sns-Topic-Arn' => 'arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6',
                    'X-Amz-Sns-Message-Id' => '2e809365-0774-5ed9-a2d6-c146730607b9',
                    'X-Amz-Sns-Message-Type' => 'Notification',
                    'Connection' => 'close',
                    'Host' => 'amazonpay-endpoint.herokuapp.com',
                ],
                json_encode([
                  "Type" => "Notification",
                  "MessageId" => "2e809365-0774-5ed9-a2d6-c146730607b9",
                  "TopicArn" => "arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6",
                  "Message" => "{\"ReleaseEnvironment\":\"Sandbox\",\"MarketplaceID\":\"136311\",\"Version\":\"2013-01-01\",\"NotificationType\":\"PaymentAuthorize\",\"SellerId\":\"A36VZZYZOVN3S6\",\"NotificationReferenceId\":\"05e053ae-875a-4a77-b8d8-aa7b624b68c3\",\"Timestamp\":\"2017-09-01T14:44:02.049Z\",\"NotificationData\":\"<?xml version=\\\"1.0\\\" encoding=\\\"UTF-8\\\"?><AuthorizationNotification xmlns=\\\"https://mws.amazonservices.com/ipn/OffAmazonPayments/2013-01-01\\\">\\n    <AuthorizationDetails>\\n        <AmazonAuthorizationId>S02-3068665-9038420-A021160<\\/AmazonAuthorizationId>\\n        <AuthorizationReferenceId>S02-3068665-903842059a97211412a9<\\/AuthorizationReferenceId>\\n        <AuthorizationAmount>\\n            <Amount>272.62<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/AuthorizationAmount>\\n        <CapturedAmount>\\n            <Amount>0.0<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/CapturedAmount>\\n        <AuthorizationFee>\\n            <Amount>0.0<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/AuthorizationFee>\\n        <IdList/>\\n        <CreationTimestamp>2017-09-01T14:43:29.536Z<\\/CreationTimestamp>\\n        <ExpirationTimestamp>2017-10-01T14:43:29.536Z<\\/ExpirationTimestamp>\\n        <AuthorizationStatus>\\n            <State>Open<\\/State>\\n            <LastUpdateTimestamp>2017-09-01T14:44:01.575Z<\\/LastUpdateTimestamp>\\n        <\\/AuthorizationStatus>\\n        <SoftDecline>false<\\/SoftDecline>\\n        <OrderItemCategories/>\\n        <CaptureNow>false<\\/CaptureNow>\\n        <SoftDescriptor/>\\n    <\\/AuthorizationDetails>\\n<\\/AuthorizationNotification>\"}",
                  "Timestamp" => "2017-09-01T14:44:02.109Z",
                  "SignatureVersion" => "1",
                  "Signature" => "i+cZDodzOym0zs6JieZN+AakFy+r+0qpCG1P1QwYMx2WG343h6HAEbzRqILwZxMTjKawdQzCGISyjaSzfJ78MNLuyng1ZBdIjgr7BzIjKc42AkcxyIH77BeS09ZrdAqViDSvQZbE2ydN5lMTT+OdypXtZfACDuwx520lhoiOH8buLYGJw/FwDvOg5yjuRt5ffj3TTC4pU8bs5VuHPH3dTAT7DMiJiwwO1mQIESPGCZWMtIyWcFbrteC7I/FKghYblHUrEjXgQDPNRCAEZ/SCHoGEVf/qrM4H4MbCwsPPY912VceDj5lk8k4ZR2jfiNS8/XzeHXLVC4+ehN4+zNj5Hg==",
                  "SigningCertURL" => "https://sns.eu-west-1.amazonaws.com/SimpleNotificationService-433026a4050d206028891664da859041.pem",
                  "UnsubscribeURL" => "https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128"
                ]),
            ],
        ];
    }

}
