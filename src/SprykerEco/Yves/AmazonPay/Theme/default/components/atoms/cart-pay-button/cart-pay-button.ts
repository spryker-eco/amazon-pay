import Component from 'ShopUi/models/component';

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
    protected loginScopeOptions: string;
    protected loginPopupOptions: string;
    protected payConfig: IAmazonConfig;
    
    protected readyCallback(): void {
        this.loginScopeOptions = <string>this.loginScopeOptionsAttribute;
        this.loginPopupOptions = <string>this.loginPopupOptionsAttribute;
        this.payConfig = <IAmazonConfig> {
            clientId: this.clientIdAttribute,
            sellerId: this.sellerIdAttribute,
            logout: this.logoutAttribute,
            redirectUrl: this.redirectUrlAttribute,
            buttonType: this.buttonTypeAttribute,
            buttonColor: this.buttonColorAttribute,
            buttonSize: this.buttonSizeAttribute,
            locale: this.localeAttribute
        };
        this.windowErrorHandler();
        this.amazonLoginReady();
        this.amazonPaymentsReady(this.loginScopeOptions, this.loginPopupOptions, this.payConfig.redirectUrl);

    }

    protected amazonLoginReady(): void {
        (<any>window).onAmazonLoginReady = () => {
            if (this.payConfig.logout) {
                (<any>window).amazon.Login.logout();
            }
            (<any>window).amazon.Login.setClientId(this.payConfig.clientId);
        };
    }

    protected amazonPaymentsReady(loginScopeOptions: string, loginPopupOptions: string, redirectUrl: string): void {
        let addressConsentToken;
        
        (<any>window).onAmazonPaymentsReady = () => {
            (<any>window).OffAmazonPayments.Button(`${this.jsName}__item`, this.payConfig.sellerId, {
                type: this.payConfig.buttonType,
                color: this.payConfig.buttonColor,
                size: this.payConfig.buttonSize,
                language: this.payConfig.locale,
                authorization() {
                    const loginOptions = {
                        scope: loginScopeOptions,
                        popup: loginPopupOptions
                    };
                    (<any>window).amazon.Login.authorize(loginOptions, response => addressConsentToken = response.access_token);
                },
                onSignIn(orderReference) {
                    const referenceId = orderReference.getAmazonOrderReferenceId();

                    if (!referenceId) {
                        (<any>window).errorHandler(new Error('referenceId missing'));
                        return;
                    }
                    (<any>window).location = redirectUrl + '?'
                        + 'reference_id=' + referenceId
                        + "&access_token=" + addressConsentToken;
                }
            })
        }
    }

    protected windowErrorHandler(): void {
        if (typeof (<any>window).errorHandler !== 'function') {
            (<any>window).errorHandler = message => {
                alert(message);
            };
        }
    }

    get clientIdAttribute(): string {
        return this.getAttribute('clientId');
    }

    get sellerIdAttribute(): string {
        return this.getAttribute('sellerId');
    }

    get logoutAttribute(): string {
        return this.getAttribute('logout');
    }

    get redirectUrlAttribute(): string {
        return this.getAttribute('redirectUrl');
    }

    get buttonTypeAttribute(): string {
        return this.getAttribute('buttonType');
    }

    get buttonColorAttribute(): string {
        return this.getAttribute('buttonColor');
    }

    get buttonSizeAttribute(): string {
        return this.getAttribute('buttonSize');
    }

    get localeAttribute(): string {
        return this.getAttribute('locale');
    }

    get loginScopeOptionsAttribute(): string {
        return this.getAttribute('loginScopeOptions');
    }

    get loginPopupOptionsAttribute(): string {
        return this.getAttribute('loginPopupOptions');
    }
}
