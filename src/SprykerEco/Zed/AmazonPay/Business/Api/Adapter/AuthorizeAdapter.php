<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Adapter;

use AmazonPay\ClientInterface;
use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\AmazonpayPaymentTransfer;
use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;
use SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyInterface;

class AuthorizeAdapter extends AbstractAdapter
{
    public const AUTHORIZATION_AMOUNT = 'authorization_amount';
    public const AUTHORIZATION_REFERENCE_ID = 'authorization_reference_id';
    public const TRANSACTION_TIMEOUT = 'transaction_timeout';
    public const CAPTURE_NOW = 'capture_now';

    /**
     * @var bool
     */
    protected $captureNow;

    /**
     * @var int
     */
    protected $transactionTimeout;

    /**
     * @param \AmazonPay\ClientInterface $client
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Converter\ResponseParserConverterInterface $converter
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToMoneyInterface $moneyFacade
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     * @param bool|null $captureNow
     */
    public function __construct(
        ClientInterface $client,
        ResponseParserConverterInterface $converter,
        AmazonPayToMoneyInterface $moneyFacade,
        AmazonPayConfigInterface $config,
        $captureNow = null
    ) {
        parent::__construct($client, $converter, $moneyFacade);

        $this->captureNow = $this->getCaptureNow($config, $captureNow);
        $this->transactionTimeout = $config->getAuthTransactionTimeout();
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function call(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $result = $this->client->authorize(
            $this->getRequestArray($amazonpayCallTransfer->getAmazonpayPayment(), $this->getAmount($amazonpayCallTransfer))
        );

        return $this->converter->convert($result);
    }

    /**
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     * @param bool|null $captureNow
     *
     * @return bool
     */
    protected function getCaptureNow(AmazonPayConfigInterface $config, $captureNow = null)
    {
        return $captureNow ?? $config->getCaptureNow();
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     * @param float $amount
     *
     * @return array
     */
    protected function getRequestArray(AmazonpayPaymentTransfer $amazonpayPaymentTransfer, $amount)
    {
        return [
            static::AMAZON_ORDER_REFERENCE_ID => $amazonpayPaymentTransfer->getOrderReferenceId(),
            static::AUTHORIZATION_AMOUNT => $amount,
            static::AUTHORIZATION_REFERENCE_ID => $amazonpayPaymentTransfer
                    ->getAuthorizationDetails()
                    ->getAuthorizationReferenceId(),
            static::TRANSACTION_TIMEOUT => $this->transactionTimeout,
            static::CAPTURE_NOW => $this->captureNow,
        ];
    }
}
