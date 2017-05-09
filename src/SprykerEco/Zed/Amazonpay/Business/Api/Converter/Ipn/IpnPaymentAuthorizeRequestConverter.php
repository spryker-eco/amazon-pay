<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter\Ipn;

use Generated\Shared\Transfer\AmazonpayIpnPaymentAuthorizeRequestTransfer;
use Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface;

class IpnPaymentAuthorizeRequestConverter extends IpnPaymentAbstractRequestConverter
{

    /**
     * @var \Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $authDetailsConverter
     */
    protected $authDetailsConverter;

    /**
     * @param \Spryker\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $authDetailsConverter
     */
    public function __construct(ArrayConverterInterface $authDetailsConverter)
    {
        $this->authDetailsConverter = $authDetailsConverter;
    }

    /**
     * @param array $request
     *
     * @return \Generated\Shared\Transfer\AmazonpayIpnPaymentAuthorizeRequestTransfer
     */
    public function convert(array $request)
    {
        $ipnPaymentAuthorizeRequestTransfer = new AmazonpayIpnPaymentAuthorizeRequestTransfer();
        $ipnPaymentAuthorizeRequestTransfer->setMessage($this->extractMessage($request));

        $ipnPaymentAuthorizeRequestTransfer->setAuthorizationDetails(
            $this->authDetailsConverter->convert($request['AuthorizationDetails'])
        );

        return $ipnPaymentAuthorizeRequestTransfer;
    }

}
