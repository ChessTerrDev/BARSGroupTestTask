
var ajaxPath = 'ajaxCRUDJson.php';

/**
 * Удаление записи
 */
var deletedModal = document.getElementById('ModalDeleted');
deletedModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var recipient = button.getAttribute('data-bs-id');
    var modalBodyInput = deletedModal.querySelector('.modal-form input')
    modalBodyInput.value = recipient;
});

$('#ButtonDeleted').click(function (){
    var inputHiddenVal = $(this).parent().parent().find('input[name="delete"]').val();
    var token = $("#authToken").val();
    $.ajax({
        url: ajaxPath,
        method: 'POST',
        dataType: 'json',
        headers: {'Token': token},
        data: {'delete': inputHiddenVal},
        success: function(data){
            console.log(data);
            $('#id_'+inputHiddenVal).remove();
            $('#ModalDeleted').modal('hide');

            if(data.status === false && data.error)
            {
                var myModal = new bootstrap.Modal($('#ModalErrorMessage'));
                $('#ModalErrorMessageText').html(data.error)
                myModal.show();
            }
        }
    });


});

/**
 * Реактирование записи
 */
$('#ModalEdit').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);

    $(this).find('input[name="id"]').val(button.data('id'));
    $(this).find('input[name="hid"]').val(button.data('hid'));
    $(this).find('input[name="full_name"]').val(button.data('full_name'));
    $(this).find('input[name="address"]').val(button.data('address'));
    $(this).find('input[name="phone"]').val(button.data('phone'));
});
$('#ButtonEdit').click(function (){
    var token = $("#authToken").val();
    $.ajax({
        url: ajaxPath,
        method: 'POST',
        dataType: 'json',
        headers: {'Token': token},
        data: $('#ModalEditForm').serialize(),
        success: function(data){
            console.log(data.data);
            if (data.status && data.data) {
                var tr = $('#id_'+data.data.id);
                $(tr).find('.id').html(data.data.id);
                $(tr).find('.hid').html(data.data.hid);
                $(tr).find('.full_name').html(data.data.full_name);
                $(tr).find('.address').html(data.data.address);
                $(tr).find('.phone').html(data.data.phone);

            } else if(data.status === false && data.error) {

                var myModal = new bootstrap.Modal($('#ModalErrorMessage'));
                $('#ModalErrorMessageText').html(data.error)
                myModal.show();
            }
        }
    });
    $('#ModalEdit').modal('hide');
});


/**
 * Дабавлние записи
 */
$('#ButtonCreate').click(function (){
    var token = $("#authToken").val();
    $.ajax({
        url: ajaxPath,
        method: 'POST',
        dataType: 'json',
        headers: {'Token': token},
        data: $('#ModalCreateForm').serialize(),
        success: function(data){
            console.log(data);
            if (data.status && data.data) {
                var trHTML = '<tr id="id_' + data.data.id + '">\n' +
                    '<td class="id">' + data.data.id + '</td>\n' +
                    '<td class="hid">' + data.data.hid + '</td>\n' +
                    '<td class="full_name">' + data.data.full_name + '</td>\n' +
                    '<td class="address">' + data.data.address + '</td>\n' +
                    '<td class="phone">' + data.data.phone + '</td>\n' +
                    '<td>\n' +
                    '    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="' + data.data.id + '" data-hid="' + data.data.hid + '" data-full_name="' + data.data.full_name + '"  data-address="' + data.data.address + '"  data-phone="' + data.data.phone + '">\n' +
                    '        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">\n' +
                    '            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"></path>\n' +
                    '        </svg>\n' +
                    '    </button>\n' +
                    '</td>\n' +
                    '<td>\n' +
                    '    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#ModalDeleted" data-bs-id="' + data.data.id + '">\n' +
                    '        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">\n' +
                    '            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>\n' +
                    '            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>\n' +
                    '        </svg>\n' +
                    '    </button>\n' +
                    '</td>\n' +
                    '</tr>';
                $('#listLPUtbody').prepend(trHTML);

            } else if(data.status === false && data.error) {

                var myModal = new bootstrap.Modal($('#ModalErrorMessage'));
                $('#ModalErrorMessageText').html(data.error)
                myModal.show();
            }

        }
    });
    $('#ModalCreate').modal('hide');
});