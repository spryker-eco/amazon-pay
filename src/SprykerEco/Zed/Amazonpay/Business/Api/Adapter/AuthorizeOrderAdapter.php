<?php


/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Adapter;

use Generated\Shared\Transfer\OrderTransfer;

class AuthorizeOrderAdapter extends AbstractAuthorizeAdapter implements OrderAdapterInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayAuthorizeOrderResponseTransfer
     */
    public function call(OrderTransfer $orderTransfer)
    {
        $result = $this->client->authorize(
            $this->buildRequestArray($orderTransfer->getAmazonpayPayment(), $this->getAmount($orderTransfer))
        );

        return $this->converter->convert($result);
    }

}
