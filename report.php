<?php
/**
 * Created by PhpStorm.
 * User: Joseph Lukan
 * Date: 7/31/2017
 * Time: 7:22 PM
 */

$page_title = "Report";
$page_header_styles = [
    'https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css'
];
$page_header_scripts = [];
$page_footer_scripts = [
    'https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',
    '../js/report-script.js'
];

include 'includes/header.php';
//include 'db.php';

// Other page variables
$sql = "SELECT * FROM `sessions`";
$sessions_rs = $conn->query($sql);

$sql = "SELECT `email`, `password` FROM `logins` WHERE `type` = 'slybroadcast' LIMIT 1";
$rs = $conn->query($sql);
$row_logins = $rs->fetch_row();
$email_sly = $row_logins[0];                    // $email_sly
$password_sly = $row_logins[1];                 // $password_sly


?>

<div class="container">
    <div class="row well">
        <h2>Last Campaign Report</h2><hr>
        <div class="table-responsive">
            <table id="report_table">
                <thead>
                <tr>
                    <th>SN</th>
                    <th>Number</th>
                    <th>Status</th>
                    <th>DateTime</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sn = 1;
                foreach ($sessions_rs as $session)
                {
                    $status = getCallStatus($email_sly, $password_sly, $session['session_id'], $session['phone']);
                    $ex = explode("|", $status);
                    ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo $session['phone']; ?></td>
                        <td><?php echo $ex[2].", ".$ex[3]; ?></td>
                        <td><?php echo $ex[4]; ?></td>

                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

</div>




<?php include 'includes/footer.php'; ?>
