<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter\Ipn;

use Generated\Shared\Transfer\AmazonpayIpnRequestMessageTransfer;
use Spryker\Zed\Amazonpay\Business\Api\Converter\AbstractArrayConverter;

abstract class IpnPaymentAbstractRequestConverter extends AbstractArrayConverter
{

    /**
     * @param array $request
     *
     * @return \Generated\Shared\Transfer\AmazonpayIpnRequestMessageTransfer
     */
    protected function extractMessage(array $request)
    {
        $ipnRequestMessageTransfer = new AmazonpayIpnRequestMessageTransfer();
        $ipnRequestMessageTransfer->setMessageId($request['MessageId']);
        $ipnRequestMessageTransfer->setNotificationReferenceId($request['NotificationReferenceId']);
        $ipnRequestMessageTransfer->setNotificationType($request['NotificationType']);
        $ipnRequestMessageTransfer->setReleaseEnvironment($request['ReleaseEnvironment']);
        $ipnRequestMessageTransfer->setSellerId($request['SellerId']);
        $ipnRequestMessageTransfer->setTopicArn($request['TopicArn']);
        $ipnRequestMessageTransfer->setType($request['Type']);

        return $ipnRequestMessageTransfer;
    }

}
