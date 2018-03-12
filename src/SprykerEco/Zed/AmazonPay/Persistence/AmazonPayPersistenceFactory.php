<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Persistence;

use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery;
use Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpaySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \SprykerEco\Shared\AmazonPay\AmazonPayConfig getConfig()
 * @method \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface getQueryContainer()
 */
class AmazonPayPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpayQuery
     */
    public function createPaymentAmazonpayQuery()
    {
        return SpyPaymentAmazonpayQuery::create();
    }

    /**
     * @return \Orm\Zed\AmazonPay\Persistence\SpyPaymentAmazonpaySalesOrderItemQuery
     */
    public function createPaymentAmazonpaySalesOrderItemQuery()
    {
        return SpyPaymentAmazonpaySalesOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSalesOrderItemQuery()
    {
        return SpySalesOrderItemQuery::create();
    }
}
