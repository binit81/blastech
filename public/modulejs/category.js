
$(document).on('submit','#categoryform',function(e){

    e.preventDefault();
    /*if(validate_categoryform('categoryform'))
    {*/
        var category_name = $("#category_name").val();
        var is_active = $("#category_status").val();
        var csrf_token = $('#csrf_token').val();

        $.post("{{ route('category_create') }}",
            {_token:csrf_token,category_name:category_name,is_active:is_active},function(data)
            {
            if (data['error'] == true) {
                alert(data['message']);
            } else {
                $('#experience_create').trigger('reset');
                $('#experience_add').modal('hide');
                alert(data);
                experiences();
            }
        });

});



/*
function validate_categoryform(frmid)
{
    var error = 0;


    if($("#category_name").val() == '')
    {
        error = 1;
        $("#category_name").addClass('invalid');
        $("#categoryformerr").html('Please Enter Category Name!');
        return false;
    }


    if(error == 1)
    {
        $("#categoryformerr").show();
        return false;
    }
    else
    {
        return true;
    }

}*/
