<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification;

use Generated\Shared\Transfer\AmazonpayCallTransfer;

interface OrderMessageBuilderInterface
{

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction\Notification\AbstractNotificationMessage|null
     */
    public function createFailedAuthMessage(AmazonpayCallTransfer $amazonpayCallTransfer);

}
