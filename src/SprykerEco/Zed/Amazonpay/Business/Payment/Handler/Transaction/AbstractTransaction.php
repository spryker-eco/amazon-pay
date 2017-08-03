<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;

abstract class AbstractTransaction
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface
     */
    protected $transactionsLogger;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     */
    public function __construct(
        CallAdapterInterface $executionAdapter,
        AmazonpayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->config = $config;
        $this->transactionsLogger = $transactionLogger;
    }

}
