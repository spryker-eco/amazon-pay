<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Ipn;

use Spryker\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLogger;
use Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface;
use Spryker\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface;
use Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingInterface;

class IpnFactory implements IpnFactoryInterface
{

    /**
     * @var \Spryker\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface
     */
    protected $amazonpayQueryContainer;

    /**
     * @var \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingInterface
     */
    protected $encodingService;

    /**
     * @param \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToOmsInterface $omsFacade
     * @param \Spryker\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface $amazonpayQueryContainer
     * @param \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToUtilEncodingInterface
     */
    public function __construct(
        AmazonpayToOmsInterface $omsFacade,
        AmazonpayQueryContainerInterface $amazonpayQueryContainer,
        AmazonpayToUtilEncodingInterface $amazonpayToUtilEncoding
    ) {
        $this->omsFacade = $omsFacade;
        $this->amazonpayQueryContainer = $amazonpayQueryContainer;
        $this->encodingService = $amazonpayToUtilEncoding;
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Payment\Handler\Ipn\Logger\IpnRequestLoggerInterface
     */
    public function createIpnRequestLogger()
    {
        return new IpnRequestLogger($this->encodingService);
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Business\Payment\Handler\Ipn\IpnRequestFactoryInterface
     */
    public function createIpnRequestFactory()
    {
        return new IpnRequestFactory(
            $this->omsFacade,
            $this->amazonpayQueryContainer,
            $this->createIpnRequestLogger()
        );
    }

}
