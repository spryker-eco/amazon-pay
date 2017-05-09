<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use PayWithAmazon\ClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface;

abstract class AbstractAdapter
{

    const AMAZON_AUTHORIZATION_ID = 'amazon_authorization_id';
    const AMAZON_ORDER_REFERENCE_ID = 'amazon_order_reference_id';
    const AMAZON_ADDRESS_CONSENT_TOKEN = 'address_consent_token';
    const AMAZON_AMOUNT = 'amount';
    const AMAZON_CAPTURE_ID = 'amazon_capture_id';
    const AMAZON_REFUND_ID = 'amazon_refund_id';

    /**
     * @var \PayWithAmazon\Client
     */
    protected $client;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface
     */
    protected $converter;

    /**
     * @param \PayWithAmazon\ClientInterface $client
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface $converter
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface $moneyFacade
     */
    public function __construct(
        ClientInterface $client,
        ResponseParserConverterInterface $converter,
        AmazonpayToMoneyInterface $moneyFacade
    ) {
        $this->client = $client;
        $this->converter = $converter;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @method
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $abstractTransfer
     *
     * @return float
     */
    protected function getAmount(AbstractTransfer $abstractTransfer)
    {
        return $this->moneyFacade->convertIntegerToDecimal(
            $abstractTransfer->requireTotals()->getTotals()->getGrandTotal()
        );
    }

}
