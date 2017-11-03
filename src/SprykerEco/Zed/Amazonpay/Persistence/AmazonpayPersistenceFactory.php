<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Persistence;

use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery;
use Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpaySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \SprykerEco\Shared\Amazonpay\AmazonpayConfig getConfig()
 * @method \SprykerEco\Zed\Amazonpay\Persistence\AmazonpayQueryContainer getQueryContainer()
 */
class AmazonpayPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpayQuery
     */
    public function createPaymentAmazonpayQuery()
    {
        return SpyPaymentAmazonpayQuery::create();
    }

    /**
     * @return \Orm\Zed\Amazonpay\Persistence\SpyPaymentAmazonpaySalesOrderItemQuery
     */
    public function createPaymentAmazonpaySalesOrderItemQuery()
    {
        return SpyPaymentAmazonpaySalesOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSpySalesOrderItemQuery()
    {
        return SpySalesOrderItemQuery::create();
    }
}
