<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConfig;
use SprykerEco\Zed\Amazonpay\Business\Api\Adapter\SetOrderReferenceDetailsAdapter;

class ShippingAddressDataQuoteUpdater extends QuoteUpdaterAbstract
{

    /**
     * @var \SprykerEco\Shared\Amazonpay\AmazonpayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Business\Api\Adapter\CallAdapterInterface $executionAdapter
     * @param \SprykerEco\Shared\Amazonpay\AmazonpayConfig $config
     */
    public function __construct(
        SetOrderReferenceDetailsAdapter $executionAdapter,
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
        $apiResponse = $this->executionAdapter->call(
            $this->convertQuoteTransferToAmazonPayTransfer($quoteTransfer)
        );

        if ($apiResponse->getHeader()->getIsSuccess()) {
            $quoteTransfer->setShippingAddress($apiResponse->getShippingAddress());
        }

        return $quoteTransfer;
    }

}
