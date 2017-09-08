<?php

/**
 * Apache OSL-2
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Amazonpay\Dependency\Injector;

use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command\CancelOrderCommandPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command\CaptureCommandPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command\CloseOrderCommandPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command\ReauthorizeExpiredOrderCommandPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command\RefundOrderCommandPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command\UpdateAuthorizationStatusCommandPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command\UpdateCaptureStatusCommandPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command\UpdateNewOrderStatusCommandPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command\UpdateRefundStatusCommandPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Command\UpdateSuspendedOrderCommandPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsAuthClosedConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsAuthDeclinedConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsAuthExpiredConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsAuthOpenConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsAuthPendingConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsAuthSuspendedConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsAuthTransactionTimedOutConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsCancelledConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsCaptureCompletedConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsCaptureDeclinedConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsCapturePendingConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsCloseAllowedConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsClosedConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsRefundCompletedConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsRefundDeclinedConditionPlugin;
use SprykerEco\Zed\Amazonpay\Communication\Plugin\Oms\Condition\IsRefundPendingConditionPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\AbstractDependencyInjector;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\OmsDependencyProvider;

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
                    ->add(new CancelOrderCommandPlugin(), 'Amazonpay/CancelOrder')
                    ->add(new CloseOrderCommandPlugin(), 'Amazonpay/CloseOrder')
                    ->add(new RefundOrderCommandPlugin(), 'Amazonpay/RefundOrder')
                    ->add(new ReauthorizeExpiredOrderCommandPlugin(), 'Amazonpay/ReauthorizeExpiredOrder')
                    ->add(new CaptureCommandPlugin(), 'Amazonpay/Capture')
                    ->add(new UpdateSuspendedOrderCommandPlugin(), 'Amazonpay/UpdateSuspendedOrder')
                    ->add(new UpdateNewOrderStatusCommandPlugin(), 'Amazonpay/UpdateNewOrderStatus')
                    ->add(new UpdateAuthorizationStatusCommandPlugin(), 'Amazonpay/UpdateAuthorizationStatus')
                    ->add(new UpdateCaptureStatusCommandPlugin(), 'Amazonpay/UpdateCaptureStatus')
                    ->add(new UpdateRefundStatusCommandPlugin(), 'Amazonpay/UpdateRefundStatus');

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
                ->add(new IsClosedConditionPlugin(), 'Amazonpay/IsClosed')
                ->add(new IsCloseAllowedConditionPlugin(), 'Amazonpay/IsCloseAllowed')
                ->add(new IsCancelledConditionPlugin(), 'Amazonpay/IsCancelled')

                ->add(new IsAuthOpenConditionPlugin(), 'Amazonpay/IsAuthOpen')
                ->add(new IsAuthDeclinedConditionPlugin(), 'Amazonpay/IsAuthDeclined')
                ->add(new IsAuthPendingConditionPlugin(), 'Amazonpay/IsAuthPending')
                ->add(new IsAuthSuspendedConditionPlugin(), 'Amazonpay/IsAuthSuspended')
                ->add(new IsAuthExpiredConditionPlugin(), 'Amazonpay/IsAuthExpired')
                ->add(new IsAuthClosedConditionPlugin(), 'Amazonpay/IsAuthClosed')
                ->add(new IsAuthTransactionTimedOutConditionPlugin(), 'Amazonpay/IsAuthTransactionTimedOut')

                ->add(new IsAuthSuspendedConditionPlugin(), 'Amazonpay/IsPaymentMethodChanged')

                ->add(new IsCaptureCompletedConditionPlugin(), 'Amazonpay/IsCaptureCompleted')
                ->add(new IsCaptureDeclinedConditionPlugin(), 'Amazonpay/IsCaptureDeclined')
                ->add(new IsCapturePendingConditionPlugin(), 'Amazonpay/IsCapturePending')

                ->add(new IsRefundCompletedConditionPlugin(), 'Amazonpay/IsRefundCompleted')
                ->add(new IsRefundDeclinedConditionPlugin(), 'Amazonpay/IsRefundDeclined')
                ->add(new IsRefundPendingConditionPlugin(), 'Amazonpay/IsRefundPending');

            return $conditionCollection;
        });

        return $container;
    }

}
