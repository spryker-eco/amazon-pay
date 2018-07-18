<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayPersistenceFactory getFactory()
 */
class AmazonPayQueryContainer extends AbstractQueryContainer implements AmazonPayQueryContainerInterface
{
    /**
     * @api
     *
     * @param string $orderReferenceId
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
     */
    public function queryPaymentByOrderReferenceId($orderReferenceId)
    {
        return $this
            ->queryPayments()
            ->filterByOrderReferenceId($orderReferenceId);
    }

    /**
     * @api
     *
     * @param string $authorizationReferenceId
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
     */
    public function queryPaymentByAuthorizationReferenceId($authorizationReferenceId)
    {
        return $this
            ->queryPayments()
            ->filterByAuthorizationReferenceId($authorizationReferenceId);
    }

    /**
     * @api
     *
     * @param string $captureReferenceId
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
     */
    public function queryPaymentByCaptureReferenceId($captureReferenceId)
    {
        return $this
            ->queryPayments()
            ->filterByCaptureReferenceId($captureReferenceId);
    }

    /**
     * @api
     *
     * @param string $refundReferenceId
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
     */
    public function queryPaymentByRefundReferenceId($refundReferenceId)
    {
        return $this
            ->queryPayments()
            ->filterByRefundReferenceId($refundReferenceId);
    }

    /**
     * @api
     *
     * @param string $authorizationReferenceId
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $excludeItems
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
     */
    public function querySalesOrderItemsByPaymentReferenceId($authorizationReferenceId, array $excludeItems)
    {
        return $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByIdSalesOrderItem($excludeItems, Criteria::NOT_IN)
            ->useSpyPaymentAmazonpaySalesOrderItemQuery()
                ->useSpyPaymentAmazonpayQuery()
                    ->filterByAuthorizationReferenceId($authorizationReferenceId)
                ->endUse()
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $salesOrderItemId
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
     */
    public function queryPaymentBySalesOrderItemId($salesOrderItemId)
    {
        return $this->getFactory()
            ->createPaymentAmazonpayQuery()
            ->useSpyPaymentAmazonpaySalesOrderItemQuery()
                ->filterByFkSalesOrderItem($salesOrderItemId)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $salesOrderItemId
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpaySalesOrderItemQuery
     */
    public function queryBySalesOrderItemId($salesOrderItemId)
    {
        return $this->getFactory()
            ->createPaymentAmazonpaySalesOrderItemQuery()
            ->filterByFkSalesOrderItem($salesOrderItemId);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
     */
    protected function queryPayments()
    {
        return $this
            ->getFactory()
            ->createPaymentAmazonpayQuery();
    }
}
