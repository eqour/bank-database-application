$(window).ready(function () {
    updateOpenPurposeInput();
    $('[name=product-type]').on('input', updateOpenPurposeInput);
    $('#root').removeAttr('hidden');
    $('[name=product-type]').focus();
});

function updateOpenPurposeInput() {
    const selectedProductType = $('[name=product-type] option:selected').attr('data-product');
    if (selectedProductType === 'deposit') {
        $('[name=open-purpose]').attr('disabled', '');
        $('[name=open-purpose]').val('');
    } else {
        $('[name=open-purpose]').removeAttr('disabled');
    }
}
