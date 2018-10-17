$(function() {
    $('input[name=amazonpayShipmentMethod]').change(function () {
        $('#amazonpayPlaceOrderLink').addClass('invisible');

        $('#amazonpaySummeryInformation').load(
            amazonpayConfig.updateShipmentMethodUrl,
            {'shipment_method_id': $(this).val()},
            function() {
                $('#amazonpayPlaceOrderLink').removeClass('invisible');
            }
        );
    });

    if (!selectDefaultShipmentMethod()) {
        $('#amazonpayPlaceOrderLink').removeClass('invisible');
    }
});

function selectDefaultShipmentMethod() {
    if ($('input[name=amazonpayShipmentMethod]:checked').length === 0) {
        $('input[name=amazonpayShipmentMethod]:first').click();

        return true;
    }

    return false;
}
