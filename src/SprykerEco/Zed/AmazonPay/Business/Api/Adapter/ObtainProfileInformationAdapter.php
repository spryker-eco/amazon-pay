<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

use AmazonPay\ClientInterface;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface;

class ObtainProfileInformationAdapter implements CallAdapterInterface
{
    /**
     * @var \AmazonPay\ClientInterface
     */
    protected $client;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface
     */
    protected $converter;

    /**
     * @param \AmazonPay\ClientInterface $client
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ArrayConverterInterface $converter
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
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->getUserInfo($amazonpayCallTransfer->getAmazonpayPayment()->getAddressConsentToken());

        $responseTransfer = $this->converter->convert($result);
        $responseTransfer->getCustomer()->setIsGuest(true);

        return $responseTransfer;
    }
}
