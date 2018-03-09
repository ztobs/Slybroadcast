<?php
$page_title = "Admin";
$page_header_styles = [
    'https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css',
    'css/fileinput.css',
    'themes/explorer/theme.css'
];
$page_header_scripts = [];
$page_footer_scripts = [
    '../js/init-script.js',
    '../src/recorder.js',
    '../src/Fr.voice.js',
    '../js/jquery.js',
    '../js/app.js',
    'https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',
    'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js',
    'https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js',
    'js/plugins/sortable.js',
    'js/fileinput.js',
    'js/locales/fr.js',
    'js/locales/es.js',
    'themes/explorer/theme.js',
    '../js/admin-page-script.js'
];

include 'includes/header.php';
//include 'db.php';


// Other variables for page

$sql = "SELECT * FROM `audios`";
$audios_rs = $conn->query($sql);

$sql = "SELECT * FROM `groups`";
$groups_rs = $conn->query($sql);

$sql = "SELECT * FROM `campaigns`";
$campaigns_rs = $conn->query($sql);

$sly_rs = $conn->query("SELECT `email`, `password` FROM `logins` WHERE `type`='slybroadcast' LIMIT 1");
$sly_row = $sly_rs->fetch_row();

$email_admin = (isset($_COOKIE['email']))?$_COOKIE['email']:"";

?>

<div id='page-blur' class="hidden">
    <img alt='Loading...' src="images/ajax-loader.gif" />
</div>




<div class="container">
    <div class="row">

        <div class="well row">
            <h3 data-toggle="collapse" data-target="#customers_card" class="z-colapse-title">CUSTOMERS</h3><hr>
            <div id="customers_card" class="collapse out">
                <div class="table-responsive">
                    <table id="customers_table" class="table">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Number</th>
                                <th>Audio</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sn = 1;
                                foreach ($audios_rs as $audios)
                                {
                            ?>
                                    <tr>
                                        <td><?php echo $sn++; ?></td>
                                        <td><?php echo $audios['name']; ?></td>
                                        <td><?php echo getPhonesByAudioId($audios['id']); ?></td>
                                        <td><audio controls>
                                                <source src="play?type=customer&id=<?php echo $audios['id']; ?>" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </td>
                                        <td>
                                            <i class="fa fa-2x fa-trash-o z-click" aria-hidden="true" onclick="trash_audio(<?php echo $audios['id']; ?>)"></i> &nbsp;
                                            <i class="fa fa-2x fa-edit z-click" data-toggle="modal" data-target="#edit-customer-phone-modal" onclick="show_phones(<?php echo $audios['id']; ?>)"></i>
                                        </td>
                                    </tr>
                            <?php
                                }
                            ?>

                        </tbody>
                    </table>
                </div>
                <div class="well z-margin-top">
                        <div class="row clearfix">
                            <div class="col-md-12 column">
                                <table class="table table-bordered table-hover" id="tab_logic">
                                    <tbody>
                                    <tr id='addr0'>
                                        <td>
                                            <input type="text" id='customer_name'  placeholder='e.g. John' class="form-control"/>
                                        </td>
                                        <td>
                                            <textarea id="customer_phone" placeholder="9117263678
