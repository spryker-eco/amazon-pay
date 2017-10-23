<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface;
use SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;

abstract class IpnAbstractTransferRequestHandler implements IpnRequestHandlerInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface $omsFacade
     */
    protected $omsFacade;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     */
    protected $amazonpayQueryContainer;

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface $ipnRequestLogger
     */
    protected $ipnRequestLogger;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface $omsFacade
     * @param \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     * @param \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface $ipnRequestLogger
     */
    public function __construct(
        AmazonpayToOmsInterface $omsFacade,
        AmazonpayQueryContainerInterface $amazonpayQueryContainer,
        IpnRequestLoggerInterface $ipnRequestLogger
    ) {
        $this->omsFacade = $omsFacade;
        $this->amazonpayQueryContainer = $amazonpayQueryContainer;
        $this->ipnRequestLogger = $ipnRequestLogger;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $amazonpayIpnRequestTransfer
     *
     * @return void
     */
    public function handle(AbstractTransfer $amazonpayIpnRequestTransfer)
    {
        $paymentEntity = $this->retrievePaymentEntity($amazonpayIpnRequestTransfer);

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

        $this->ipnRequestLogger->log($amazonpayIpnRequestTransfer, $paymentEntity);
    }

    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $paymentEntity
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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $amazonpayIpnPaymentAuthorizeRequestTransfer
     *
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay
     */
    abstract protected function retrievePaymentEntity(
        AbstractTransfer $amazonpayIpnPaymentAuthorizeRequestTransfer
    );

    /**
     * @return string
     */
    abstract protected function getOmsEventId();

    /**
     * @return string
     */
    abstract protected function getOmsStatusName();

}
