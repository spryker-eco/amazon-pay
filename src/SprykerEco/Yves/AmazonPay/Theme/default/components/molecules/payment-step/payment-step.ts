import Component from 'ShopUi/models/component';
import AjaxProvider from "ShopUi/components/molecules/ajax-provider/ajax-provider";

interface IAmazonConfig {
    sellerId: string,
    orderReferenceUrl: string,
    shipmentMethodsUrl: string,
    updateShipmentMethodUrl: string,
    locale: string,
    orderReferenceId?: string,
    displayMode?: string
}
 
export default class PaymentStep extends Component {
    protected addressScopeConfig: string;
    protected walletScopeConfig: string;
    protected shipmentMethodsHolder: HTMLElement;
    protected shipmentMethods: Array<HTMLInputElement>;
    protected shipmentMethodsAjaxProvider: AjaxProvider;
    protected orderReferenceAjaxProvider: AjaxProvider;
    protected shipmentUpdateAjaxProvider: AjaxProvider;
    protected payConfig: IAmazonConfig;
    
    protected readyCallback(): void {
        this.addressScopeConfig = <string>this.addressScopeConfigAttribute;
        this.walletScopeConfig = <string>this.walletScopeConfigAttribute;
        this.shipmentMethodsHolder = <HTMLElement>document.querySelector(this.shipmentMethodsHolderAttribute);
        this.shipmentMethodsAjaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__shipment-methods-ajax-provider`);
        this.orderReferenceAjaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__order-reference-ajax-provider`);
        this.shipmentUpdateAjaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__shipment-update-ajax-provider`);
        this.payConfig = <IAmazonConfig> {
            sellerId: this.sellerIdAttribute,
            orderReferenceUrl: this.orderReferenceUrlAttribute,
            shipmentMethodsUrl: this.shipmentMethodsUrlAttribute,
            updateShipmentMethodUrl: this.updateShipmentMethodUrlAttribute,
            locale: this.localeAttribute,
            orderReferenceId: this.orderReferenceUrlAttribute,
            displayMode: this.displayModeAttribute
        };
        // this.shipmentMethodsAjaxProvider.xhr.addEventListener('error', () => console.log('error'));
        this.amazonLoginReady();
        this.amazonPaymentsReady();
    }
    
    protected mapEvents(): void {
        const checkedMethods = this.shipmentMethods.find(itemMethod => itemMethod.checked === true);
        if(checkedMethods === undefined) {
            this.shipmentMethods[0].setAttribute('checked', 'checked');
        }
        this.shipmentMethods.forEach(itemMethod => itemMethod.addEventListener('change', this.updatePayment.bind(itemMethod, this.shipmentUpdateAjaxProvider)));
    }

    protected amazonLoginReady(): void {
        (<any>window).onAmazonLoginReady = () => {
            (<any>window).amazon.Login.setClientId(this.payConfig.sellerId);
        };
    }

    protected amazonPaymentsReady(): void {
        const _this = this;
        (<any>window).onAmazonPaymentsReady = () => {
            new (<any>window).OffAmazonPayments.Widgets.AddressBook({
                sellerId: this.payConfig.sellerId,
                scope: this.addressScopeConfig,
                language: this.payConfig.locale,
                // amazonOrderReferenceId: this.payConfig.orderReferenceId,
                // displayMode: this.payConfig.displayMode,
                onOrderReferenceCreate(orderReference) {
                    const referenceId = orderReference.getAmazonOrderReferenceId();
                    // const data = {
                    //     'reference_id': referenceId
                    // }
                    _this.orderReference(`reference_id=${referenceId}`);
                    // (<any>window).jQuery(function() {
                    //     (<any>window).jQuery.post(
                    //         _this.payConfig.orderReferenceUrl,
                    //         {'reference_id': referenceId}
                    //     );
                    // });
                },
                onAddressSelect(orderReference) {
                    _this.getShipmentMethods(_this.shipmentMethodsHolder);
                    // (<any>window).jQuery(function() {
                    //     (<any>window).jQuery('#amazonpayPlaceOrderLink').addClass('invisible');
                    //     var shipmentMethodsBlock = (<any>window).jQuery('#shipmentMethods');
                    //     shipmentMethodsBlock.html('Please wait...');
                    //     shipmentMethodsBlock.load(
                    //         shipmentMethodsUrl,
                    //         function( response, status, xhr ) {
                    //             console.log(response);
                    //             if ( status === "error" ) {
                    //                 var msg = "Sorry but there was an error: ";
                    //                 (<any>window).jQuery( "#shipment_methods" ).html( msg + xhr.status + " " + xhr.statusText );
                    //             }
                    //         }
                    //     );
                    // });
                },
                design: {
                    designMode: 'responsive'
                }
            }).bind(`${this.jsName}__address-item`);

            new (<any>window).OffAmazonPayments.Widgets.Wallet({
                sellerId: this.payConfig.sellerId,
                scope: this.walletScopeConfig,
                // amazonOrderReferenceId: this.payConfig.orderReferenceId,
                design: {
                    designMode: 'responsive'
                },
            }).bind(`${this.jsName}__wallet-item`);
        }
    }

    protected async getShipmentMethods(selector: HTMLElement): Promise<void> {
        selector.innerHTML = await this.shipmentMethodsAjaxProvider.fetch();
        this.shipmentMethods = <HTMLInputElement[]>Array.from(document.getElementsByName(`${this.nameShipmentMethodAttribute}`));
        this.mapEvents();
    }

    protected async orderReference(data: Object): Promise<void> {
        await this.orderReferenceAjaxProvider.fetch(data);
    }
    
    protected async updatePayment(shipmentUpdateAjaxProvider: AjaxProvider): Promise<void> {
        const shipmentId = this.getAttribute('value');
        const data = `shipment_method_id=${shipmentId}`;
        const hol = document.getElementById('amazonpaySummeryInformation');
        const response = await shipmentUpdateAjaxProvider.fetch(data);
        hol.innerHTML = response;
    }

    get sellerIdAttribute(): string {
        return this.getAttribute('sellerId');
    }

    get orderReferenceUrlAttribute(): string {
        return this.getAttribute('orderReferenceUrl');
    }

    get shipmentMethodsUrlAttribute(): string {
        return this.getAttribute('shipmentMethodsUrl');
    }

    get updateShipmentMethodUrlAttribute(): string {
        return this.getAttribute('updateShipmentMethodUrl');
    }

    get localeAttribute(): string {
        return this.getAttribute('locale');
    }

    get orderReferenceIdAttribute(): string {
        return this.getAttribute('orderReferenceId');
    }

    get addressScopeConfigAttribute(): string {
        return this.getAttribute('addressScope');
    }

    get walletScopeConfigAttribute(): string {
        return this.getAttribute('walletScope');
    }

    get shipmentMethodsHolderAttribute(): string {
        return this.getAttribute('shipmentMethodsHolder');
    }

    get displayModeAttribute(): string {
        return this.getAttribute('displayMode');
    }

    get nameShipmentMethodAttribute(): string {
        return this.getAttribute('nameShipmentMethod');
    }
}
