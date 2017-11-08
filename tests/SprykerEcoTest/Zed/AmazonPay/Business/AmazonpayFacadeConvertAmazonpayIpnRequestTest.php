<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\AmazonPay\Business;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;

class AmazonpayFacadeConvertAmazonpayIpnRequestTest extends AmazonpayFacadeAbstractTest
{
    const MESSAGE_ID = "2e809365-0774-5ed9-a2d6-c146730607b9";
    const TOPIC_ARN = "arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6";

    /**
     * @dataProvider updateRefundStatusDataProvider
     *
     * @param array $headers
     * @param string $body
     *
     * @return void
     */
    public function testConvertAmazonpayIpnRequest(array $headers, $body)
    {
        $result = $this->createFacade()->convertAmazonPayIpnRequest($headers, $body);
        $this->assertInstanceOf(AmazonpayIpnPaymentRequestTransfer::class, $result);
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
                    'X-Amz-Sns-Topic-Arn' => self::TOPIC_ARN,
                    'X-Amz-Sns-Message-Id' => self::MESSAGE_ID,
                    'X-Amz-Sns-Message-Type' => 'Notification',
                    'Connection' => 'close',
                    'Host' => 'amazonpay-endpoint.herokuapp.com',
                ],
                json_encode([
                  "Type" => "Notification",
                  "MessageId" => self::MESSAGE_ID,
                  "TopicArn" => self::TOPIC_ARN,
                  "Message" => "{\"ReleaseEnvironment\":\"Sandbox\",\"MarketplaceID\":\"136311\",\"Version\":\"2013-01-01\",\"NotificationType\":\"PaymentAuthorize\",\"SellerId\":\"A36VZZYZOVN3S6\",\"NotificationReferenceId\":\"05e053ae-875a-4a77-b8d8-aa7b624b68c3\",\"Timestamp\":\"2017-09-01T14:44:02.049Z\",\"NotificationData\":\"<?xml version=\\\"1.0\\\" encoding=\\\"UTF-8\\\"?><AuthorizationNotification xmlns=\\\"https://mws.amazonservices.com/ipn/OffAmazonPayments/2013-01-01\\\">\\n    <AuthorizationDetails>\\n        <AmazonAuthorizationId>S02-3068665-9038420-A021160<\\/AmazonAuthorizationId>\\n        <AuthorizationReferenceId>S02-3068665-903842059a97211412a9<\\/AuthorizationReferenceId>\\n        <AuthorizationAmount>\\n            <Amount>272.62<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/AuthorizationAmount>\\n        <CapturedAmount>\\n            <Amount>0.0<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/CapturedAmount>\\n        <AuthorizationFee>\\n            <Amount>0.0<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/AuthorizationFee>\\n        <IdList/>\\n        <CreationTimestamp>2017-09-01T14:43:29.536Z<\\/CreationTimestamp>\\n        <ExpirationTimestamp>2017-10-01T14:43:29.536Z<\\/ExpirationTimestamp>\\n        <AuthorizationStatus>\\n            <State>Open<\\/State>\\n            <LastUpdateTimestamp>2017-09-01T14:44:01.575Z<\\/LastUpdateTimestamp>\\n        <\\/AuthorizationStatus>\\n        <SoftDecline>false<\\/SoftDecline>\\n        <OrderItemCategories/>\\n        <CaptureNow>false<\\/CaptureNow>\\n        <SoftDescriptor/>\\n    <\\/AuthorizationDetails>\\n<\\/AuthorizationNotification>\"}",
                  "Timestamp" => "2017-09-01T14:44:02.109Z",
                  "SignatureVersion" => "1",
                  "Signature" => "i+cZDodzOym0zs6JieZN+AakFy+r+0qpCG1P1QwYMx2WG343h6HAEbzRqILwZxMTjKawdQzCGISyjaSzfJ78MNLuyng1ZBdIjgr7BzIjKc42AkcxyIH77BeS09ZrdAqViDSvQZbE2ydN5lMTT+OdypXtZfACDuwx520lhoiOH8buLYGJw/FwDvOg5yjuRt5ffj3TTC4pU8bs5VuHPH3dTAT7DMiJiwwO1mQIESPGCZWMtIyWcFbrteC7I/FKghYblHUrEjXgQDPNRCAEZ/SCHoGEVf/qrM4H4MbCwsPPY912VceDj5lk8k4ZR2jfiNS8/XzeHXLVC4+ehN4+zNj5Hg==",
                  "SigningCertURL" => "https://sns.eu-west-1.amazonaws.com/SimpleNotificationService-433026a4050d206028891664da859041.pem",
                  "UnsubscribeURL" => "https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128",
                ]),
            ],
            [
                [],
                's:2693:"{
  "Type" : "Notification",
  "MessageId" : "43e537a3-b786-5620-9e06-65dead920f18",
  "TopicArn" : "arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6",
  "Message" : "{\"ReleaseEnvironment\":\"Sandbox\",\"MarketplaceID\":\"136311\",\"Version\":\"2013-01-01\",\"NotificationType\":\"PaymentAuthorize\",\"SellerId\":\"A36VZZYZOVN3S6\",\"NotificationReferenceId\":\"0a968de7-1229-423a-bd69-4ab1dd07e713\",\"Timestamp\":\"2017-11-08T14:42:28.523Z\",\"NotificationData\":\"<?xml version=\\\"1.0\\\" encoding=\\\"UTF-8\\\"?><AuthorizationNotification xmlns=\\\"https://mws.amazonservices.com/ipn/OffAmazonPayments/2013-01-01\\\">\\n    <AuthorizationDetails>\\n        <AmazonAuthorizationId>S02-6007681-5678623-A042651<\\/AmazonAuthorizationId>\\n        <AuthorizationReferenceId>S02-6007681-56786235a0074b475f39<\\/AuthorizationReferenceId>\\n        <AuthorizationAmount>\\n            <Amount>39.44<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/AuthorizationAmount>\\n        <CapturedAmount>\\n            <Amount>0.0<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/CapturedAmount>\\n        <AuthorizationFee>\\n            <Amount>0.0<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/AuthorizationFee>\\n        <IdList/>\\n        <CreationTimestamp>2017-11-06T14:41:56.776Z<\\/CreationTimestamp>\\n        <ExpirationTimestamp>2017-12-06T14:41:56.776Z<\\/ExpirationTimestamp>\\n        <AuthorizationStatus>\\n            <State>Closed<\\/State>\\n            <LastUpdateTimestamp>2017-11-08T14:42:28.125Z<\\/LastUpdateTimestamp>\\n            <ReasonCode>ExpiredUnused<\\/ReasonCode>\\n        <\\/AuthorizationStatus>\\n        <SoftDecline>false<\\/SoftDecline>\\n        <OrderItemCategories/>\\n        <CaptureNow>false<\\/CaptureNow>\\n        <SoftDescriptor/>\\n    <\\/AuthorizationDetails>\\n<\\/AuthorizationNotification>\"}",
  "Timestamp" : "2017-11-08T14:42:28.533Z",
  "SignatureVersion" : "1",
  "Signature" : "HuyIE+P9RghI1JYPTiOEM5NzY8IHQH88nsvzr+rmgax3mRmVUHHin/gYVOrSuC1V2crCqu8PIWkMlnVqnaJDDs9a1Gu0V2afPSbd8peQgXPxhuv6wFGNhSeusqG/W05G7rNwU1TydwxjkZ3ei0dVmVAdH7FCGiTpOfcKJ1/VktWqOD2uGGXO8R5X6uM8Hv7/sBrmBaa1doToR8uiGFl+YcWhcrZI9xM5k8PwItgdnHKYj1l49Un/qrVZZQmqUE8HDEmKRY8jP+YZom8QfxB5K6LpnbKrmsnWf24wIGdjP/Rsk5l6kUmtqp+IhNl9fTyzb+OIFyWmzXf2cS6OUwihyw==",
  "SigningCertURL" : "https://sns.eu-west-1.amazonaws.com/SimpleNotificationService-433026a4050d206028891664da859041.pem",
  "UnsubscribeURL" : "https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128"
}";',
            ],
            [
                [],
                's:2703:"{
  "Type" : "Notification",
  "MessageId" : "01b8375f-3434-5adb-ba7a-65222e5c42a7",
  "TopicArn" : "arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6",
  "Message" : "{\"ReleaseEnvironment\":\"Sandbox\",\"MarketplaceID\":\"136311\",\"Version\":\"2013-01-01\",\"NotificationType\":\"PaymentAuthorize\",\"SellerId\":\"A36VZZYZOVN3S6\",\"NotificationReferenceId\":\"c6ee0833-dc22-4458-be1f-0c89a0e58d27\",\"Timestamp\":\"2017-11-08T14:43:52.573Z\",\"NotificationData\":\"<?xml version=\\\"1.0\\\" encoding=\\\"UTF-8\\\"?><AuthorizationNotification xmlns=\\\"https://mws.amazonservices.com/ipn/OffAmazonPayments/2013-01-01\\\">\\n    <AuthorizationDetails>\\n        <AmazonAuthorizationId>S02-8084966-1007363-A095465<\\/AmazonAuthorizationId>\\n        <AuthorizationReferenceId>S02-8084966-10073635a031808d6004<\\/AuthorizationReferenceId>\\n        <AuthorizationAmount>\\n            <Amount>289.49<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/AuthorizationAmount>\\n        <CapturedAmount>\\n            <Amount>0.0<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/CapturedAmount>\\n        <AuthorizationFee>\\n            <Amount>0.0<\\/Amount>\\n            <CurrencyCode>EUR<\\/CurrencyCode>\\n        <\\/AuthorizationFee>\\n        <IdList/>\\n        <CreationTimestamp>2017-11-08T14:43:21.210Z<\\/CreationTimestamp>\\n        <ExpirationTimestamp>2017-12-08T14:43:21.210Z<\\/ExpirationTimestamp>\\n        <AuthorizationStatus>\\n            <State>Declined<\\/State>\\n            <LastUpdateTimestamp>2017-11-08T14:43:51.868Z<\\/LastUpdateTimestamp>\\n            <ReasonCode>InvalidPaymentMethod<\\/ReasonCode>\\n        <\\/AuthorizationStatus>\\n        <SoftDecline>false<\\/SoftDecline>\\n        <OrderItemCategories/>\\n        <CaptureNow>false<\\/CaptureNow>\\n        <SoftDescriptor/>\\n    <\\/AuthorizationDetails>\\n<\\/AuthorizationNotification>\"}",
  "Timestamp" : "2017-11-08T14:43:52.590Z",
  "SignatureVersion" : "1",
  "Signature" : "VHNu7UPfEWcahT97HvNURXuJv0sSn4anXJpBf8Uzr3OxfcxR0lRdsy3wunn1D0kQbVCr5N9vEp9g+UUekm7pxXXd7QzWh6aYzM9cStf0vN+Pf0uBtrv9XGPM+L9+4n10XAKva/T0lPmZahsTk9MhDmiQ3e9HBjWExdOOwSm/ceHFNIxEjQgmXlFch9sCyhyvvdb4iElgaz87hzuERSNXfieGILIWuuax0l9jWlr1twpUMxCQTaCCwjRZhrlGsYMcJ3p5FCQjxkXIB4tqRh1WNn7T9GbxY+laRYyfK/5nXEE9ZTymtUb2SjhsgE0YzLUs4BGnKfS/8rzgxI2QC6fQrA==",
  "SigningCertURL" : "https://sns.eu-west-1.amazonaws.com/SimpleNotificationService-433026a4050d206028891664da859041.pem",
  "UnsubscribeURL" : "https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128"
}";',
            ],
            [
                [],
                '',
            ],
            [
                [],
                '',
            ],
            [
                [],
                '',
            ],
            [
                [],
                '',
            ],
            [
                [],
                '',
            ],
            [
                [],
                '',
            ],
            [
                [],
                '',
            ],
            [
                [],
                '',
            ],
        ];
    }
}
