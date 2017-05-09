<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayCloseOrderResponseTransfer;

class CloseOrderConverter extends AbstractResponseParserConverter
{

    /**
     * @return string
     */
    protected function getResponseType()
    {
        return 'CloseOrderReferenceResult';
    }

    /**
     * @return \Generated\Shared\Transfer\AmazonpayCloseOrderResponseTransfer
     */
    protected function createTransferObject()
    {
        return new AmazonpayCloseOrderResponseTransfer();
    }

}
