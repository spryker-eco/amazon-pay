import Component from 'ShopUi/models/component';

export default class Success extends Component {
    protected readyCallback(): void {
        this.amazonLoginReady();
    }
    
    protected amazonLoginReady(): void {
        (<any>window).onAmazonLoginReady = () => {
            (<any>window).amazon.Login.logout();
        };
    }
}
