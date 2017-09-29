<?php

namespace SprykerEco\Zed\Amazonpay\Business\Order;

use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay;
use SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToRefundInterface;

class RefundOrderModel implements RefundOrderInterface
{

    /**
     * @var \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToRefundInterface
     */
    protected $refundFacade;

    /**
     * @param \SprykerEco\Zed\Amazonpay\Dependency\Facade\AmazonpayToRefundInterface $refundFacade
     */
    public function __construct(
        AmazonpayToRefundInterface $refundFacade
    ) {

        $this->refundFacade = $refundFacade;
    }

    /**
     * @param \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpay $paymentEntity
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
