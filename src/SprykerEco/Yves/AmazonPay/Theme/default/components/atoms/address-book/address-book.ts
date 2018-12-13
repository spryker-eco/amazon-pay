import Component from 'ShopUi/models/component';

interface IAmazonConfig {
    clientId: string,
    sellerId: string,
    orderReferenceUrl: string,
    shipmentMethodsUrl: string,
    locale: string,
    addressBookMode?: string,
    orderReferenceId?: string
}
 
export default class AddressBook extends Component {
    protected payConfig: IAmazonConfig;

    protected readyCallback(): void {
        this.payConfig = <IAmazonConfig> {
            clientId: this.clientIdAttribute,
            sellerId: this.sellerIdAttribute,
            orderReferenceUrl: this.orderReferenceUrlAttribute,
            shipmentMethodsUrl: this.shipmentMethodsUrlAttribute,
            locale: this.localeAttribute,
            addressBookMode: this.addressBookModeAttribute,
            orderReferenceId: this.orderReferenceUrlAttribute
        };
        this.amazonLoginReady();
        this.amazonPaymentsReady(this.payConfig.orderReferenceUrl, this.payConfig.shipmentMethodsUrl);
    }

    protected amazonLoginReady(): void {
        (<any>window).onAmazonLoginReady = () => {
            (<any>window).amazon.Login.setClientId("A36VZZYZOVN3S6");
        };
    }

    protected amazonPaymentsReady(orderReferenceUrl: string, shipmentMethodsUrl: string): void {
        (<any>window).onAmazonPaymentsReady = () => {
            console.log('amazon ready');
            (<any>window).OffAmazonPayments.Widgets.AddressBook =
            new (<any>window).OffAmazonPayments.Widgets.AddressBook({
                sellerId: "A36VZZYZOVN3S6",
                scope: 'profile payments:widget payments:shipping_address payments:billing_address',
                language: "en_US",
                // onOrderReferenceCreate(orderReference) {
                //     const referenceId = orderReference.getAmazonOrderReferenceId();
                //     (<any>window).jQuery(function() {
                //         (<any>window).jQuery.post(
                //             "/amazonpay/set-order-reference",
                //             {'reference_id': referenceId}
                //         );
                //     });
                // },
                onAddressSelect(orderReference) {
                    (<any>window).jQuery(function() {
                        (<any>window).jQuery('#amazonpayPlaceOrderLink').addClass('invisible');
                        var shipmentMethodsBlock = (<any>window).jQuery('#shipmentMethods');
                        shipmentMethodsBlock.html('Please wait...');
                        shipmentMethodsBlock.load(
                            "/en/amazonpay/get-shipment-methods",
                            function( response, status, xhr ) {
                                console.log(response);
                                if ( status === "error" ) {
                                    var msg = "Sorry but there was an error: ";
                                    (<any>window).jQuery( "#shipment_methods" ).html( msg + xhr.status + " " + xhr.statusText );
                                }
                            }
                        );
                    });
                },
                design: {
                    designMode: 'responsive'
                },
                onReady(orderReference) {
                    console.log('ready');
                },
                onError(error) {
                }
            }).bind('amazonpayAddressBookWidgetContainer');
        }
    }

    get clientIdAttribute(): string {
        return this.getAttribute('clientId');
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

    get localeAttribute(): string {
        return this.getAttribute('locale');
    }

    get addressBookModeAttribute(): string {
        return this.getAttribute('addressBookMode');
    }

    get orderReferenceIdAttribute(): string {
        return this.getAttribute('orderReferenceId');
    }
}
