<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface;

class IpnPaymentCaptureRequestConverter extends IpnPaymentAbstractRequestConverter
{
    const CAPTURE_DETAILS = 'CaptureDetails';
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface $captureDetailsConverter
     */
    protected $captureDetailsConverter;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface $captureDetailsConverter
     */
    public function __construct(ArrayConverterInterface $captureDetailsConverter)
    {
        $this->captureDetailsConverter = $captureDetailsConverter;
    }

    /**
     * @param array $request
     *
     * @return \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer
     */
    public function convert(array $request)
    {
        $ipnPaymentRequestTransfer = new AmazonpayIpnPaymentRequestTransfer();
        $ipnPaymentRequestTransfer->setMessage($this->extractMessage($request));
        $ipnPaymentRequestTransfer->setCaptureDetails(
            $this->captureDetailsConverter->convert($request[static::CAPTURE_DETAILS])
        );

        return $ipnPaymentRequestTransfer;
    }
}
