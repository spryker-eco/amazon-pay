<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use SprykerEco\Shared\Amazonpay\AmazonpayConstants;

class UpdateOrderAuthorizationStatusTransaction extends AbstractAmazonpayTransaction
{

    /**
     * @var \Generated\Shared\Transfer\AmazonpayResponseTransfer
     */
    protected $apiResponse;

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return \Generated\Shared\Transfer\AmazonpayCallTransfer
     */
    public function execute(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        $amazonpayCallTransfer = parent::execute($amazonpayCallTransfer);

        $amazonPayment = $amazonpayCallTransfer->getAmazonpayPayment();

        if ($amazonPayment->getResponseHeader()->getIsSuccess()) {
            $status = $amazonPayment->getAuthorizationDetails()->getAuthorizationStatus();

            if ($amazonPayment->getAuthorizationDetails()->getIdList()) {
                $this->paymentEntity->setAmazonCaptureId(
                    $amazonPayment->getAuthorizationDetails()->getIdList()
                )
                    ->setStatus(
                        $status->getIsClosed()
                            ? AmazonpayConstants::OMS_STATUS_CLOSED
                            : AmazonpayConstants::OMS_STATUS_CAPTURE_COMPLETED
                    )
                    ->save();

                return $amazonpayCallTransfer;
            }

            if ($status->getIsPending()) {
                return $amazonpayCallTransfer;
            }

            if ($status->getIsDeclined()) {
                if ($status->getIsSuspended()) {
                    $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_SUSPENDED);
                } elseif ($status->getIsTransactionTimedOut()) {
                    $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_TRANSACTION_TIMED_OUT);
                } else {
                    $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_DECLINED);
                }
            }

            if ($status->getIsOpen()) {
                $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_OPEN);
            }

            if ($status->getIsClosed()) {
                if ($status->getIsReauthorizable()) {
                    $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_EXPIRED);
                } else {
                    $this->paymentEntity->setStatus(AmazonpayConstants::OMS_STATUS_AUTH_CLOSED);
                }
            }

            $this->paymentEntity->save();
        }

        return $amazonpayCallTransfer;
    }

}
