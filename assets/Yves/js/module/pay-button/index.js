window.onAmazonLoginReady = function() {
    if (amazonpayConfig.logout) {
        amazon.Login.logout();
    }

    amazon.Login.setClientId(amazonpayConfig.clientId);
};

window.onAmazonPaymentsReady = function() {
    var addressConsentToken;

    // render the button here
    OffAmazonPayments.Button('AmazonPayButton', amazonpayConfig.sellerId, {
        type:  amazonpayConfig.buttonType,
        color: amazonpayConfig.buttonColor,
        size:  amazonpayConfig.buttonSize,
        language: amazonpayConfig.locale,

        authorization: function() {
            var loginOptions = {
                scope: 'profile postal_code payments:widget payments:shipping_address payments:billing_address',
                popup: 'true'
            };

            amazon.Login.authorize(loginOptions, function(response) {
                addressConsentToken = response.access_token;
            });
        },

        onSignIn: function (orderReference) {
            var referenceId = orderReference.getAmazonOrderReferenceId();

            if (!referenceId) {
                errorHandler(new Error('referenceId missing'));

                return;
            }

            window.location = amazonpayConfig.redirectUrl + '?'
                + 'reference_id=' + referenceId
                + "&access_token=" + addressConsentToken;
        }
    });
};

if (typeof window.errorHandler !== 'function') {
    window.errorHandler = function (message) {
        alert(message);
    };
}
