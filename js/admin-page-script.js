/**
 * Created by User on 7/31/2017.
 */

$(document).ready(function () {
    $('#customers_table').DataTable({
        'paging': true,
        'columns': [
            {'width':'5%'},
            null,
            null,
            {'width':'15%'},
            {'width':'5%'}
        ],
        'autoWidth': false,
        'lengthMenu': [[5, 20, 50, -1], [5, 20, 50, "All"]],
        'responsive': true,
        'scrollCollapse': true
    });

    $('#groups_table').DataTable({
        'paging': true,
        'columns': [
            {'width':'5%'},
            {'width':'15%'},
            null,
            {'width':'5%'}
        ],
        'autoWidth': false,
        'lengthMenu': [[5, 20, 50, -1], [5, 20, 50, "All"]],
        'responsive': true,
        'scrollCollapse': true
    });

    $('#campaign_table').DataTable({
        'paging': true,
        'columns': [
            {'width':'5%'},
            null,
            null,
            {'width':'5%'}
        ],
        'autoWidth': false,
        'lengthMenu': [[5, 20, 50, -1], [5, 20, 50, "All"]],
        'responsive': true,
        'scrollCollapse': true
    });

    $("#check_customer_form").on("click", function (e) {
        var err = false;
        if($('#customer_name').val().trim() == "")
        {
            $('#customer_name').addClass('z-form-error');
            err = true;
        }
        else
        {
            $('#customer_name').removeClass('z-form-error');
        }

        if($('#customer_phone').val().trim() == "")
        {
            $('#customer_phone').addClass('z-form-error');
            err = true;
        }
        else
        {
            $('#customer_phone').removeClass('z-form-error');
        }

        if(err)
        {
            e.stopPropagation();
        }
        else
        {
            //Bootstrap uploader
            $("#input-700").fileinput({

                uploadUrl: 'uploader', // server upload action
                allowedFileExtensions: ['mp3', 'wav'],
                uploadAsync: true,
                multiple: false,
                uploadExtraData: {type: 'customer', c_name: $('#customer_name').val(), c_phone: $('#customer_phone').val()},
                maxFileCount: 1,
                maxFilesNum: 1,
                slugCallback: function (filename) {

                    return filename.replace('_', '_');
                }
            });
        }

    });


    $("#check_campaign_form").on("click", function (e) {
        var err = false;
        if($('#campaign_name').val().trim() == "")
        {
            $('#campaign_name').addClass('z-form-error');
            err = true;
        }
        else
        {
            $('#campaign_name').removeClass('z-form-error');
        }


        if(err)
        {
            e.stopPropagation();
        }
        else
        {
            //Bootstrap uploader
            $("#input-800").fileinput({

                uploadUrl: 'uploader', // server upload action
                allowedFileExtensions: ['mp3', 'wav'],
                allowedFileTypes: ['audio'],
                uploadAsync: true,
                multiple: false,
                uploadExtraData: {type: 'campaign', c_name: $('#campaign_name').val()},
                maxFileCount: 1,
                maxFilesNum: 1,
                slugCallback: function (filename) {

                    return filename.replace('_', '_');
                }
            });
        }

    });

    $('#show-password').mouseenter(function ()
    {
        $('#password_sly').attr('type', 'text');
    });
    $('#show-password').mouseleave(function ()
    {
        $('#password_sly').attr('type', 'password');
    });


    $('#update_login_sly').click(function(){
        //update slybroadcast login
        var email = $('#email_sly').val();
        var password = $('#password_sly').val();
        var err = false;
        if(email.trim() == '')
        {
            err = true;
            $('#email_sly').addClass('z-form-error');
        }
        else
        {
            $('#email_sly').removeClass('z-form-error');
        }

        if(password.trim() == '')
        {
            err = true;
            $('#password_sly').addClass('z-form-error');
        }
        else
        {
            $('#password_sly').removeClass('z-form-error');
        }

        if(err) return;

        var formData = {
            email: email,
            password: password,
            is: 'update slybroadcast login'
        };

        $.ajax({
            url: '../ajax/admin-ajax',
            type: 'POST',
            data: formData,
            beforeSend: function()
            {
                $('#page-blur').removeClass('hidden')
            },
            success: function(data)
            {
                if(data ==  'ok') location.reload();
            }
        })

    });


    $('#add-group').click(function(){
        var name = $('#group_name').val();
        var list = $('#group_list').val();

        var err = false;
        if(name.trim() == '')
        {
            $('#group_name').addClass('z-form-error');
            err = true;
        }
        else
        {
            $('#group_name').removeClass('z-form-error');
        }
        if(list.trim() == '')
        {
            $('#group_list').addClass('z-form-error');
            err = true;
        }
        else
        {
            $('#group_list').removeClass('z-form-error');
        }
        if(err) return;

        var formData = {
            name: name,
            list: list,
            is: 'add-group'
        };

        $.ajax({
            url: '../ajax/admin-ajax',
            type: 'POST',
            data: formData,
            beforeSend: function()
            {
                $('#page-blur').removeClass('hidden')
            },
            success: function(data)
            {
                if(data ==  'ok') location.reload();
            }
        })
    });


    $('#add-phones').click(function(){
        var id = $('#audio_id').val();
        var phones = $('#phones').val();
        if(phones.trim() == "")
        {
            $('#phones').addClass('z-form-error');
            return false;
        }
        else
        {
            $.ajax({
                url: '../ajax/admin-ajax',
                type: 'POST',
                data: {id:id, phones:phones, is:'add_phones'},
                beforeSend: function()
                {
                    $('#add-phones-modal-blur').removeClass('hidden');
                },
                success: function(data){
                    if(data == 'ok')
                    {
                        location.reload();
                    }
                }
            })
        }

    });

    $('#add-phones-group').click(function(){
        var id = $('#group_id').val();
        var phones = $('#phones_group').val();
        if(phones.trim() == "")
        {
            $('#phones_group').addClass('z-form-error');
            return false;
        }
        else
        {
            $.ajax({
                url: '../ajax/admin-ajax',
                type: 'POST',
                data: {id:id, phones:phones, is:'add_phones_group'},
                beforeSend: function()
                {
                    $('#add-phones-group-modal-blur').removeClass('hidden');
                },
                success: function(data){
                    if(data == 'ok')
                    {
                        location.reload();
                    }
                }
            })
        }

    });

    $('#update_login_admin').click(function(){
        var email = $('#email_admin').val();
        var pass = $('#password_admin').val();

        if(email.trim() == "" || pass.trim() == "") return false;

        $.ajax({
            url: '../ajax/admin-ajax',
            type: 'POST',
            data: {email:email, pass:pass, is:'update_login_admin'},
            beforeSend: function()
            {
                $('#page-blur').removeClass('hidden');
            },
            success: function(data)
            {
                if(data == 'ok')
                {
                    location.reload();
                }
            }

        })
    })

});



