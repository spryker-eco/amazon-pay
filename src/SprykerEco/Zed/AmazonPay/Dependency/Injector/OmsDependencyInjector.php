<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\AmazonPay\Dependency\Injector;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\AbstractDependencyInjector;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\OmsDependencyProvider;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command\CancelOrderCommandPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command\CaptureCommandPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command\CloseOrderCommandPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command\ReauthorizeExpiredOrderCommandPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command\RefundOrderCommandPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command\UpdateAuthorizationStatusCommandPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command\UpdateCaptureStatusCommandPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command\UpdateNewOrderStatusCommandPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command\UpdateRefundStatusCommandPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Command\UpdateSuspendedOrderCommandPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsAuthClosedConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsAuthDeclinedConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsAuthExpiredConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsAuthOpenConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsAuthPendingConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsAuthSuspendedConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsAuthTransactionTimedOutConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsCancelledConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsCancelledOrderConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsCancelNotAllowedConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsCaptureCompletedConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsCaptureDeclinedConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsCapturePendingConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsCloseAllowedConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsClosedConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsRefundCompletedConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsRefundDeclinedConditionPlugin;
use SprykerEco\Zed\AmazonPay\Communication\Plugin\Oms\Condition\IsRefundPendingConditionPlugin;

class OmsDependencyInjector extends AbstractDependencyInjector
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function injectBusinessLayerDependencies(Container $container)
    {
        $container = $this->injectCommands($container);
        $container = $this->injectConditions($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function injectCommands(Container $container)
    {
        $container->extend(
            OmsDependencyProvider::COMMAND_PLUGINS,
            function (CommandCollectionInterface $commandCollection) {
                $commandCollection
                    ->add(new CancelOrderCommandPlugin(), 'AmazonPay/CancelOrder')
                    ->add(new CloseOrderCommandPlugin(), 'AmazonPay/CloseOrder')
                    ->add(new RefundOrderCommandPlugin(), 'AmazonPay/RefundOrder')
                    ->add(new ReauthorizeExpiredOrderCommandPlugin(), 'AmazonPay/ReauthorizeExpiredOrder')
                    ->add(new CaptureCommandPlugin(), 'AmazonPay/Capture')
                    ->add(new UpdateSuspendedOrderCommandPlugin(), 'AmazonPay/UpdateSuspendedOrder')
                    ->add(new UpdateNewOrderStatusCommandPlugin(), 'AmazonPay/UpdateNewOrderStatus')
                    ->add(new UpdateAuthorizationStatusCommandPlugin(), 'AmazonPay/UpdateAuthorizationStatus')
                    ->add(new UpdateCaptureStatusCommandPlugin(), 'AmazonPay/UpdateCaptureStatus')
                    ->add(new UpdateRefundStatusCommandPlugin(), 'AmazonPay/UpdateRefundStatus');

                return $commandCollection;
            }
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function injectConditions(Container $container)
    {
        $container->extend(OmsDependencyProvider::CONDITION_PLUGINS, function (ConditionCollectionInterface $conditionCollection) {
            $conditionCollection
                ->add(new IsClosedConditionPlugin(), 'AmazonPay/IsClosed')
                ->add(new IsCloseAllowedConditionPlugin(), 'AmazonPay/IsCloseAllowed')

                ->add(new IsCancelledConditionPlugin(), 'AmazonPay/IsCancelled')
                ->add(new IsCancelNotAllowedConditionPlugin(), 'AmazonPay/IsCancelNotAllowed')
                ->add(new IsCancelledOrderConditionPlugin(), 'AmazonPay/IsOrderCancelled')

                ->add(new IsAuthOpenConditionPlugin(), 'AmazonPay/IsAuthOpen')
                ->add(new IsAuthDeclinedConditionPlugin(), 'AmazonPay/IsAuthDeclined')
                ->add(new IsAuthPendingConditionPlugin(), 'AmazonPay/IsAuthPending')
                ->add(new IsAuthSuspendedConditionPlugin(), 'AmazonPay/IsAuthSuspended')
                ->add(new IsAuthExpiredConditionPlugin(), 'AmazonPay/IsAuthExpired')
                ->add(new IsAuthClosedConditionPlugin(), 'AmazonPay/IsAuthClosed')
                ->add(new IsAuthTransactionTimedOutConditionPlugin(), 'AmazonPay/IsAuthTransactionTimedOut')
                ->add(new IsAuthSuspendedConditionPlugin(), 'AmazonPay/IsPaymentMethodChanged')

                ->add(new IsCaptureCompletedConditionPlugin(), 'AmazonPay/IsCaptureCompleted')
                ->add(new IsCaptureDeclinedConditionPlugin(), 'AmazonPay/IsCaptureDeclined')
                ->add(new IsCapturePendingConditionPlugin(), 'AmazonPay/IsCapturePending')

                ->add(new IsRefundCompletedConditionPlugin(), 'AmazonPay/IsRefundCompleted')
                ->add(new IsRefundDeclinedConditionPlugin(), 'AmazonPay/IsRefundDeclined')
                ->add(new IsRefundPendingConditionPlugin(), 'AmazonPay/IsRefundPending');

            return $conditionCollection;
        });

        return $container;
    }
}
