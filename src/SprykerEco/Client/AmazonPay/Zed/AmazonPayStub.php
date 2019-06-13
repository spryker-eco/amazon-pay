<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\AmazonPay\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class AmazonPayStub implements AmazonPayStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedStub
     */
    public function __construct(ZedRequestClientInterface $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function handleCartWithAmazonPay(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/amazon-pay/gateway/handle-cart-with-amazon-pay', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function addSelectedAddressToQuote(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call(
            '/amazon-pay/gateway/add-selected-address-to-quote',
            $quoteTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function addSelectedShipmentMethodToQuote(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call(
            '/amazon-pay/gateway/add-selected-shipment-method-to-quote',
            $quoteTransfer
        );
    }
}
