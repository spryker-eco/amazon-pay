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
        $this->markTestSkipped('Should be rewritten to test converter, and not include the call to Amazon');

        $result = $this->createFacade()->convertAmazonPayIpnRequest($headers, $body);
        $this->assertInstanceOf(AmazonpayIpnPaymentRequestTransfer::class, $result);
    }

    /**
     * @return array
     */
    public function updateRefundStatusDataProvider()
    {
        $header = [
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
        ];
        return [
            [
                $header,
                unserialize('s:2693:"{
  "Type" : "Notification",
  "MessageId" : "43e537a3-b786-5620-9e06-65dead920f18",
  "TopicArn" : "arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6",
  "Message" : "{\\"ReleaseEnvironment\\":\\"Sandbox\\",\\"MarketplaceID\\":\\"136311\\",\\"Version\\":\\"2013-01-01\\",\\"NotificationType\\":\\"PaymentAuthorize\\",\\"SellerId\\":\\"A36VZZYZOVN3S6\\",\\"NotificationReferenceId\\":\\"0a968de7-1229-423a-bd69-4ab1dd07e713\\",\\"Timestamp\\":\\"2017-11-08T14:42:28.523Z\\",\\"NotificationData\\":\\"<' . '?xml version=\\\\\\"1.0\\\\\\" encoding=\\\\\\"UTF-8\\\\\\"?' . '><AuthorizationNotification xmlns=\\\\\\"https://mws.amazonservices.com/ipn/OffAmazonPayments/2013-01-01\\\\\\">\\\\n    <AuthorizationDetails>\\\\n        <AmazonAuthorizationId>S02-6007681-5678623-A042651<\\\\/AmazonAuthorizationId>\\\\n        <AuthorizationReferenceId>S02-6007681-56786235a0074b475f39<\\\\/AuthorizationReferenceId>\\\\n        <AuthorizationAmount>\\\\n            <Amount>39.44<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/AuthorizationAmount>\\\\n        <CapturedAmount>\\\\n            <Amount>0.0<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/CapturedAmount>\\\\n        <AuthorizationFee>\\\\n            <Amount>0.0<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/AuthorizationFee>\\\\n        <IdList/>\\\\n        <CreationTimestamp>2017-11-06T14:41:56.776Z<\\\\/CreationTimestamp>\\\\n        <ExpirationTimestamp>2017-12-06T14:41:56.776Z<\\\\/ExpirationTimestamp>\\\\n        <AuthorizationStatus>\\\\n            <State>Closed<\\\\/State>\\\\n            <LastUpdateTimestamp>2017-11-08T14:42:28.125Z<\\\\/LastUpdateTimestamp>\\\\n            <ReasonCode>ExpiredUnused<\\\\/ReasonCode>\\\\n        <\\\\/AuthorizationStatus>\\\\n        <SoftDecline>false<\\\\/SoftDecline>\\\\n        <OrderItemCategories/>\\\\n        <CaptureNow>false<\\\\/CaptureNow>\\\\n        <SoftDescriptor/>\\\\n    <\\\\/AuthorizationDetails>\\\\n<\\\\/AuthorizationNotification>\\"}",
  "Timestamp" : "2017-11-08T14:42:28.533Z",
  "SignatureVersion" : "1",
  "Signature" : "HuyIE+P9RghI1JYPTiOEM5NzY8IHQH88nsvzr+rmgax3mRmVUHHin/gYVOrSuC1V2crCqu8PIWkMlnVqnaJDDs9a1Gu0V2afPSbd8peQgXPxhuv6wFGNhSeusqG/W05G7rNwU1TydwxjkZ3ei0dVmVAdH7FCGiTpOfcKJ1/VktWqOD2uGGXO8R5X6uM8Hv7/sBrmBaa1doToR8uiGFl+YcWhcrZI9xM5k8PwItgdnHKYj1l49Un/qrVZZQmqUE8HDEmKRY8jP+YZom8QfxB5K6LpnbKrmsnWf24wIGdjP/Rsk5l6kUmtqp+IhNl9fTyzb+OIFyWmzXf2cS6OUwihyw==",
  "SigningCertURL" : "https://sns.eu-west-1.amazonaws.com/SimpleNotificationService-433026a4050d206028891664da859041.pem",
  "UnsubscribeURL" : "https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128"
}";', []),
            ],
            [
                $header,
                unserialize('s:2703:"{
  "Type" : "Notification",
  "MessageId" : "01b8375f-3434-5adb-ba7a-65222e5c42a7",
  "TopicArn" : "arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6",
  "Message" : "{\\"ReleaseEnvironment\\":\\"Sandbox\\",\\"MarketplaceID\\":\\"136311\\",\\"Version\\":\\"2013-01-01\\",\\"NotificationType\\":\\"PaymentAuthorize\\",\\"SellerId\\":\\"A36VZZYZOVN3S6\\",\\"NotificationReferenceId\\":\\"c6ee0833-dc22-4458-be1f-0c89a0e58d27\\",\\"Timestamp\\":\\"2017-11-08T14:43:52.573Z\\",\\"NotificationData\\":\\"<' . '?xml version=\\\\\\"1.0\\\\\\" encoding=\\\\\\"UTF-8\\\\\\"?' . '><AuthorizationNotification xmlns=\\\\\\"https://mws.amazonservices.com/ipn/OffAmazonPayments/2013-01-01\\\\\\">\\\\n    <AuthorizationDetails>\\\\n        <AmazonAuthorizationId>S02-8084966-1007363-A095465<\\\\/AmazonAuthorizationId>\\\\n        <AuthorizationReferenceId>S02-8084966-10073635a031808d6004<\\\\/AuthorizationReferenceId>\\\\n        <AuthorizationAmount>\\\\n            <Amount>289.49<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/AuthorizationAmount>\\\\n        <CapturedAmount>\\\\n            <Amount>0.0<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/CapturedAmount>\\\\n        <AuthorizationFee>\\\\n            <Amount>0.0<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/AuthorizationFee>\\\\n        <IdList/>\\\\n        <CreationTimestamp>2017-11-08T14:43:21.210Z<\\\\/CreationTimestamp>\\\\n        <ExpirationTimestamp>2017-12-08T14:43:21.210Z<\\\\/ExpirationTimestamp>\\\\n        <AuthorizationStatus>\\\\n            <State>Declined<\\\\/State>\\\\n            <LastUpdateTimestamp>2017-11-08T14:43:51.868Z<\\\\/LastUpdateTimestamp>\\\\n            <ReasonCode>InvalidPaymentMethod<\\\\/ReasonCode>\\\\n        <\\\\/AuthorizationStatus>\\\\n        <SoftDecline>false<\\\\/SoftDecline>\\\\n        <OrderItemCategories/>\\\\n        <CaptureNow>false<\\\\/CaptureNow>\\\\n        <SoftDescriptor/>\\\\n    <\\\\/AuthorizationDetails>\\\\n<\\\\/AuthorizationNotification>\\"}",
  "Timestamp" : "2017-11-08T14:43:52.590Z",
  "SignatureVersion" : "1",
  "Signature" : "VHNu7UPfEWcahT97HvNURXuJv0sSn4anXJpBf8Uzr3OxfcxR0lRdsy3wunn1D0kQbVCr5N9vEp9g+UUekm7pxXXd7QzWh6aYzM9cStf0vN+Pf0uBtrv9XGPM+L9+4n10XAKva/T0lPmZahsTk9MhDmiQ3e9HBjWExdOOwSm/ceHFNIxEjQgmXlFch9sCyhyvvdb4iElgaz87hzuERSNXfieGILIWuuax0l9jWlr1twpUMxCQTaCCwjRZhrlGsYMcJ3p5FCQjxkXIB4tqRh1WNn7T9GbxY+laRYyfK/5nXEE9ZTymtUb2SjhsgE0YzLUs4BGnKfS/8rzgxI2QC6fQrA==",
  "SigningCertURL" : "https://sns.eu-west-1.amazonaws.com/SimpleNotificationService-433026a4050d206028891664da859041.pem",
  "UnsubscribeURL" : "https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128"
}";', []),
            ],
            [
                $header,
                unserialize('s:2778:"{
  "Type" : "Notification",
  "MessageId" : "2f2de76f-716b-5318-9f97-b512524c93c4",
  "TopicArn" : "arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6",
  "Message" : "{\\"ReleaseEnvironment\\":\\"Sandbox\\",\\"MarketplaceID\\":\\"136311\\",\\"Version\\":\\"2013-01-01\\",\\"NotificationType\\":\\"PaymentAuthorize\\",\\"SellerId\\":\\"A36VZZYZOVN3S6\\",\\"NotificationReferenceId\\":\\"99c8f9a4-9547-490c-a5b6-701b5aa63ad0\\",\\"Timestamp\\":\\"2017-11-08T15:21:11.541Z\\",\\"NotificationData\\":\\"<' . '?xml version=\\\\\\"1.0\\\\\\" encoding=\\\\\\"UTF-8\\\\\\"?' . '><AuthorizationNotification xmlns=\\\\\\"https://mws.amazonservices.com/ipn/OffAmazonPayments/2013-01-01\\\\\\">\\\\n    <AuthorizationDetails>\\\\n        <AmazonAuthorizationId>S02-9613398-6723668-A070512<\\\\/AmazonAuthorizationId>\\\\n        <AuthorizationReferenceId>S02-9613398-67236685a032084a0885<\\\\/AuthorizationReferenceId>\\\\n        <AuthorizationAmount>\\\\n            <Amount>272.62<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/AuthorizationAmount>\\\\n        <CapturedAmount>\\\\n            <Amount>272.62<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/CapturedAmount>\\\\n        <AuthorizationFee>\\\\n            <Amount>0.0<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/AuthorizationFee>\\\\n        <IdList>\\\\n            <Id>S02-9613398-6723668-C070512<\\\\/Id>\\\\n        <\\\\/IdList>\\\\n        <CreationTimestamp>2017-11-08T15:19:40.451Z<\\\\/CreationTimestamp>\\\\n        <ExpirationTimestamp>2017-12-08T15:19:40.451Z<\\\\/ExpirationTimestamp>\\\\n        <AuthorizationStatus>\\\\n            <State>Closed<\\\\/State>\\\\n            <LastUpdateTimestamp>2017-11-08T15:21:10.894Z<\\\\/LastUpdateTimestamp>\\\\n            <ReasonCode>MaxCapturesProcessed<\\\\/ReasonCode>\\\\n        <\\\\/AuthorizationStatus>\\\\n        <SoftDecline>false<\\\\/SoftDecline>\\\\n        <OrderItemCategories/>\\\\n        <CaptureNow>false<\\\\/CaptureNow>\\\\n        <SoftDescriptor/>\\\\n    <\\\\/AuthorizationDetails>\\\\n<\\\\/AuthorizationNotification>\\"}",
  "Timestamp" : "2017-11-08T15:21:11.556Z",
  "SignatureVersion" : "1",
  "Signature" : "DtsDPQksXxryDi0ZR/NFSngkzgaPpMQix+6uDdVOOnNxSKuzovXim0k7bAAcULyC/Nsx8jZqSW+0Zm8NWlEmB76bNPP1VljjBAHskhl5u5+bYDLr6f+tmvhgNsqwJvgvYhjWRRuVAsZxXmRMSM/ypVLSRO19U7IRgzsvluyMVOr56vXytjYBBWauPPGtGXVKubKPbrt5XkN2gi8VcCPcpnPTmaqi1tC5nJzHGzqm0LPVqSA2xWhuHqifcSItmUMTxFFxEdTjzF8lVLERmzh4EcOrp0B7dtZ69QRH2x/uBzdDqRCFqViDphWgvhFzzJiyj5JuRx1YaqHOZthxCT1tJw==",
  "SigningCertURL" : "https://sns.eu-west-1.amazonaws.com/SimpleNotificationService-433026a4050d206028891664da859041.pem",
  "UnsubscribeURL" : "https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128"
}";', []),
            ],
            [
                $header,
                unserialize('s:2384:"{
  "Type" : "Notification",
  "MessageId" : "c51984dc-9212-5116-a934-d6a1b2b3861e",
  "TopicArn" : "arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6",
  "Message" : "{\\"ReleaseEnvironment\\":\\"Sandbox\\",\\"MarketplaceID\\":\\"136311\\",\\"Version\\":\\"2013-01-01\\",\\"NotificationType\\":\\"PaymentCapture\\",\\"SellerId\\":\\"A36VZZYZOVN3S6\\",\\"NotificationReferenceId\\":\\"48fb9412-7843-4267-95ec-b9f17d81ab63\\",\\"Timestamp\\":\\"2017-11-08T15:10:25.418Z\\",\\"NotificationData\\":\\"<' . '?xml version=\\\\\\"1.0\\\\\\" encoding=\\\\\\"UTF-8\\\\\\"?' . '><CaptureNotification xmlns=\\\\\\"https://mws.amazonservices.com/ipn/OffAmazonPayments/2013-01-01\\\\\\">\\\\n    <CaptureDetails>\\\\n        <AmazonCaptureId>S02-5637700-4789582-C019942<\\\\/AmazonCaptureId>\\\\n        <CaptureReferenceId>S02-5637700-47895825a031e5fb0d9f<\\\\/CaptureReferenceId>\\\\n        <CaptureAmount>\\\\n            <Amount>267.73<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/CaptureAmount>\\\\n        <RefundedAmount>\\\\n            <Amount>0.0<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/RefundedAmount>\\\\n        <CaptureFee>\\\\n            <Amount>0.0<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/CaptureFee>\\\\n        <IdList/>\\\\n        <CreationTimestamp>2017-11-08T15:10:24.146Z<\\\\/CreationTimestamp>\\\\n        <CaptureStatus>\\\\n            <State>Completed<\\\\/State>\\\\n            <LastUpdateTimestamp>2017-11-08T15:10:24.146Z<\\\\/LastUpdateTimestamp>\\\\n        <\\\\/CaptureStatus>\\\\n        <SoftDescriptor>AMZ*spryker<\\\\/SoftDescriptor>\\\\n    <\\\\/CaptureDetails>\\\\n<\\\\/CaptureNotification>\\"}",
  "Timestamp" : "2017-11-08T15:10:25.436Z",
  "SignatureVersion" : "1",
  "Signature" : "g/1fo3ktGuHnigUjvwKV0z/CUu0oiY+NRukcBM+6N0h49JTE59OWsIewPUL/JWuI4uN47+Ne0xrAdfVgl3DTPN7FyoryVV5fAX7VlM1WH05WPIbOFtFJeNiYSP3WEhWnYpRYRMc3Qlfndr5Gpxx9AA+USPbKa3+2XHcK1utkdRQx/V2K7bf1Mj4fIPLtv7UOtl4c0K1PT6S90ZUdGeHdFeuuvTrtg7Mp09OHX7VBylEZAveuyMetWSqJN2feoQBb1ASud7s/jAystiMlyx4k1bw1taLwdUOmaQE36OF9ATHgvTklYoGC+3A6wcrNzxktM1KS1HKePmRMWU+vNhDCUQ==",
  "SigningCertURL" : "https://sns.eu-west-1.amazonaws.com/SimpleNotificationService-433026a4050d206028891664da859041.pem",
  "UnsubscribeURL" : "https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128"
}";', []),
            ],
            [
                $header,
                unserialize('s:2637:"{
  "Type" : "Notification",
  "MessageId" : "8bf94ac8-e137-5d6e-8d86-18b5c10f292a",
  "TopicArn" : "arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6",
  "Message" : "{\\"ReleaseEnvironment\\":\\"Sandbox\\",\\"MarketplaceID\\":\\"136311\\",\\"Version\\":\\"2013-01-01\\",\\"NotificationType\\":\\"PaymentAuthorize\\",\\"SellerId\\":\\"A36VZZYZOVN3S6\\",\\"NotificationReferenceId\\":\\"69bdfcb9-b987-4858-98fd-efa87fa30c5b\\",\\"Timestamp\\":\\"2017-11-08T15:08:40.358Z\\",\\"NotificationData\\":\\"<' . '?xml version=\\\\\\"1.0\\\\\\" encoding=\\\\\\"UTF-8\\\\\\"?' . '><AuthorizationNotification xmlns=\\\\\\"https://mws.amazonservices.com/ipn/OffAmazonPayments/2013-01-01\\\\\\">\\\\n    <AuthorizationDetails>\\\\n        <AmazonAuthorizationId>S02-5637700-4789582-A019942<\\\\/AmazonAuthorizationId>\\\\n        <AuthorizationReferenceId>S02-5637700-47895825a031dd8d5687<\\\\/AuthorizationReferenceId>\\\\n        <AuthorizationAmount>\\\\n            <Amount>781.31<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/AuthorizationAmount>\\\\n        <CapturedAmount>\\\\n            <Amount>0.0<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/CapturedAmount>\\\\n        <AuthorizationFee>\\\\n            <Amount>0.0<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/AuthorizationFee>\\\\n        <IdList/>\\\\n        <CreationTimestamp>2017-11-08T15:08:09.149Z<\\\\/CreationTimestamp>\\\\n        <ExpirationTimestamp>2017-12-08T15:08:09.149Z<\\\\/ExpirationTimestamp>\\\\n        <AuthorizationStatus>\\\\n            <State>Open<\\\\/State>\\\\n            <LastUpdateTimestamp>2017-11-08T15:08:39.628Z<\\\\/LastUpdateTimestamp>\\\\n        <\\\\/AuthorizationStatus>\\\\n        <SoftDecline>false<\\\\/SoftDecline>\\\\n        <OrderItemCategories/>\\\\n        <CaptureNow>false<\\\\/CaptureNow>\\\\n        <SoftDescriptor/>\\\\n    <\\\\/AuthorizationDetails>\\\\n<\\\\/AuthorizationNotification>\\"}",
  "Timestamp" : "2017-11-08T15:08:40.384Z",
  "SignatureVersion" : "1",
  "Signature" : "EhqHq9EiQ2a/S7XhZ/w8QZJskoUvos75DLsPFqva1ncZoHfV7nfo76DDWTC848v+ldwUJK1nwxgoCHY4OlG5MQKGSGMznLfxqHNpTEKSKA4B4OvEEwUWxrMINnFVsqUl2uisDr26KHM/mBMv8fcuOOsMw3M7sz0oKtdUC8Z51dik0t09T1Tr5Q4gya+lLSEIfrujXPndiMq5Jk4246DStpA2/6sdLwIa//oX8WiWg54cH9QZNdosFYFbeEKOa8/+FYxSg6HbXm7FXQ0s5kYP/YlcTitvBCihqnWNCUhJOai7endqHszdJkzuzfLD5om3ZQA8hjVBGNc5nu7aLgOtBw==",
  "SigningCertURL" : "https://sns.eu-west-1.amazonaws.com/SimpleNotificationService-433026a4050d206028891664da859041.pem",
  "UnsubscribeURL" : "https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128"
}";', []),
            ],
            [
                $header,
                unserialize('s:2365:"{
  "Type" : "Notification",
  "MessageId" : "6ac19550-a064-50b3-9d77-c72dbe9e9bea",
  "TopicArn" : "arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6",
  "Message" : "{\\"ReleaseEnvironment\\":\\"Sandbox\\",\\"MarketplaceID\\":\\"136311\\",\\"Version\\":\\"2013-01-01\\",\\"NotificationType\\":\\"OrderReferenceNotification\\",\\"SellerId\\":\\"A36VZZYZOVN3S6\\",\\"NotificationReferenceId\\":\\"6205e833-c453-4e9c-b946-343fae90cab0\\",\\"Timestamp\\":\\"2017-11-08T15:03:22.007Z\\",\\"NotificationData\\":\\"<' . '?xml version=\\\\\\"1.0\\\\\\" encoding=\\\\\\"UTF-8\\\\\\"?' . '><OrderReferenceNotification xmlns=\\\\\\"https://mws.amazonservices.com/ipn/OffAmazonPayments/2013-01-01\\\\\\">\\\\n    <OrderReference>\\\\n        <AmazonOrderReferenceId>S02-9613398-6723668<\\\\/AmazonOrderReferenceId>\\\\n        <OrderTotal>\\\\n            <Amount>272.62<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/OrderTotal>\\\\n        <SellerOrderAttributes>\\\\n            <SellerId>A36VZZYZOVN3S6<\\\\/SellerId>\\\\n            <SellerOrderId>S02-9613398-67236685a031c994d40a<\\\\/SellerOrderId>\\\\n            <OrderItemCategories/>\\\\n        <\\\\/SellerOrderAttributes>\\\\n        <OrderReferenceStatus>\\\\n            <State>Suspended<\\\\/State>\\\\n            <LastUpdateTimestamp>2017-11-08T15:03:21.476Z<\\\\/LastUpdateTimestamp>\\\\n            <ReasonCode>InvalidPaymentMethod<\\\\/ReasonCode>\\\\n        <\\\\/OrderReferenceStatus>\\\\n        <CreationTimestamp>2017-11-08T15:02:33.819Z<\\\\/CreationTimestamp>\\\\n        <ExpirationTimestamp>2018-05-07T15:02:33.819Z<\\\\/ExpirationTimestamp>\\\\n    <\\\\/OrderReference>\\\\n<\\\\/OrderReferenceNotification>\\"}",
  "Timestamp" : "2017-11-08T15:03:22.020Z",
  "SignatureVersion" : "1",
  "Signature" : "Fh4K1741U5Qrq38DnKrWBdxNN3Pne/EhC1FMSpIvue1Nb7dftDKtxTne7fP0QIjshzl2wU3rb8yp9H5neJ7FdN7HoRwQY6PQJosB8xD6IrrPh8Mu+RJItJ6AXb9sYv+cfRdchwUJMBiDilq0gOisUuSqScarE1WlKol39Pxhfh7sHuZHkirQozGlMr6C5mfOoIxRy1bUlOxGXAGMvk+WKwSwf3yrIEkEq4y/ffpOTwzql6+ZHDFlFbg1QGGH4Qb97oklaDjTspoKjcz8AVItJgLscuE0jLmuVKTg68dIPlctanDqt4z3e+i+v+c19fuIJt0hxSsoEnB1v9pk3TDMyQ==",
  "SigningCertURL" : "https://sns.eu-west-1.amazonaws.com/SimpleNotificationService-433026a4050d206028891664da859041.pem",
  "UnsubscribeURL" : "https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128"
}";', []),
            ],
            [
                $header,
                unserialize('s:2298:"{
  "Type" : "Notification",
  "MessageId" : "6636729a-8529-53d7-b303-20e799ebcdf0",
  "TopicArn" : "arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6",
  "Message" : "{\\"ReleaseEnvironment\\":\\"Sandbox\\",\\"MarketplaceID\\":\\"136311\\",\\"Version\\":\\"2013-01-01\\",\\"NotificationType\\":\\"OrderReferenceNotification\\",\\"SellerId\\":\\"A36VZZYZOVN3S6\\",\\"NotificationReferenceId\\":\\"da1dea60-77f2-41be-8350-af6b8bd21f81\\",\\"Timestamp\\":\\"2017-11-08T14:53:54.922Z\\",\\"NotificationData\\":\\"<' . '?xml version=\\\\\\"1.0\\\\\\" encoding=\\\\\\"UTF-8\\\\\\"?' . '><OrderReferenceNotification xmlns=\\\\\\"https://mws.amazonservices.com/ipn/OffAmazonPayments/2013-01-01\\\\\\">\\\\n    <OrderReference>\\\\n        <AmazonOrderReferenceId>S02-8084966-1007363<\\\\/AmazonOrderReferenceId>\\\\n        <OrderTotal>\\\\n            <Amount>289.49<\\\\/Amount>\\\\n            <CurrencyCode>EUR<\\\\/CurrencyCode>\\\\n        <\\\\/OrderTotal>\\\\n        <SellerOrderAttributes>\\\\n            <SellerId>A36VZZYZOVN3S6<\\\\/SellerId>\\\\n            <SellerOrderId>S02-8084966-10073635a031807a5f10<\\\\/SellerOrderId>\\\\n            <OrderItemCategories/>\\\\n        <\\\\/SellerOrderAttributes>\\\\n        <OrderReferenceStatus>\\\\n            <State>Open<\\\\/State>\\\\n            <LastUpdateTimestamp>2017-11-08T14:48:54.237Z<\\\\/LastUpdateTimestamp>\\\\n        <\\\\/OrderReferenceStatus>\\\\n        <CreationTimestamp>2017-11-08T14:38:49.516Z<\\\\/CreationTimestamp>\\\\n        <ExpirationTimestamp>2018-05-07T14:38:49.516Z<\\\\/ExpirationTimestamp>\\\\n    <\\\\/OrderReference>\\\\n<\\\\/OrderReferenceNotification>\\"}",
  "Timestamp" : "2017-11-08T14:53:54.935Z",
  "SignatureVersion" : "1",
  "Signature" : "T2MrF+5fQny3T72d1jsDWtkInavWXqesOCBslZ64YU4fJQ0iWyp4s9b4BtiqwOPadIOQjgUaSWw+vet8NggAM7/f4/Dz9e2PRlSfZJ2kndPW+44/vtfVFjwZfYsAHSOqmLVFKRv4LNHYDck0OvnsfdK80Har9DHkDFL2fvqYQ32rFMAS9nkacvP8uVGBzK8JTuljYuNvA7/yjWiJSapy3S28jqatJhKuMpi9WWoOXMs27x9c46bpKweViokyWtlNYMBm/b8WfrOPP65sbGKCsvvShfmp1wN3lPV0aPWISUsJAtaiDwrUgVDdeoMrD+47v3OElvv7qxP7vEfKlxzmOg==",
  "SigningCertURL" : "https://sns.eu-west-1.amazonaws.com/SimpleNotificationService-433026a4050d206028891664da859041.pem",
  "UnsubscribeURL" : "https://sns.eu-west-1.amazonaws.com/?Action=Unsubscribe&SubscriptionArn=arn:aws:sns:eu-west-1:291180941288:A1G8446IYHA4MRA36VZZYZOVN3S6:e580adf7-36cf-4bae-a3bb-a49a51618128"
}";', []),
            ],
        ];
    }
}
