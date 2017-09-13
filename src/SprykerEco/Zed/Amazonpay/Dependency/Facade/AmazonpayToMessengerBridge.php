<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Dependency\Facade;

use Generated\Shared\Transfer\MessageTransfer;

class AmazonpayToMessengerBridge implements AmazonpayToMessengerInterface
{

    /**
     * @var \Spryker\Zed\Messenger\Business\MessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\Messenger\Business\MessengerFacadeInterface $messengerFacade
     */
    public function __construct($messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messenger
     *
     * @return array
     */
    public function addErrorMessage(MessageTransfer $messenger)
    {
        return $this->messengerFacade->addErrorMessage($messenger);
    }

}
