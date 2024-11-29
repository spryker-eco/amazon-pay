import register from 'ShopUi/app/registry';
export default register('amazon-order-submitter', () => import(/* webpackMode: "lazy" */'./amazon-order-submitter'));
