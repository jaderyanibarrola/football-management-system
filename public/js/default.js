var n = '';
$(document).ready(function () {
    $.noConflict();
    //show image preview of a file upload
    $('.show-preview').change(function () {

        let reader = new FileReader();
        reader.onload = (e) => {
            if (e.target.result != 'undefined') {
                $(this).parents().find('#image-preview img').prop('src', e.target.result);
                $(this).parents().find('#image-preview').removeClass('d-none');
            }
        }
        reader.readAsDataURL(this.files[0]);

    });

    //add datatable plugin
    if ($('#dt').length > 0) {
        $('#dt').DataTable({
            buttons: [
                'copy', 'excel', 'pdf'
            ]
        });
    }
    
    //edit action of form
    $(document).on('click', '.edit-action', function () {
        var id = $(this).data('id');
        var type = $(this).data('type');
        n = $(this).data('name');
        var data = getData(id, type);
    });

    //delete action of form
    $(document).on('click', '.delete-action', function (e) {
        var form = $(this).closest('form');
        e.preventDefault();
        $('#delete').modal()
        .on('click', '#deleteBtn', function(e) {
            form.submit();
        });
        $("#cancel").on('click',function(e){
            e.preventDefault();
            $('#delete').modal.model('hide');
        });
    });

});

function getData(id, type) {
    var callback = '';
    switch(type){
        case 'team':
            url = base_url+'/team/'+id;
            callback = teamEditData;
            break;
        case 'player':
            url = base_url+'/player/'+id;
            callback = playerEditData;
            break;
        case 'lineup':
            url = base_url+'/lineup/'+id;
            callback = lineupEditData;
            break;
    }
    $.ajax({
        type: "GET",
        url: url,
        dataType: 'json',
        success: callback
    });
}

function teamEditData(data){
	$('#edit').find('input[name="id"]').val(data.response.id);
	$('#edit').find('input[name="name"]').val(data.response.name);
	$('#edit').find('input[name="wins"]').val(data.response.wins);
	$('#edit').find('input[name="losses"]').val(data.response.losses);
	$('#edit').find('input[name="active"][value="'+data.response.active+'"]').prop('checked', true);
}

function playerEditData(data){
	$('#edit').find('input[name="id"]').val(data.response.id);
	$('#edit').find('input[name="first_name"]').val(data.response.first_name);
	$('#edit').find('input[name="last_name"]').val(data.response.last_name);
	$('#edit').find('input[name="age"]').val(data.response.age);
	$('#edit').find('input[name="weight"]').val(data.response.weight);
	$('#edit').find('input[name="height"]').val(data.response.height);
	$('#edit').find('input[name="active"][value="'+data.response.active+'"]').prop('checked', true);
}

function lineupEditData(data){
	$('#edit').find('input[name="id"]').val(data.response.id);
    $('#teamName').html(n);
}