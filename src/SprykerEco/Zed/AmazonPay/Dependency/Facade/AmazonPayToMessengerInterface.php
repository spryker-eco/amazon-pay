<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Dependency\Facade;

use Generated\Shared\Transfer\MessageTransfer;

interface AmazonPayToMessengerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messenger
     *
     * @return array
     */
    public function addErrorMessage(MessageTransfer $messenger);
}
