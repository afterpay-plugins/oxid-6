$(document).ready(function () {
    $('#checkAfterPayAgbTop').change(function () {
        $('form #clonedAfterPayAgbCheckbox').remove();
        $('#checkAfterPayAgbTop').clone().attr('id', 'clonedAfterPayAgbCheckbox').hide().appendTo("#orderConfirmAgbBottom");
        $('#checkAfterPayAgbTop').clone().attr('id', 'clonedAfterPayAgbCheckbox').hide().appendTo("#orderConfirmAgbTop");
    });
});

