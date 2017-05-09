<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AbstractAdapter;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;

abstract class AbstractTransaction
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AbstractAdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface
     */
    protected $config;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected $apiResponse;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface
     */
    protected $transactionsLogger;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\AbstractAdapter $executionAdapter
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
     */
    public function __construct(
        AbstractAdapter $executionAdapter,
        AmazonpayConfigInterface $config,
        TransactionLoggerInterface $transactionLogger
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->config = $config;
        $this->transactionsLogger = $transactionLogger;
    }

}
