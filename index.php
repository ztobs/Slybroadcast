<?php
$page_title = "";
$page_header_styles = [];
$page_header_scripts = [];
$page_footer_scripts = [
];

include 'includes/header.php';
//include 'db.php';

// Other page variables
$sql = "SELECT * FROM `campaigns`";
$campaigns_rs = $conn->query($sql);

$sql = "SELECT * FROM `groups`";
$groups_rs = $conn->query($sql);


?>

    <!-- Page Content -->
    <div class="container">
        <div class="row form-group">
            <div class="col-xs-12">
                <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                    <li class="active"><a href="#step-1">
                        <h4 class="list-group-item-heading">Step 1</h4>
                        <p class="list-group-item-text">Choose Campaign Audio</p>
                    </a></li>
                    <li class="disabled"><a href="#step-2">
                        <h4 class="list-group-item-heading">Step 2</h4>
                        <p class="list-group-item-text">Choose Listing Group</p>
                    </a></li>
                    <li class="disabled"><a href="#step-3">
                        <h4 class="list-group-item-heading">Step 3</h4>
                        <p class="list-group-item-text">Set Caller ID</p>
                    </a></li>
                    <li class="disabled"><a href="#step-4">
                        <h4 class="list-group-item-heading">Step 4</h4>
                        <p class="list-group-item-text">Launch</p>
                    </a></li>    
                </ul>
            </div>
        </div>
    </div>  

    <form class="container">

        <div class="row setup-content" id="step-1">
            <div class="col-xs-12">
                <div class="col-md-12 well text-center">
                    <h1> CAMPAIGN AUDIO</h1>
                    <hr>
                    <div class="form-group">
                        <select id="select_campaign" class="form-control">
                            <option value="0" >Choose Campaign</option>
                            <?php
                                foreach ($campaigns_rs as $campaign)
                                {
                            ?>
                                    <option value="<?php echo $campaign['id']?>"><?php echo $campaign['name']; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                    
                    <button id="activate-step-2" class="btn btn-primary btn-md">Next</button>
                </div>
            </div>
        </div>

    </form>

    <form class="container">

        <div class="row setup-content" id="step-2">
            <div class="col-xs-12">
                <div class="col-md-12 well text-center">
                    <h1 class="text-center"> CUSTOMER GROUP</h1>
                    <hr>
                    <div class="form-group">
                        <select id="select_group" class="form-control">
                            <option value="0" >Choose Customer Group</option>
                            <?php
                            foreach ($groups_rs as $group)
                            {
                                ?>
                                <option value="<?php echo $group['id']?>"><?php echo $group['name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <span id="or">OR</span>
                    <div class="form-group">
                        <textarea id="text_group" class="form-control" placeholder="9197954902
3368592058
9196095786
9194173019"></textarea>
                    </div>
                    
                    <button id="activate-step-3" class="btn btn-primary btn-md">Next</button>
                </div>
            </div>
        </div>

    </form>

    <form class="container">

        <div class="row setup-content" id="step-3">
            <div class="col-xs-12">
                <div class="col-md-12 well text-center">
                    <h1 class="text-center"> CALLER ID</h1>
                    <hr>
                    <div class="form-group">
                        <input type="text" id="caller_id" class="form-control" placeholder="e.g. 07086353546"/>
                    </div>

                    <button id="activate-step-4" class="btn btn-primary btn-md">Next</button>
                </div>
            </div>
        </div>

    </form>

    <form class="container">
        
        <div class="row setup-content" id="step-4">
            <div class="col-xs-12">
                <div class="col-md-12 well text-center">
                    <h1 class="text-center"> LAUNCH</h1>
                    <hr>
                    <div class="form-group col-md-4 col-md-offset-4" >
                        <button id="launch_campaign" class=" btn btn-primary form-control" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing" > GO <i class="fa fa-rocket"></i> </button>
                        <div id="notice" class="text-center z-margin-top hidden">..closing the browser wont stop the process, you just wont know when it ends.<br>
                        Press "c" to alternate console visibility, Press "f" to alternate full-screen mode
                        </div>
                    </div>
                    
                </div>
                <div id="console" class="col-md-12 hidden">
                    <div id="console_title">CONSOLE</div>
                    <div id="console_body">
                    </div>
                </div>
            </div>
        </div>

    </form>
    <!-- /.page Content -->

<?php include 'includes/footer.php'; ?>
