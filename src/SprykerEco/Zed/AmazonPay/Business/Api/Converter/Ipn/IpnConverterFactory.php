<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter\Ipn;

use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\Details\AuthorizationDetailsConverter;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\Details\CaptureDetailsConverter;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\Details\RefundDetailsConverter;
use SprykerEco\Zed\AmazonPay\Business\Exception\InvalidIpnCallException;

class IpnConverterFactory implements IpnConverterFactoryInterface
{
    const NOTIFICATION_TYPE = 'NotificationType';

    /**
     * @param array $request
     *
     * @throws \SprykerEco\Zed\AmazonPay\Business\Exception\InvalidIpnCallException
     *
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    public function createIpnRequestConverter(array $request)
    {
        switch ($request[static::NOTIFICATION_TYPE]) {
            case AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE:
                return $this->createIpnPaymentAuthorizeRequestConverter();

            case AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_CAPTURE:
                return $this->createIpnPaymentCaptureRequestConverter();

            case AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_REFUND:
                return $this->createIpnPaymentRefundRequestConverter();

            case AmazonPayConfig::IPN_REQUEST_TYPE_ORDER_REFERENCE_NOTIFICATION:
                return $this->createIpnOrderReferenceNotificationConverter();
        }

        throw new InvalidIpnCallException('Unknown notification type: ' . $request[static::NOTIFICATION_TYPE]);
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createIpnOrderReferenceNotificationConverter()
    {
        return new IpnOrderReferenceNotificationConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createIpnPaymentAuthorizeRequestConverter()
    {
        return new IpnPaymentAuthorizeRequestConverter(
            $this->createAuthorizationDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createIpnPaymentCaptureRequestConverter()
    {
        return new IpnPaymentCaptureRequestConverter(
            $this->createCaptureDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createIpnPaymentRefundRequestConverter()
    {
        return new IpnPaymentRefundRequestConverter(
            $this->createRefundDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createAuthorizationDetailsConverter()
    {
        return new AuthorizationDetailsConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createCaptureDetailsConverter()
    {
        return new CaptureDetailsConverter();
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    protected function createRefundDetailsConverter()
    {
        return new RefundDetailsConverter();
    }
}
