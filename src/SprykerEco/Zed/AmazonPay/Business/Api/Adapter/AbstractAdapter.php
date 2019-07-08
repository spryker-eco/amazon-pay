<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use AmazonPay\ClientInterface;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyInterface;

abstract class AbstractAdapter implements CallAdapterInterface
{
    public const AMAZON_AUTHORIZATION_ID = 'amazon_authorization_id';
    public const AMAZON_ORDER_REFERENCE_ID = 'amazon_order_reference_id';
    public const AMAZON_ADDRESS_CONSENT_TOKEN = 'address_consent_token';
    public const AMAZON_AMOUNT = 'amount';
    public const AMAZON_CAPTURE_ID = 'amazon_capture_id';
    public const AMAZON_REFUND_ID = 'amazon_refund_id';
    public const AMAZON_SUCCESS_URL = 'success_url';
    public const AMAZON_FAILURE_URL = 'failure_url';

    /**
     * @var \AmazonPay\ClientInterface
     */
    protected $client;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface
     */
    protected $converter;

    /**
     * @param \AmazonPay\ClientInterface $client
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface $converter
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyInterface $moneyFacade
     */
    public function __construct(
        ClientInterface $client,
        ResponseParserConverterInterface $converter,
        AmazonPayToMoneyInterface $moneyFacade
    ) {
        $this->client = $client;
        $this->converter = $converter;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return float
     */
    protected function getAmount(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return $this->moneyFacade->convertIntegerToDecimal(
            $amazonpayCallTransfer->getRequestedAmount()
        );
    }
}
