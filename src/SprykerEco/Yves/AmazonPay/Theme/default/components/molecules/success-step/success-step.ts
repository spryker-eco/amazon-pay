import Component from 'ShopUi/models/component';

declare const window: any;

export default class Success extends Component {
    protected readyCallback(): void {
        this.amazonLoginReady();
    }

    protected amazonLoginReady(): void {
        window.onAmazonLoginReady = () => {
            window.amazon.Login.logout();
        };
    }
}
