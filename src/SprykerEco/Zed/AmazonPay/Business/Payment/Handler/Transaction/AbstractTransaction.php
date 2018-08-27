<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction;

use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;
use SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;

abstract class AbstractTransaction
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface
     */
    protected $transactionLogger;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Business\Api\Adapter\CallAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     */
    public function __construct(
        CallAdapterInterface $executionAdapter,
        AmazonPayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->config = $config;
        $this->transactionLogger = $transactionLogger;
    }
}
