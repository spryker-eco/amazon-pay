<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface;
use Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface;
use Spryker\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;

abstract class IpnAbstractTransferRequestHandler implements IpnRequestHandlerInterface
{

    /**
     * @var \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsBridge $omsFacade
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     */
    protected $amazonpayQueryContainer;

    /**
     * @var \Spryker\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface $ipnRequestLogger
     */
    protected $ipnRequestLogger;

    /**
     * @param \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface $omsFacade
     * @param \Spryker\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     * @param \Spryker\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface $ipnRequestLogger
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
        $paymentEntity->setStatus($this->getOmsStatusName());
        $paymentEntity->save();

        $this->omsFacade->triggerEvent(
            $this->getOmsEventId(),
            $paymentEntity->getSpySalesOrder()->getItems(),
            []
        );

        $this->ipnRequestLogger->log($amazonpayIpnRequestTransfer, $paymentEntity);
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
