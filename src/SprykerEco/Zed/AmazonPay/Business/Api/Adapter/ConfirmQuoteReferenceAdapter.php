<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

use AmazonPay\ClientInterface;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfig;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyInterface;

class ConfirmQuoteReferenceAdapter extends AbstractAdapter
{
    /**
     * @var \SprykerEco\Shared\AmazonPay\AmazonPayConfig
     */
    protected $config;

    /**
     * @param \AmazonPay\ClientInterface $client
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface $converter
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyInterface $moneyFacade
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfig $config
     */
    public function __construct(
        ClientInterface $client,
        ResponseParserConverterInterface $converter,
        AmazonPayToMoneyInterface $moneyFacade,
        AmazonPayConfig $config
    ) {
        $this->config = $config;

        parent::__construct($client, $converter, $moneyFacade);
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->confirmOrderReference([
            AbstractAdapter::AMAZON_ORDER_REFERENCE_ID => $amazonpayCallTransfer->getAmazonpayPayment()->getOrderReferenceId(),
            AbstractAdapter::AMAZON_AMOUNT => $this->getAmount($amazonpayCallTransfer),
            AbstractAdapter::AMAZON_SUCCESS_URL => $this->config->getSuccessPaymentUrl(),
            AbstractAdapter::AMAZON_FAILURE_URL => $this->config->getFailurePaymentUrl(),
        ]);

        return $this->converter->convert($result);
    }
}
