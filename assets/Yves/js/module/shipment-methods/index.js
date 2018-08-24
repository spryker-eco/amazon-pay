$(function() {
    $('input[name=amazonpayShipmentMethod]').change(function () {
        $('#amazonpayPlaceOrderLink').attr('disabled', true);
        $('#amazonpaySummeryInformation').load(
            amazonpayConfig.updateShipmentMethodUrl,
            {'shipment_method_id': $(this).val()},
            function() {
                activatePlaceOrderButton();
            }
        );
    });

    if (!selectDefaultShipmentMethod()) {
        activatePlaceOrderButton();
    }
});

function selectDefaultShipmentMethod() {
    if ($('input[name=amazonpayShipmentMethod]:checked').length === 0) {
        $('input[name=amazonpayShipmentMethod]:first').click();

        return true;
    }

    return false;
}

function activatePlaceOrderButton() {
    $('#amazonpayPlaceOrderLink').attr('disabled', false);
}
