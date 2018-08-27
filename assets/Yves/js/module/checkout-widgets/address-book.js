new OffAmazonPayments.Widgets.AddressBook({
    sellerId: amazonpayConfig.sellerId,
    scope: 'profile payments:widget payments:shipping_address payments:billing_address',
    language: amazonpayConfig.locale,
    onOrderReferenceCreate: function(orderReference) {
        // Here is where you can grab the Order Reference ID.
        var aoid = orderReference.getAmazonOrderReferenceId();

        $(function() {
            $.post(
                amazonpayConfig.setOrderReferenceUrl,
                {'reference_id': aoid}
            );
        });
    },
    onAddressSelect: function(orderReference) {
        $(function() {
            $('#amazonpayPlaceOrderLink').attr('disabled', true);
            var shipmentMethodsBlock = $('#shipmentMethods');
            shipmentMethodsBlock.html('Please wait...');
            shipmentMethodsBlock.load(
                amazonpayConfig.getShipmentMethodsUrl,
                function( response, status, xhr ) {
                    if ( status === "error" ) {
                        var msg = "Sorry but there was an error: ";
                        $( "#shipment_methods" ).html( msg + xhr.status + " " + xhr.statusText );
                    }
                }
            );
        });
    },
    design: {
        designMode: 'responsive'
    },
    onReady: function(orderReference) {
        // Enter code here you want to be executed
        // when the address widget has been rendered.
    },
    onError: function(error) {
    }
}).bind("amazonpayAddressBookWidgetContainer");
