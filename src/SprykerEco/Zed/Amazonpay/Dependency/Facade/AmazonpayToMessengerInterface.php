<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Dependency\Facade;

use Generated\Shared\Transfer\MessageTransfer;

interface AmazonpayToMessengerInterface
{

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messenger
     *
     * @return array
     */
    public function addErrorMessage(MessageTransfer $messenger);

}
