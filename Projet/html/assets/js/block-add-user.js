$('#galerie-conf').change(function () {
    if ($('#galerie-conf').val() == '3') {
        $('#block-add-user').css('display', 'block');
    } else {
        $('#block-add-user').css('display', 'none');
    }
})