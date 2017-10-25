<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Api\Converter;

use Generated\Shared\Transfer\CustomerTransfer;

class ObtainProfileInformationConverter extends AbstractArrayConverter
{
    /**
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function convert(array $response)
    {
        $responseTransfer = new CustomerTransfer();

        if (!empty($response['name'])) {
            $responseTransfer = $this->updateNameData($responseTransfer, $response['name']);
        }

        if (!empty($response['email'])) {
            $responseTransfer->setEmail($response['email']);
        }

        return $responseTransfer;
    }
}
