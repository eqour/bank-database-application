$(window).ready(function () {
    updateStatusInput();
    $('[name="BankingProductFilterForm\[accountNumber\]"]').on('input', updateStatusInput);
});

function updateStatusInput() {
    const isEmpty = $('[name="BankingProductFilterForm\[accountNumber\]"]').val() === '';
    if (isEmpty) {
        $('[name="BankingProductFilterForm\[status\]"]').removeAttr('disabled');
    } else {
        $('[name="BankingProductFilterForm\[status\]"]').attr('disabled', '');
        $('[name="BankingProductFilterForm\[status\]"]').prop('selectedIndex', 0);
    }
}
