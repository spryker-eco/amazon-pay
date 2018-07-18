<?php


/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Business\Order;

use Generated\Shared\Transfer\AmazonpayCallTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface;
use SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface;

class RelatedItemsUpdateModel implements RelatedItemsUpdateInterface
{
    /**
     * @var \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface
     */
    protected $omsFacade;

    /**
     * @param \SprykerEco\Zed\AmazonPay\Persistence\AmazonPayQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\AmazonPay\Dependency\Facade\AmazonPayToOmsInterface $omsFacade
     */
    public function __construct(AmazonPayQueryContainerInterface $queryContainer, AmazonPayToOmsInterface $omsFacade)
    {
        $this->queryContainer = $queryContainer;
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     * @param int[] $alreadyAffectedItems
     * @param string $eventName
     *
     * @return void
     */
    public function triggerEvent(AmazonpayCallTransfer $amazonpayCallTransfer, array $alreadyAffectedItems, $eventName)
    {
        $affectedItems = $this->getAffectedItemIds($amazonpayCallTransfer);
        $affectedItems = array_merge($affectedItems, $alreadyAffectedItems);

        $toBeUpdatedItems = $this->queryContainer
            ->querySalesOrderItemsByPaymentReferenceId(
                $amazonpayCallTransfer->getAmazonpayPayment()->getAuthorizationDetails()->getAuthorizationReferenceId(),
                $affectedItems
            )
            ->find();

        if ($toBeUpdatedItems->count() > 0) {
            $this->omsFacade
                ->triggerEvent(
                    $eventName,
                    $toBeUpdatedItems,
                    [],
                    $affectedItems
                );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AmazonpayCallTransfer $amazonpayCallTransfer
     *
     * @return int[]
     */
    protected function getAffectedItemIds(AmazonpayCallTransfer $amazonpayCallTransfer)
    {
        return array_map(
            function (ItemTransfer $item) {
                return $item->getIdSalesOrderItem();
            },
            $amazonpayCallTransfer->getItems()->getArrayCopy()
        );
    }
}
