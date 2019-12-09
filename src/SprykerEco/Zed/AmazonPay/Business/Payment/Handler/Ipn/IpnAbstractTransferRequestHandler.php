<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface;
use SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface;

abstract class IpnAbstractTransferRequestHandler implements IpnRequestHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface
     */
    protected $ipnRequestLogger;

    /**
     * @var \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface $omsFacade
     * @param \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface $ipnRequestLogger
     * @param \SprykerEco\Shared\AmazonPay\AmazonPayConfigInterface $config
     */
    public function __construct(
        AmazonPayToOmsInterface $omsFacade,
        AmazonPayQueryContainerInterface $queryContainer,
        IpnRequestLoggerInterface $ipnRequestLogger,
        AmazonPayConfigInterface $config
    ) {
        $this->omsFacade = $omsFacade;
        $this->queryContainer = $queryContainer;
        $this->ipnRequestLogger = $ipnRequestLogger;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return void
     */
    public function handle(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer)
    {
        $repeatable = 2;
        while ($repeatable-- > 0) {
            try {
                $this->handleDatabaseTransaction(function () use ($paymentRequestTransfer) {
                    if ($this->config->getEnableIsolateLevelRead()) {
                        Propel::getConnection()->exec('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;');
                    }
                    Propel::disableInstancePooling();

                    $this->handleTransactional($paymentRequestTransfer);
                });

                break;
            } catch (\Throwable $e) {
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return void
     */
    protected function handleTransactional(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer)
    {
        $paymentEntity = $this->retrievePaymentEntity($paymentRequestTransfer);

        if ($paymentEntity === null) {
            return;
        }

        $paymentEntity->setStatus($this->getStatusName());
        $paymentEntity->save();

        $this->omsFacade->triggerEvent(
            $this->getOmsEventId(),
            $this->getAffectedSalesOrderItems($paymentEntity),
            []
        );
        $this->omsFacade->checkConditions();

        $this->ipnRequestLogger->log($paymentRequestTransfer, $paymentEntity);
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentEntity
     *
     * @return \Propel\Runtime\Collection\ObjectCollection | \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    protected function getAffectedSalesOrderItems(SpyPaymentAmazonpay $paymentEntity)
    {
        $items = new ObjectCollection();

        foreach ($paymentEntity->getSpyPaymentAmazonpaySalesOrderItems() as $amazonpaySalesOrderItem) {
            $items[] = $amazonpaySalesOrderItem->getSpySalesOrderItem();
        }

        return $items;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay|null
     */
    abstract protected function retrievePaymentEntity(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer);

    /**
     * @return string
     */
    abstract protected function getOmsEventId();

    /**
     * @return string
     */
    abstract protected function getStatusName();
}
