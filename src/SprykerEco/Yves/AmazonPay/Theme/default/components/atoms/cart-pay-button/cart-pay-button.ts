import Component from 'ShopUi/models/component';

declare const window: any;

interface IAmazonConfig {
    clientId: string,
    sellerId: string,
    logout: string,
    redirectUrl: string,
    buttonType: string,
    buttonColor: string,
    buttonSize: string,
    locale: string,
}

export default class CartPayButton extends Component {
    protected payConfig: IAmazonConfig;

    protected readyCallback(): void {
        this.payConfig = <IAmazonConfig> {
             clientId: this.clientId,
             sellerId: this.sellerId,
             logout: this.logout,
             redirectUrl: this.redirectUrl,
             buttonType: this.buttonType,
             buttonColor: this.buttonColor,
             buttonSize: this.buttonSize,
             locale: this.locale
        };
        this.windowErrorHandler();
        this.amazonLoginReady();
        this.amazonPaymentsReady(this.loginScopeOptions, this.loginPopupOptions, this.payConfig.redirectUrl);

    }

    protected amazonLoginReady(): void {
        window.onAmazonLoginReady = () => {
            if (this.payConfig.logout) {
                window.amazon.Login.logout();
            }
            window.amazon.Login.setClientId(this.payConfig.clientId);
        };
    }

    protected amazonPaymentsReady(loginScopeOptions: string, loginPopupOptions: string, redirectUrl: string): void {
        let accessToken;
        
        window.onAmazonPaymentsReady = () => {
            window.OffAmazonPayments.Button(`${this.jsName}__item`, this.payConfig.sellerId, {
                type: this.payConfig.buttonType,
                color: this.payConfig.buttonColor,
                size: this.payConfig.buttonSize,
                language: this.payConfig.locale,
                authorization() {
                    const loginOptions = {
                        scope: loginScopeOptions,
                        popup: loginPopupOptions
                    };
                    window.amazon.Login.authorize(loginOptions, response => accessToken = response.access_token);
                },
                onSignIn(orderReference) {
                    const referenceId = orderReference.getAmazonOrderReferenceId();

                    if (!referenceId) {
                        window.errorHandler(new Error('referenceId missing'));
                        return;
                    }
                    window.location = `${redirectUrl}?reference_id=${referenceId}&access_token=${accessToken}`;
                }
            })
        }
    }

    protected windowErrorHandler(): void {
        if (typeof window.errorHandler !== 'function') {
            window.errorHandler = message => {
                alert(message);
            };
        }
    }

    get clientId(): string {
        return this.getAttribute('clientId');
    }

    get sellerId(): string {
        return this.getAttribute('sellerId');
    }

    get logout(): string {
        return this.getAttribute('logout');
    }

    get redirectUrl(): string {
        return this.getAttribute('redirectUrl');
    }

    get buttonType(): string {
        return this.getAttribute('buttonType');
    }

    get buttonColor(): string {
        return this.getAttribute('buttonColor');
    }

    get buttonSize(): string {
        return this.getAttribute('buttonSize');
    }

    get locale(): string {
        return this.getAttribute('locale');
    }

    get loginScopeOptions(): string {
        return this.getAttribute('loginScopeOptions');
    }

    get loginPopupOptions(): string {
        return this.getAttribute('loginPopupOptions');
    }
}
