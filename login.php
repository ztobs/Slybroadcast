<?php
/**
 * Created by PhpStorm.
 * User: Joseph Lukan
 * Date: 8/1/2017
 * Time: 4:18 PM
 */
include 'db.php';

	//ob_start();
if(is_loggedin()) header("Location: /");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>API 101 | Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <style>
        body {
            background:#333;
        }
        .form_bg {
            background-color:#eee;
            color:#666;
            padding:20px;
            border-radius:10px;
            position: absolute;
            border:1px solid #fff;
            margin: auto;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 320px;
            height: 320px;
        }
        .align-center {
            text-align:center;
        }

    </style>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body>
<div class="container">
    <div class="row">
        <div class="form_bg">
            <form>
                <h2 class="text-center">Login</h2>
                <br/>
                <div class="form-group">
                    <input type="text" class="form-control" id="usr" placeholder="Username">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="pwd" placeholder="Password">
                </div>
                <div class="align-center" id="status"></div>
                <br/>
                <div class="align-center">
                    <button type="submit" class="btn btn-default" id="login" onclick="return false;">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>

<script>
    $(document).ready(function(){
        $('#login').click(function(){
            var usr = $('#usr').val();
            var pwd = $('#pwd').val();

            if(usr=="" || pwd=="") return;

            var indata = {usr:usr, pwd:pwd, is:'login'};
            $.ajax({
                url: 'ajax/admin-ajax',
                data: indata,
                type: 'POST',
                success: function(data){

                    if(data == "ok")
                    {
                        location.reload();
                    }
                    else
                    {
                        $("#status").html('Invalid Credentials');
                    }
                },

                beforeSend: function(){
                    $("#status").html('<center><img src="images/loading.gif" title="Loading"/></center>');
                }
            });
        })


    })
</script>
</html>