7024938456" class="form-control" ></textarea>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary form-control" data-toggle="modal" data-target="#add-customer-audio-modal" id="check_customer_form" ><i class="fa fa-microphone"></i> Add Audio</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
            </div>
        </div>


        <div class="well row">
            <h3 data-toggle="collapse" data-target="#groups_card" class="z-colapse-title">CUSTOMER GROUP</h3><hr>
            <div id="groups_card" class="collapse out">
                <div class="table-responsive">
                    <table id="groups_table" class="table">
                        <thead>
                        <tr>
                            <th>SN</th>
                            <th>Group Name</th>
                            <th>Number List</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sn = 1;
                        foreach ($groups_rs as $group)
                        {
                            ?>
                            <tr>
                                <td><?php echo $sn++; ?></td>
                                <td><?php echo $group['name']; ?></td>
                                <td><?php echo $group['list']; ?></td>
                                <td>
                                    <i class="fa fa-2x fa-trash-o z-click" aria-hidden="true" onclick="trash_group(<?php echo $group['id']; ?>)"></i> &nbsp;
                                    <i class="fa fa-2x fa-edit z-click" data-toggle="modal" data-target="#edit-group-phone-modal" onclick="show_phones_group(<?php echo $group['id']; ?>)"></i>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
                <div class="well z-margin-top">
                    <div class="row clearfix">
                        <div class="col-md-12 column">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr id=''>
                                    <td id="z-adder-1">
                                        <input type="text" id='group_name'  placeholder='General List' class="form-control"/>
                                    </td>
                                    <td>
                                        <textarea class="form-control" id="group_list" placeholder="9117263678
7024938456"></textarea>
                                    </td>
                                    <td id="z-adder-2">
                                        <button class="btn btn-primary form-control" id="add-group" ><i class="fa fa-group"></i> Add List</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class=" well row">
            <h3 data-toggle="collapse" data-target="#campaign_card" class="z-colapse-title">CAMPAIGNS</h3><hr>
            <div id="campaign_card" class="collapse out">
                <div class="table-responsive">
                    <table id="campaign_table" class="table">
                        <thead>
                        <tr>
                            <th>SN</th>
                            <th>Title</th>
                            <th>Audio</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sn = 1;
                        foreach ($campaigns_rs as $campaign)
                        {
                            ?>
                            <tr>
                                <td><?php echo $sn++; ?></td>
                                <td><?php echo $campaign['name']; ?></td>
                                <td><audio controls>
                                        <source src="play?type=campaign&id=<?php echo $campaign['id']; ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio></td>
                                <td><i class="fa fa-2x fa-trash-o z-click" aria-hidden="true" onclick="trash_campaign(<?php echo $campaign['id']; ?>)"></i></td>
                            </tr>
                            <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
                <div class="well z-margin-top">
                    <div class="row clearfix">
                        <div class="col-md-12 column">
                            <table class="table table-bordered table-hover" id="">
                                <tbody>
                                <tr id=''>
                                    <td>
                                        <input type="text" id='campaign_name'  placeholder='e.g. General Campaign' class="form-control"/>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary form-control" data-toggle="modal" data-target="#add-campaign-audio-modal" id="check_campaign_form" ><i class="fa fa-microphone"></i> Add Audio</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="well row">
            <h3 data-toggle="collapse" data-target="#login_card" class="z-colapse-title">LOGIN</h3><hr>
            <div id="login_card" class="collapse out">

                <div class="col-md-6">
                    <h4>Api101</h4><hr>
                    <div class="form-group">
                        <label for="email_admin">Email: </label>
                        <input type="text" value="<?php echo $email_admin; ?>" class="form-control" id="email_admin"/>
                    </div>
                    <div class="form-group">
                        <label for="password_admin">Password: </label>
                        <input type="password" class="form-control" id="password_admin" />
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" id="update_login_admin">Update</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <h4>Slybroadcast</h4><hr>
                    <div class="form-group">
                        <label for="email_sly">Email: </label>
                        <input type="text" value="<?php echo $sly_row[0]; ?>" class="form-control" id="email_sly"/>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="password_sly">Password: </label>
                        <input type="password" value="<?php echo $sly_row[1]; ?>" class="form-control" id="password_sly" />
                        <i class="glyphicon glyphicon-eye-open form-control-feedback" id="show-password"></i>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary " id="update_login_sly">Update</button>
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>

<!-- Modals Begin  -->
    <div id="add-customer-audio-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-music"></i> Upload Customer Audio</h4>
                    <br> You need to refresh page when upload is complete to see changes
                </div>
                <div class="modal-body">
                    <div class="well">
                        <h5>Upload A file</h5><hr>
                        <input id="input-700" name="kartik-input-700[]" type="file" multiple class="file-loading">
                    </div>

                    <div class="well">
                        <h5>Or Record Directly</h5><hr>
                        <audio controls id="audio"></audio>
                        <div>
                            <a class="butn recordButton" id="record">Record</a>
                            <a class="butn disabled one" id="stop">Reset</a>
                            <a class="butn disabled one" id="save">Stop & Upload</a>
                        </div><br/>
                        <canvas id="level" height="200" width="500"></canvas>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>


    <div id="add-campaign-audio-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-music"></i> Upload Campaign Audio</h4>
                    <br> You need to refresh page when upload is complete to see changes
                </div>
                <div class="modal-body">
                    <input id type="text" value="0" class="hidden" />
                    <div class="well">
                        <h5>Upload A file</h5><hr>
                        <input id="input-800" name="kartik-input-800[]" type="file" multiple class="file-loading">
                    </div>

                    <div class="well">
                        <h5>Or Record Directly</h5><hr>
                        <audio controls id="audio_"></audio>
                        <div>
                            <a class="butn recordButton" id="record_">Record</a>
                            <a class="butn disabled one" id="stop_">Reset</a>
                            <a class="butn disabled one" id="save_">Stop & Upload</a>
                        </div><br/>
                        <canvas id="level_" height="200" width="500"></canvas>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>



    <div id="edit-customer-phone-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div id='add-phones-modal-blur' class="hidden">
                    <img alt='Loading...' src="images/ajax-loader.gif" />
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Modify Customer Phone Numbers</h4>
                </div>
                <div class="modal-body">
                    <input id="audio_id" class="hidden">
                    <div class="table-responsive" id="phones_table_container">

                    </div>
                    <div class="row z-margin-top">
                        <div class="col-md-8 form-group">
                            <textarea class="form-control" id="phones" placeholder="9117263678
7024938456" ></textarea>
                        </div>
                        <div class="col-md-4 form-group">
                            <button class="btn btn-primary form-control" id="add-phones" ><i class="fa fa-user-plus"></i> Add Phone</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>


    <div id="edit-group-phone-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div id='add-phones-group-modal-blur' class="hidden">
                    <img alt='Loading...' src="images/ajax-loader.gif" />
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-edit"></i> Modify Group List Numbers</h4>
                </div>
                <div class="modal-body">
                    <input id="group_id" class="hidden">
                    <div class="table-responsive" id="phones_group_table_container">

                    </div>
                    <div class="row z-margin-top">
                        <div class="col-md-8 form-group">
                            <textarea class="form-control" id="phones_group" placeholder="9117263678
7024938456" ></textarea>
                        </div>
                        <div class="col-md-4 form-group">
                            <button class="btn btn-primary form-control" id="add-phones-group" ><i class="fa fa-user-plus"></i> Add Phone</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
<!-- Modals End  -->

<?php include 'includes/footer.php'; ?>