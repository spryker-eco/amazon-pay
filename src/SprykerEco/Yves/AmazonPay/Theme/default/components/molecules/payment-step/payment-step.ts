import Component from 'ShopUi/models/component';
import AjaxProvider from "ShopUi/components/molecules/ajax-provider/ajax-provider";

declare const window: any;

interface IAmazonConfig {
    sellerId: string,
    clientId: string,
    orderReferenceUrl: string,
    shipmentMethodsUrl: string,
    updateShipmentMethodUrl: string,
    locale: string,
    orderReferenceId?: string | undefined,
    displayMode?: string | undefined
}
 
export default class PaymentStep extends Component {
    protected shipmentMethodsHolder: HTMLElement;
    protected summaryInfoHolder: HTMLElement;
    protected shipmentMethods: Array<HTMLInputElement>;
    protected shipmentMethodsAjaxProvider: AjaxProvider;
    protected orderReferenceAjaxProvider: AjaxProvider;
    protected shipmentUpdateAjaxProvider: AjaxProvider;
    protected payConfig: IAmazonConfig;
    
    protected readyCallback(): void {
        this.shipmentMethodsHolder = <HTMLElement>document.querySelector(this.shipmentMethodsHolderAttribute);
        this.summaryInfoHolder = <HTMLElement>document.querySelector(this.summaryInfoHolderAttribute);
        this.shipmentMethodsAjaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__shipment-methods-ajax-provider`);
        this.orderReferenceAjaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__order-reference-ajax-provider`);
        this.shipmentUpdateAjaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__shipment-update-ajax-provider`);
        this.payConfig = <IAmazonConfig> {
             sellerId: this.sellerId,
             clientId: this.clientId,
             orderReferenceUrl: this.orderReferenceUrl,
             shipmentMethodsUrl: this.shipmentMethodsUrl,
             updateShipmentMethodUrl: this.updateShipmentMethodUrl,
             locale: this.locale,
             orderReferenceId: (this.orderReferenceId === "") ? undefined : this.orderReferenceId,
             displayMode: (this.displayMode === "") ? undefined : this.displayMode
        };
        this.amazonLoginReady();
        this.amazonPaymentsReady();
    }
    
    protected mapEvents(): void {
        this.shipmentMethods.forEach(itemMethod => itemMethod.addEventListener('change', this.updatePayment.bind(this, itemMethod.value)));
        const checkedMethods = this.shipmentMethods.find(itemMethod => itemMethod.checked === true);
        if(checkedMethods === undefined) {
            this.shipmentMethods[0].click();
        }
    }

    protected amazonLoginReady(): void {
        window.onAmazonLoginReady = () => {
            window.amazon.Login.setClientId(this.payConfig.clientId);
        };
    }

    protected amazonPaymentsReady(): void {
        const _this = this;
        window.onAmazonPaymentsReady = () => {
            new window.OffAmazonPayments.Widgets.AddressBook({
                sellerId: _this.payConfig.sellerId,
                scope: _this.addressScopeConfig,
                language: _this.payConfig.locale,
                amazonOrderReferenceId: _this.payConfig.orderReferenceId,
                displayMode: _this.payConfig.displayMode,
                onOrderReferenceCreate(orderReference) {
                    const referenceId = orderReference.getAmazonOrderReferenceId();
                    const formData = new FormData();
                    formData.append('reference_id', referenceId);
                    _this.orderReference(formData);
                },
                onAddressSelect(orderReference) {
                    _this.getShipmentMethods(_this.shipmentMethodsHolder);
                },
                onError: function (error) {
                    console.log('OffAmazonPayments.Widgets.AddressBook', error.getErrorCode(), error.getErrorMessage());
                },
                design: {
                    designMode: 'responsive'
                }
            }).bind(`${_this.jsName}__address-item`);

            new window.OffAmazonPayments.Widgets.Wallet({
                sellerId: _this.payConfig.sellerId,
                scope: _this.walletScopeConfig,
                amazonOrderReferenceId: _this.payConfig.orderReferenceId,
                design: {
                    designMode: 'responsive'
                },
            }).bind(`${_this.jsName}__wallet-item`);
        }
    }

    protected async getShipmentMethods(selector: HTMLElement): Promise<void> {
        selector.innerHTML = await this.shipmentMethodsAjaxProvider.fetch();
        this.shipmentMethods = <HTMLInputElement[]>Array.from(document.getElementsByName(`${this.nameShipmentMethod}`));
        this.mapEvents();
    }

    protected async orderReference(data: Object): Promise<void> {
        await this.orderReferenceAjaxProvider.fetch(data);
    }
    
    protected async updatePayment(selectedMethodValue: string): Promise<void> {
        const formData = new FormData();
        formData.append('shipment_method_id', selectedMethodValue);
        const response = await this.shipmentUpdateAjaxProvider.fetch(formData);
        this.summaryInfoHolder.innerHTML = response;
    }

    get clientId(): string {
        return this.getAttribute('clientId');
    }

    get sellerId(): string {
        return this.getAttribute('sellerId');
    }

    get orderReferenceUrl(): string {
        return this.getAttribute('orderReferenceUrl');
    }

    get shipmentMethodsUrl(): string {
        return this.getAttribute('shipmentMethodsUrl');
    }

    get updateShipmentMethodUrl(): string {
        return this.getAttribute('updateShipmentMethodUrl');
    }

    get locale(): string {
        return this.getAttribute('locale');
    }

    get orderReferenceId(): string {
        return this.getAttribute('orderReferenceId');
    }

    get addressScopeConfig(): string {
        return this.getAttribute('addressScope');
    }

    get walletScopeConfig(): string {
        return this.getAttribute('walletScope');
    }

    get shipmentMethodsHolderAttribute(): string {
        return this.getAttribute('shipmentMethodsHolder');
    }
    
    get summaryInfoHolderAttribute(): string {
        return this.getAttribute('summaryInfoHolder');
    }

    get displayMode(): string {
        return this.getAttribute('displayMode');
    }

    get nameShipmentMethod(): string {
        return this.getAttribute('nameShipmentMethod');
    }
}
