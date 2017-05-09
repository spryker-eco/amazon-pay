<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Communication;

use Spryker\Zed\Amazonpay\AmazonpayDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Shared\Amazonpay\AmazonpayConfig getConfig()
 * @method \Spryker\Zed\Amazonpay\Persistence\AmazonpayQueryContainerInterface getQueryContainer()
 */
class AmazonpayCommunicationFactory extends AbstractCommunicationFactory implements AmazonpayCommunicationFactoryInterface
{

    /**
     * @return \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(
            AmazonpayDependencyProvider::FACADE_SALES
        );
    }

    /**
     * @return \Spryker\Zed\Amazonpay\Dependency\Facade\AmazonpayToRefundInterface
     */
    public function getRefundFacade()
    {
        return $this->getProvidedDependency(AmazonpayDependencyProvider::FACADE_REFUND);
    }

}
