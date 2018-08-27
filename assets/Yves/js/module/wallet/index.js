window.onAmazonLoginReady = function() {
    amazon.Login.setClientId(amazonpayConfig.clientId);
};

window.onAmazonPaymentsReady = function() {
    new OffAmazonPayments.Widgets.Wallet({
        sellerId: amazonpayConfig.sellerId,
        scope: 'profile postal_code payments:widget payments:shipping_address',
        onPaymentSelect: function(orderReference) {
            $('#amazonpayPlaceOrderLink').attr('disabled', false);
        },
        design: {
            designMode: 'responsive'
        },
        onError: function(error) {
        }
    }).bind("amazonpayWalletWidgetContainer");
};
