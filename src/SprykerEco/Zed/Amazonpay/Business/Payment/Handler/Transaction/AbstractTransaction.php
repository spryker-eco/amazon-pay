<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Spryker\Shared\Amazonpay\AmazonpayConfigInterface;
use Spryker\Zed\Amazonpay\Business\Api\Adapter\AbstractAdapter;
use Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface;

abstract class AbstractTransaction
{

    /**
     * @var \Spryker\Zed\Amazonpay\Business\Api\Adapter\AbstractAdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \Spryker\Shared\Amazonpay\AmazonpayConfigInterface
     */
    protected $config;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected $apiResponse;

    /**
     * @var \Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface
     */
    protected $transactionsLogger;

    /**
     * @param \Spryker\Zed\Amazonpay\Business\Api\Adapter\AbstractAdapter $executionAdapter
     * @param \Spryker\Shared\Amazonpay\AmazonpayConfigInterface $config
     * @param \Spryker\Zed\Amazonpay\Business\Payment\Handler\Transaction\Logger\TransactionLoggerInterface $transactionLogger
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
