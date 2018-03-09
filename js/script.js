
// Activate Next Step

$(document).ready(function() {
    
    var navListItems = $('ul.setup-panel li a'),
        allWells = $('.setup-content');

    allWells.hide();

    navListItems.click(function(e)
    {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this).closest('li');
        
        if (!$item.hasClass('disabled')) {
            navListItems.closest('li').removeClass('active');
            $item.addClass('active');
            allWells.hide();
            $target.show();
        }
    });
    
    $('ul.setup-panel li.active a').trigger('click');
    
    // DEMO ONLY //
    $('#activate-step-2').on('click', function(e) {
        if($('#select_campaign').val() == '0')
        {
            $('#select_campaign').addClass('z-form-error');
            return false;
        }
        else
        {
            $('#select_campaign').removeClass('z-form-error');
            $('ul.setup-panel li:eq(1)').removeClass('disabled');
            $('ul.setup-panel li a[href="#step-2"]').trigger('click');
            $(this).remove();
        }

    });
    
    $('#activate-step-3').on('click', function(e) {
        if($('#select_group').val() == '0' && $('#text_group').val().trim() == '')
        {
            $('#select_group').addClass('z-form-error');
            $('#text_group').addClass('z-form-error');
            return false;
        }
        else
        {
            $('#select_group').removeClass('z-form-error');
            $('#text_group').removeClass('z-form-error');
            $('ul.setup-panel li:eq(2)').removeClass('disabled');
            $('ul.setup-panel li a[href="#step-3"]').trigger('click');
            $(this).remove();
        }

    });
    
    $('#activate-step-4').on('click', function(e) {
        if($('#caller_id').val().trim() == '')
        {
            $('#caller_id').addClass('z-form-error');
            return false;
        }
        else
        {
            $('#caller_id').removeClass('z-form-error');
            $('ul.setup-panel li:eq(3)').removeClass('disabled');
            $('ul.setup-panel li a[href="#step-4"]').trigger('click');
            $(this).remove();
        }

    });


    $('#select_group').focus(function () {
        $('#text_group').val('');
    });

    $('#text_group').focus(function () {
        $('#select_group').val('0');
    });

    var interval;
    $('#launch_campaign').click(function(e){
        e.preventDefault();
        var err = "";
        if($('#select_campaign').val() == '0')
        {
            $('#select_campaign').addClass('z-form-error');
            err += 'Goto campaign tab and choose a campaign \r\n';
        }
        if($('#select_group').val() == '0' && $('#text_group').val().trim() == '')
        {
            $('#select_group').addClass('z-form-error');
            $('#text_group').addClass('z-form-error');
            err += 'Goto customer group tab and choose a group or enter numbers \r\n';
        }
        if($('#caller_id').val().trim() == '')
        {
            $('#caller_id').addClass('z-form-error');
            err += 'Goto caller id tab and enter your valid caller id of 10 digits \r\n';
        }

        if($('#caller_id').val().trim().length !=  10)
        {
            $('#caller_id').addClass('z-form-error');
            err += 'Goto caller id tab and enter your valid caller id of 10 digits \r\n';
        }

        if(err != '')
        {
            alert (err);
            return false;
        }

        var fdata = {
            select_campaign: $('#select_campaign').val(),
            select_group: $('#select_group').val(),
            text_group: $('#text_group').val().trim(),
            caller_id: $('#caller_id').val().trim(),
            is: 'launch'
        };
 

        $.ajax({
            url: '../ajax/nostop',
            type: 'POST',
            data: fdata,
            dataType: 'json',
            beforeSend: function()
            {
                $('#notice').removeClass('hidden');
                $('#console').removeClass('hidden');
                $('#launch_campaign').button('loading');
                interval = setInterval(function(){
                    hrx = $.get("../ajax/progress.txt").done(function(progress){
                        if(progress.indexOf("Completed") > -1) 
                        {
                            alert("Campaign sent");
                            clearInterval(interval);
                            $('#launch_campaign').button('reset');
                            location.replace('../report');
                        }
                        $('#console_body').html(progress);
                        $('#console_body').scrollTop($("#console_body")[0].scrollHeight);
                    })
                },3000);

            },
            success: function(data)
            {
                $('#notice').addClass('hidden');
                if(data.status ==  'ok')
                {
                    // $('#launch_campaign').button('reset');
                    // alert("Campaign sent to the following number: \r\n"+data.numbers);
                    // location.replace('../report');
                }
                else if(data.status == 'error')
                {
                    $('#launch_campaign').button('reset');
                    alert("One or more errors occurred, with last error details: \r\n"+data.last_error);
                }
                //clearInterval(interval);
            }
        })
        

    })

    $(document.body).keypress(function(e) {
        var dInput = String.fromCharCode(e.which);
        if(dInput == "c")
        {
            $("#console").toggleClass('hidden');
        }

        if(dInput == "f")
        {
            $("#console").toggleClass('full-screen');
        }
        
    });
});




