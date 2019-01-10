<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\AmazonPay\AmazonPayDependencyProvider;
use SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayEntityToTransferConverter;
use SprykerEco\Zed\AmazonPay\Business\Payment\RequestAmountCalculator;

/**
 * @method \SprykerEco\Shared\AmazonPay\AmazonPayConfig getConfig()
 * @method \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\AmazonPay\Business\AmazonPayFacadeInterface getFacade()
 */
class AmazonPayCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToRefundInterface
     */
    public function getRefundFacade()
    {
        return $this->getProvidedDependency(AmazonPayDependencyProvider::FACADE_REFUND);
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Payment\RequestAmountCalculatorInterface
     */
    public function createRequestAmountCalculator()
    {
        return new RequestAmountCalculator($this->getOmsFacade());
    }

    /**
     * @return \SprykerEco\Zed\AmazonPay\Business\Converter\AmazonPayEntityToTransferConverterInterface
     */
    public function createPaymentAmazonpayConverter()
    {
        return new AmazonPayEntityToTransferConverter();
    }
}
