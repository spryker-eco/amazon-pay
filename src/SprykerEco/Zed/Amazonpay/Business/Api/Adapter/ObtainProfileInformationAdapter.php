<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use PayWithAmazon\ClientInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface;

class ObtainProfileInformationAdapter implements CallAdapterInterface
{

    /**
     * @var \PayWithAmazon\ClientInterface
     */
    protected $client;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Converter\AbstractArrayConverter
     */
    protected $converter;

    /**
     * @param \PayWithAmazon\ClientInterface $client
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $converter
     */
    public function __construct(
        ClientInterface $client,
        ArrayConverterInterface $converter
    ) {
        $this->client = $client;
        $this->converter = $converter;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->getUserInfo($amazonpayCallTransfer->getAmazonpayPayment()->getAddressConsentToken());

        /** @var \Generated\Shared\Transfer\CustomerTransfer $customer */
        $customer = $this->converter->convert($result);
        $customer->setIsGuest(true);

        return $customer;
    }

}
