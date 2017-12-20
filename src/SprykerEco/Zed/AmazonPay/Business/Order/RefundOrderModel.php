<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Order;

use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToRefundInterface;

class RefundOrderModel implements RefundOrderInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToRefundInterface
     */
    protected $refundFacade;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToRefundInterface $refundFacade
     */
    public function __construct(
        AmazonPayToRefundInterface $refundFacade
    ) {

        $this->refundFacade = $refundFacade;
    }

    /**
     * @param \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpay $paymentEntity
     *
     * @return void
     */
    public function refundPayment(SpyPaymentAmazonpay $paymentEntity)
    {
        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $items */
        $items = [];

        foreach ($paymentEntity->getSpyPaymentAmazonpaySalesOrderItems() as $amazonpaySalesOrderItem) {
            $items[] = $amazonpaySalesOrderItem->getSpySalesOrderItem();
        }

        if (count($items) > 0) {
            $refundTransfer = $this->refundFacade->calculateRefund($items, $items[0]->getOrder());
            $this->refundFacade->saveRefund($refundTransfer);
        }
    }
}
