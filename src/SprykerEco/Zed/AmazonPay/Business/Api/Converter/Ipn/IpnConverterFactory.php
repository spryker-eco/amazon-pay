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

class IpnConverterFactory implements IpnConverterFactoryInterface
{
    const NOTIFICATION_TYPE = 'NotificationType';

    /**
     * @return array
     */
    protected function getTypeToConverterMap()
    {
        return [
            AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE => function () {
                return $this->createIpnPaymentAuthorizeRequestConverter();
            },
            AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_CAPTURE => function () {
                return $this->createIpnPaymentCaptureRequestConverter();
            },
            AmazonPayConfig::IPN_REQUEST_TYPE_PAYMENT_REFUND => function () {
                return $this->createIpnPaymentRefundRequestConverter();
            },
            AmazonPayConfig::IPN_REQUEST_TYPE_ORDER_REFERENCE_NOTIFICATION => function () {
                return $this->createIpnOrderReferenceNotificationConverter();
            },
        ];
    }

    /**
     * @param array $request
     *
     * @return \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    public function createIpnRequestConverter(array $request)
    {
        $map = $this->getTypeToConverterMap();

        return $map[$request[static::NOTIFICATION_TYPE]]();
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
