$(window).ready(function () {
    updateOpenPurposeInput();
    $('[name="BankingProductRegistrationForm\[type\]"]').on('input', updateOpenPurposeInput);
    $('#root').removeAttr('hidden');
    $('[name="BankingProductRegistrationForm\[type\]"]').focus();
});

function updateOpenPurposeInput() {
    const selectedProductType = $('[name="BankingProductRegistrationForm\[type\]"] option:selected').attr('data-product');
    if (selectedProductType === 'deposit') {
        $('[name="BankingProductRegistrationForm\[purpose\]"]').attr('disabled', '');
        $('[name="BankingProductRegistrationForm\[purpose\]"]').val('');
    } else {
        $('[name="BankingProductRegistrationForm\[purpose\]"]').removeAttr('disabled');
    }
}
