new OffAmazonPayments.Widgets.Wallet({
    sellerId: amazonpayConfig.sellerId,
    scope: 'profile postal_code payments:widget payments:shipping_address',
    onPaymentSelect: function(orderReference) {
        // Replace this code with the action that you want to perform
        // after the payment method is selected.

        // Ideally this would enable the next action for the buyer
        // including either a "Continue" or "Place Order" button.
    },
    design: {
        designMode: 'responsive'
    },
    onError: function(error) {
    }
}).bind("amazonpayWalletWidgetDiv");
