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
const XHR_REDIRECT_CODE = 302;

export default class AmazonOrderSubmitter extends Component {
    protected button: HTMLButtonElement;
    protected xhr: XMLHttpRequest;

    constructor() {
        super();
        this.xhr = new XMLHttpRequest();
    }

    protected readyCallback(): void {}

    protected init(): void {
        this.button = <HTMLButtonElement>this.getElementsByClassName(`${this.jsName}__button`)[0];
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.button.addEventListener('click', this.onButtonClick.bind(this));
    }

    protected placeOrder<T = string>(confirmationFlow: ConfirmationFlow): void {
        new Promise<T>((resolve: Function, reject: Function) => {
            this.xhr.open('POST', this.url);
            this.xhr.addEventListener('load', (event: Event) => this.onRequestLoad(resolve, reject, confirmationFlow));
            this.xhr.addEventListener('error', (event: Event) => this.onRequestError(reject, confirmationFlow));
            this.xhr.send();
        });
    }

    protected onRequestLoad(resolve: Function, reject: Function, confirmationFlow: ConfirmationFlow): void {
        if (this.xhr.status === XHR_SUCCESS_CODE) {
            confirmationFlow.success();
            resolve(this.xhr.response);

            return;
        }

        if (this.xhr.status === XHR_REDIRECT_CODE) {
            resolve(this.xhr.response);
            location.href = JSON.parse(this.xhr.response).url;

            return;
        }

        this.onRequestError(reject, confirmationFlow);
    }

    protected onRequestError(reject: Function, confirmationFlow: ConfirmationFlow): void {
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
