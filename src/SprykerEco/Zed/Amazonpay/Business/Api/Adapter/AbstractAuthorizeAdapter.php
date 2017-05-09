<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use PayWithAmazon\Client;
use PayWithAmazon\ClientInterface;
use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface;

abstract class AbstractAuthorizeAdapter extends AbstractAdapter
{

    const AUTHORIZATION_AMOUNT = 'authorization_amount';
    const AUTHORIZATION_REFERENCE_ID = 'authorization_reference_id';
    const TRANSACTION_TIMEOUT = 'transaction_timeout';
    const CAPTURE_NOW = 'capture_now';

    /**
     * @var bool
     */
    protected $captureNow;

    /**
     * @var int
     */
    protected $transactionTimeout;

    /**
     * @param \PayWithAmazon\ClientInterface $client
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Converter\ResponseParserConverterInterface $converter
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToMoneyInterface $moneyFacade
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param bool|null $captureNow
     */
    public function __construct(
        ClientInterface $client,
        ResponseParserConverterInterface $converter,
        AmazonpayToMoneyInterface $moneyFacade,
        AmazonpayConfigInterface $config,
        $captureNow = null
    ) {
        parent::__construct($client, $converter, $moneyFacade);

        $this->setCaptureNow($config, $captureNow);
        $this->transactionTimeout = $config->getAuthTransactionTimeout();
    }

    /**
     * @param AmazonpayConfigInterface $config
     * @param string|null $captureNow
     *
     * @return void
     */
    protected function setCaptureNow(AmazonpayConfigInterface $config, $captureNow = null)
    {
        if ($captureNow === null) {
            $this->captureNow = $config->getCaptureNow();
        } else {
            $this->captureNow = (bool)$captureNow;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     * @param float $amount
     *
     * @return array
     */
    protected function buildRequestArray(AmazonpayPaymentTransfer $amazonpayPaymentTransfer, $amount)
    {
        return [
            static::AMAZON_ORDER_REFERENCE_ID => $amazonpayPaymentTransfer->getOrderReferenceId(),
            static::AUTHORIZATION_AMOUNT => $amount,
            static::AUTHORIZATION_REFERENCE_ID =>
                $amazonpayPaymentTransfer
                    ->getAuthorizationDetails()
                    ->getAuthorizationReferenceId(),
            static::TRANSACTION_TIMEOUT => $this->transactionTimeout,
            static::CAPTURE_NOW => $this->captureNow,
        ];
    }

}
