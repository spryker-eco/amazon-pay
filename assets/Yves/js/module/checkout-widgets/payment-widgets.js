'use strict';

window.onAmazonPaymentsReady = function() {
    require('./address-book');
    require('./wallet');
};
