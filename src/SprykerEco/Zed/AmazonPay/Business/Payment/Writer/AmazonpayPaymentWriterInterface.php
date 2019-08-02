<?php


namespace SprykerEco\Zed\AmazonPay\Business\Payment\Writer;


use Generated\Shared\Transfer\AmazonpayPaymentTransfer;

interface AmazonpayPaymentWriterInterface
{
    /**
     * @param AmazonpayPaymentTransfer $amazonpayPaymentTransfer
     *
     * @return bool
     */
    public function writerConfirmedAmazonPayPayment(AmazonpayPaymentTransfer $amazonpayPaymentTransfer): bool;
}
