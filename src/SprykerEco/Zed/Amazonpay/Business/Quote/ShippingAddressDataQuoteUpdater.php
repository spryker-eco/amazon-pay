<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface;

class ShippingAddressDataQuoteUpdater implements QuoteUpdaterInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \SprykerEco\Shared\Amazonpay\AmazonpayConfig
     */
    protected $config;

    /**
     * @var \Generated\Shared\Transfer\AmazonpaySetOrderReferenceDetailsResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\QuoteAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfig $config
     */
    public function __construct(
        QuoteAdapterInterface $executionAdapter,
        AmazonpayConfig $config
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
        $this->apiResponse = $this->executionAdapter->call($quoteTransfer);

        if ($this->apiResponse->getHeader()->getIsSuccess()) {
            $quoteTransfer->setShippingAddress($this->apiResponse->getShippingAddress());
        }

        return $quoteTransfer;
    }

}
