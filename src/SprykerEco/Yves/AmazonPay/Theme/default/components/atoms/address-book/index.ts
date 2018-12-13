import './address-book.scss';
import register from 'ShopUi/app/registry';
export default register('address-book', () => import(/* webpackMode: "" */'./address-book'));
