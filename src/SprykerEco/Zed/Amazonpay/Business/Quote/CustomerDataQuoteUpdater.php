<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface;

class CustomerDataQuoteUpdater implements QuoteUpdaterInterface
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
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfigInterface $config
     */
    public function __construct(
        QuoteAdapterInterface $executionAdapter,
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
        $quoteTransfer->setCustomer(
            $this->executionAdapter->call($quoteTransfer)
        );

        $quoteTransfer->getCustomer()->setIsGuest(true);

        return $quoteTransfer;
    }

}
