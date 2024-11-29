import './payment-step.scss';
import register from 'ShopUi/app/registry';
export default register('payment-step', () => import(
    /* webpackMode: "lazy" */
    /* webpackChunkName: "payment-step" */
    './payment-step'));
