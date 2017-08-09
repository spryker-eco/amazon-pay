<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use PayWithAmazon\Client;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface;

class ObtainProfileInformationAdapter implements CallAdapterInterface
{

    /**
     * @var \PayWithAmazon\Client
     */
    protected $client;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Converter\AbstractArrayConverter
     */
    protected $converter;

    /**
     * @param \PayWithAmazon\Client $client
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ArrayConverterInterface $converter
     */
    public function __construct(
        Client $client,
        ArrayConverterInterface $converter
    ) {
        $this->client = $client;
        $this->converter = $converter;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->getUserInfo($amazonpayCallTransfer->getAmazonpayPayment()->getAddressConsentToken());

        $customer = $this->converter->convert($result);

        $customer->setIsGuest(true);

        return $customer;
    }

}
