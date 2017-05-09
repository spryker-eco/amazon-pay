<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayCancelOrderResponseTransfer;

class CancelOrderConverter extends AbstractResponseParserConverter
{

    /**
     * @return string
     */
    protected function getResponseType()
    {
        return 'CancelOrderReferenceResult';
    }

    /**
     * @return \Generated\Shared\Transfer\AmazonpayCancelOrderResponseTransfer
     */
    protected function createTransferObject()
    {
        return new AmazonpayCancelOrderResponseTransfer();
    }

}