// Functions

function trash_audio(id)
{
    if(confirm("Please confirm you are deleting a record") != true)
    {
        return false;
    }
    $.ajax({
        url: '../ajax/admin-ajax',
        data: {id:id, is:'trash_audio'},
        type: 'POST',
        beforeSend: function()
        {
            $('#page-blur').removeClass('hidden')
        },
        success: function(data)
        {
            if(data ==  'ok') location.reload();
        }
    });
}

function trash_campaign(id)
{
    if(confirm("Please confirm you are deleting a record") != true)
    {
        return false;
    }
    $.ajax({
        url: '../ajax/admin-ajax',
        data: {id:id, is:'trash_campaign'},
        type: 'POST',
        beforeSend: function()
        {
            $('#page-blur').removeClass('hidden')
        },
        success: function(data)
        {
            if(data ==  'ok') location.reload();
        }
    });
}

function trash_group(id)
{
    if(confirm("Please confirm you are deleting a record") != true)
    {
        return false;
    }
    $.ajax({
        url: '../ajax/admin-ajax',
        data: {id:id, is:'trash_group'},
        type: 'POST',
        beforeSend: function()
        {
            $('#page-blur').removeClass('hidden')
        },
        success: function(data)
        {
            if(data ==  'ok') location.reload();
        }
    });
}

function trash_phone(id)
{
    if(confirm("Please confirm you are deleting a record") != true)
    {
        return false;
    }
    $.ajax({
        url: '../ajax/admin-ajax',
        data: {id:id, is:'trash_phone'},
        type: 'POST',
        beforeSend: function()
        {
            $('#add-phones-modal-blur').removeClass('hidden');
        },
        success: function(data)
        {
            if(data == 'ok')
            {
                $('#add-phones-modal-blur').addClass('hidden');
                $('#phones').val('');
                show_phones($("#audio_id").val());
            }

        }
    });
}


function trash_phone_group(phone, id)
{
    if(confirm("Please confirm you are deleting a record") != true)
    {
        return false;
    }
    $.ajax({
        url: '../ajax/admin-ajax',
        data: {id:id, phone:phone, is:'trash_phone_group'},
        type: 'POST',
        beforeSend: function()
        {
            $('#add-phones-group-modal-blur').removeClass('hidden');
        },
        success: function(data)
        {
            if(data == 'ok')
            {
                $('#add-phones-group-modal-blur').addClass('hidden');
                $('#phones_group').val('');
                show_phones_group($("#group_id").val());
            }

        }
    });
}


function show_phones(id)
{
    $("#audio_id").val(id);
    $.ajax({
        url: '../ajax/admin-ajax',
        data: {id:id, is:'show_phones'},
        type: 'POST',
        beforeSend: function()
        {
            $('#phones_table_container').html('<center><img class="img-responsive"  src="../images/ajax-loader.gif" /></center>');
        },
        success: function(dat)
        {
            $('#phones_table_container').html(dat);
        }
    });
}

function show_phones_group(id)
{
    $("#group_id").val(id);
    $.ajax({
        url: '../ajax/admin-ajax',
        data: {id:id, is:'show_phones_group'},
        type: 'POST',
        beforeSend: function()
        {
            $('#phones_group_table_container').html('<center><img class="img-responsive"  src="../images/ajax-loader.gif" /></center>');
        },
        success: function(dat)
        {
            $('#phones_group_table_container').html(dat);
        }
    });
}


