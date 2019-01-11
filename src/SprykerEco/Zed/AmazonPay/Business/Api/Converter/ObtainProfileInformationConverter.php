<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Api\Converter;

use Generated\Shared\Transfer\AmazonpayResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class ObtainProfileInformationConverter extends AbstractArrayConverter
{
    public const NAME = 'name';
    public const EMAIL = 'email';

    /**
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    public function convert(array $response)
    {
        $responseTransfer = new AmazonpayResponseTransfer();
        $customerTransfer = new CustomerTransfer();

        $this->extractName($response, $customerTransfer);
        $this->extractEmail($response, $customerTransfer);

        $responseTransfer->setCustomer($customerTransfer);

        return $responseTransfer;
    }

    /**
     * @param array $response
     * @param \Generated\Shared\Transfer\CustomerTransfer $responseTransfer
     *
     * @return void
     */
    protected function extractName(array $response, CustomerTransfer $responseTransfer)
    {
        if (!empty($response[self::NAME])) {
            $this->updateNameData($responseTransfer, $response[self::NAME]);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $transfer
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function updateNameData(CustomerTransfer $transfer, $name)
    {
        $names = $this->getNameData($name);

        $transfer->setFirstName($names[0]);
        $transfer->setLastName($names[1]);

        return $transfer;
    }

    /**
     * @param array $response
     * @param \Generated\Shared\Transfer\CustomerTransfer $responseTransfer
     *
     * @return void
     */
    protected function extractEmail(array $response, CustomerTransfer $responseTransfer)
    {
        if (!empty($response[self::EMAIL])) {
            $responseTransfer->setEmail($response[self::EMAIL]);
        }
    }
}
