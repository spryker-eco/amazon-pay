<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface;

class CustomerDataQuoteUpdater extends QuoteUpdaterAbstract
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\ObtainProfileInformationAdapter
     */
    protected $executionAdapter;

    /**
     * @var \SprykerEco\Shared\Amazonpay\AmazonpayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     */
    public function __construct(
        CallAdapterInterface $executionAdapter,
        AmazonpayConfigInterface $config
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function update(QuoteTransfer $quoteTransfer)
    {
        $amazonCallTransfer = $this->convertQuoteTransferToAmazonPayTransfer($quoteTransfer);
        $customer = $this->executionAdapter->call($amazonCallTransfer);

        $this->updateCustomer($quoteTransfer, $customer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     */
    protected function updateCustomer(QuoteTransfer $quoteTransfer, CustomerTransfer $customer)
    {
        $quoteTransfer->getCustomer()->fromArray($customer->modifiedToArray());

        if ($quoteTransfer->getCustomer()->getIdCustomer()) {
            $quoteTransfer->getCustomer()->setIsGuest(false);
        }
    }

}
