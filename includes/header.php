<?php
/**
 * Created by PhpStorm.
 * User: Joseph Lukan
 * Date: 7/29/2017
 * Time: 2:58 PM
 */
include 'db.php';
if(!is_loggedin()) header("Location: login");
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo ($page_title != "" && $page_title != null)?$page_title." | ":"";?>Slybroadcast API</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">



    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <?php
    foreach ($page_header_styles as $styles)
    {
        ?>
            <link href="<?php echo $styles; ?>" rel="stylesheet" >
        <?php
    }
    ?>

    <!-- Custom scripts -->
    <?php
    foreach ($page_header_scripts as $script)
    {
        ?>
            <script src="<?php echo $script; ?>"></script>
        <?php
    }
    ?>

</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../">Sylbroadcast API</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php if($page_title != "Admin"): ?><li><a href="admin">Admin</a></li> <?php endif ?>
                <?php if($page_title != "Report"): ?><li><a href="report">Report</a></li> <?php endif ?>
                <li><a href="logout">Log Out</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>


