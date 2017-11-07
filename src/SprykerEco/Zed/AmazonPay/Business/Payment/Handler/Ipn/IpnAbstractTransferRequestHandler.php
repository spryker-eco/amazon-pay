<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn;

use Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface;
use SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface;

abstract class IpnAbstractTransferRequestHandler implements IpnRequestHandlerInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface $omsFacade
     */
    protected $omsFacade;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface $amazonPayQueryContainer
     */
    protected $amazonPayQueryContainer;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface $ipnRequestLogger
     */
    protected $ipnRequestLogger;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface $omsFacade
     * @param \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface $amazonPayQueryContainer
     * @param \SprykerEco\Zed\AmazonPay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface $ipnRequestLogger
     */
    public function __construct(
        AmazonPayToOmsInterface $omsFacade,
        AmazonPayQueryContainerInterface $amazonPayQueryContainer,
        IpnRequestLoggerInterface $ipnRequestLogger
    ) {
        $this->omsFacade = $omsFacade;
        $this->amazonPayQueryContainer = $amazonPayQueryContainer;
        $this->ipnRequestLogger = $ipnRequestLogger;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer
     *
     * @return void
     */
    public function handle(AmazonpayIpnPaymentRequestTransfer $paymentRequestTransfer)
    {
        $paymentEntity = $this->retrievePaymentEntity($paymentRequestTransfer);

        if ($paymentEntity === null) {
            return;
        }

        $paymentEntity->setStatus($this->getOmsStatusName());
        $paymentEntity->save();

        $this->omsFacade->triggerEvent(
            $this->getOmsEventId(),
            $this->getAffectedSalesOrderItems($paymentEntity),
            []
        );

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
    abstract protected function getOmsStatusName();
}
