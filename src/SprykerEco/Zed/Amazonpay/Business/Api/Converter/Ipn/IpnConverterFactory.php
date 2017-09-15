<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter\Ipn;

use SprykerEco\Shared\Amazonpay\AmazonpayConstants;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\Details\AuthorizationDetailsConverter;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\Details\CaptureDetailsConverter;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\Details\RefundDetailsConverter;
use SprykerEco\Zed\Amazonpay\Business\Exception\InvalidIpnCallException;

class IpnConverterFactory implements IpnConverterFactoryInterface
{

    /**
     * @param array $request
     *
     * @throws \SprykerEco\Zed\Amazonpay\Business\Exception\InvalidIpnCallException
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface
     */
    public function createIpnRequestConverter(array $request)
    {
        switch ($request['NotificationType'])
        {
            case AmazonpayConstants::IPN_REQUEST_TYPE_PAYMENT_AUTHORIZE:
                return $this->createIpnPaymentAuthorizeRequestConverter();

            case AmazonpayConstants::IPN_REQUEST_TYPE_PAYMENT_CAPTURE:
                return $this->createIpnPaymentCaptureRequestConverter();

            case AmazonpayConstants::IPN_REQUEST_TYPE_PAYMENT_REFUND:
                return $this->createIpnPaymentRefundRequestConverter();

            case AmazonpayConstants::IPN_REQUEST_TYPE_ORDER_REFERENCE_NOTIFICATION:
                return $this->createIpnOrderReferenceNotificationConverter();
        }

        throw new InvalidIpnCallException('Unknown notification type: ' . $request['NotificationType']);
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Converter\Ipn\IpnOrderReferenceNotificationConverter
     */
    protected function createIpnOrderReferenceNotificationConverter()
    {
        return new IpnOrderReferenceNotificationConverter();
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Converter\Ipn\IpnPaymentAuthorizeRequestConverter
     */
    protected function createIpnPaymentAuthorizeRequestConverter()
    {
        return new IpnPaymentAuthorizeRequestConverter(
            $this->createAuthorizationDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Converter\Ipn\IpnPaymentCaptureRequestConverter
     */
    protected function createIpnPaymentCaptureRequestConverter()
    {
        return new IpnPaymentCaptureRequestConverter(
            $this->createCaptureDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Converter\Ipn\IpnPaymentRefundRequestConverter
     */
    protected function createIpnPaymentRefundRequestConverter()
    {
        return new IpnPaymentRefundRequestConverter(
            $this->createRefundDetailsConverter()
        );
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Converter\Details\AuthorizationDetailsConverter
     */
    protected function createAuthorizationDetailsConverter()
    {
        return new AuthorizationDetailsConverter();
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Converter\Details\CaptureDetailsConverter
     */
    protected function createCaptureDetailsConverter()
    {
        return new CaptureDetailsConverter();
    }

    /**
     * @return \SprykerEco\Zed\Amazonpay\Business\Api\Converter\Details\RefundDetailsConverter
     */
    protected function createRefundDetailsConverter()
    {
        return new RefundDetailsConverter();
    }

}
