$(window).ready(function () {
    updateStatusInput();
    $('[name=account-number]').on('input', updateStatusInput);
});

function updateStatusInput() {
    const isEmpty = $('[name=account-number]').val() === '';
    if (isEmpty) {
        $('[name=status]').removeAttr('disabled');
    } else {
        $('[name=status]').attr('disabled', '');
        $('[name=status]').prop('selectedIndex', 0);
    }
}
