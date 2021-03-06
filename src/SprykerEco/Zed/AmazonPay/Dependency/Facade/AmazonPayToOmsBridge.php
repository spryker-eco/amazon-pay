<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Dependency\Facade;

use Propel\Runtime\Collection\ObjectCollection;

class AmazonPayToOmsBridge implements AmazonPayToOmsInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $eventId
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = [])
    {
        return $this->omsFacade->triggerEvent($eventId, $orderItems, $logContext, $data);
    }

    /**
     * @param array $logContext
     *
     * @return int
     */
    public function checkConditions(array $logContext = [])
    {
        return $this->omsFacade->checkConditions($logContext);
    }

    /**
     * @param int $idOrder
     * @param string $flag
     *
     * @return bool
     */
    public function isOrderFlaggedAll($idOrder, $flag)
    {
        return $this->omsFacade->isOrderFlaggedAll($idOrder, $flag);
    }
}
