import Component from 'ShopUi/models/component';
import AjaxProvider from "ShopUi/components/molecules/ajax-provider/ajax-provider";

declare const window: any;

interface IAmazonConfig {
    sellerId: string,
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
            window.amazon.Login.setClientId(this.payConfig.sellerId);
        };
    }

    protected amazonPaymentsReady(): void {
        const _this = this;
        window.onAmazonPaymentsReady = () => {
            new window.OffAmazonPayments.Widgets.AddressBook({
                sellerId: this.payConfig.sellerId,
                scope: this.addressScopeConfig,
                language: this.payConfig.locale,
                amazonOrderReferenceId: this.payConfig.orderReferenceId,
                displayMode: this.payConfig.displayMode,
                onOrderReferenceCreate(orderReference) {
                    const referenceId = orderReference.getAmazonOrderReferenceId();
                    const formData = new FormData();
                    formData.append('reference_id', referenceId);
                    _this.orderReference(formData);
                },
                onAddressSelect(orderReference) {
                    _this.getShipmentMethods(_this.shipmentMethodsHolder);
                },
                design: {
                    designMode: 'responsive'
                }
            }).bind(`${this.jsName}__address-item`);

            new window.OffAmazonPayments.Widgets.Wallet({
                sellerId: this.payConfig.sellerId,
                scope: this.walletScopeConfig,
                amazonOrderReferenceId: this.payConfig.orderReferenceId,
                design: {
                    designMode: 'responsive'
                },
            }).bind(`${this.jsName}__wallet-item`);
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
