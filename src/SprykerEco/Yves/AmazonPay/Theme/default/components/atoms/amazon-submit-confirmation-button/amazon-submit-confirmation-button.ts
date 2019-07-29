import Component from 'ShopUi/models/component';

declare const window: {
    OffAmazonPayments: {
        initConfirmationFlow(sellerId: string, orderId: string, callback: (confirmationFlow: ConfirmationFlow) => void): void
    }
};

interface ConfirmationFlow {
    success(): void;
    error(): void;
}

const XHR_SUCCESS_CODE = 200;

export default class AmazonSubmitConfirmationButton extends Component {
    protected button: HTMLButtonElement;
    protected xhr: XMLHttpRequest;

    constructor() {
        super();
        this.xhr = new XMLHttpRequest();
    }

    protected readyCallback(): void {}

    mountCallback(): void {
        this.button = this.querySelector(`.${this.jsName}__button`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.button.addEventListener('click', this.onButtonClick.bind(this));
    }

    protected placeOrder<T = string>(confirmationFlow: ConfirmationFlow): void {
        new Promise<T>((resolve, reject) => {
            this.xhr.open('POST', this.url);
            this.xhr.addEventListener('load', (event: Event) => this.onRequestLoad(resolve, reject, confirmationFlow));
            this.xhr.addEventListener('error', (event: Event) => this.onRequestError(reject, confirmationFlow));
            this.xhr.send();
        });
    }

    protected onRequestLoad(resolve, reject, confirmationFlow: ConfirmationFlow): void {
        if (this.xhr.status === XHR_SUCCESS_CODE) {
            confirmationFlow.success();
            resolve(this.xhr.response);

            return;
        }

        this.onRequestError(reject, confirmationFlow);
    }

    protected onRequestError(reject, confirmationFlow: ConfirmationFlow): void {
        confirmationFlow.error();
        reject(new Error(`${this.url} request aborted with ${this.xhr.status}`));
        location.href = this.paymentFailedUrl;
    }

    protected onButtonClick(event: Event) {
        event.preventDefault();
        window.OffAmazonPayments.initConfirmationFlow(this.sellerId, this.orderReferenceId, confirmationFlow => {
            this.placeOrder(confirmationFlow);
        });

    }

    protected get url(): string {
        return this.getAttribute('url');
    }

    protected get sellerId(): string {
        return this.getAttribute('seller-id');
    }

    protected get orderReferenceId(): string {
        return this.getAttribute('order-reference-id');
    }

    protected get paymentFailedUrl(): string {
        return this.getAttribute('payment-failed-url');
    }

}
