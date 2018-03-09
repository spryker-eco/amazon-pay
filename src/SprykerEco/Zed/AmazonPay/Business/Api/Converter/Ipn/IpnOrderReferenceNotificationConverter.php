<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;

class IpnOrderReferenceNotificationConverter extends IpnPaymentAbstractRequestConverter
{
    const ORDER_REFERENCE = 'OrderReference';
    const AMAZON_ORDER_REFERENCE_ID = 'AmazonOrderReferenceId';
    const ORDER_REFERENCE_STATUS = 'OrderReferenceStatus';

    /**
     * @param array $request
     * @param string $body
     *
     * @return \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer
     */
    public function convert(array $request, $body)
    {
        $ipnPaymentRequestTransfer = new AmazonpayIpnPaymentRequestTransfer();
        $ipnPaymentRequestTransfer->setMessage($this->extractMessage($request));
        $ipnPaymentRequestTransfer->setOrderReferenceStatus(
            $this->convertStatusToTransfer($request[static::ORDER_REFERENCE][static::ORDER_REFERENCE_STATUS])
        );
        $ipnPaymentRequestTransfer->setAmazonOrderReferenceId(
            $request[static::ORDER_REFERENCE][static::AMAZON_ORDER_REFERENCE_ID]
        );
        $ipnPaymentRequestTransfer->setRawMessage($body);

        return $ipnPaymentRequestTransfer;
    }
}
