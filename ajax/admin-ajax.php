<?php
/**
 * Created by PhpStorm.
 * User: Joseph Lukan
 * Date: 7/29/2017
 * Time: 8:33 PM
 */

// removing execution limits
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');






$progress = "progress.txt";



if(!isset($_POST['is'])) die();
$is = $_POST['is'];
include '../db.php';

if($is == 'trash_audio')
{
    $id = $_POST['id'];
    $sql = "DELETE FROM `audios` WHERE id = ".$id;
    $conn->query($sql);
    if($conn->affected_rows > 0) echo "ok";
}

if($is == 'trash_campaign')
{
    $id = $_POST['id'];
    $sql = "DELETE FROM `campaigns` WHERE id = ".$id;
    $conn->query($sql);
    if($conn->affected_rows > 0) echo "ok";
}

if($is == 'trash_group')
{
    $id = $_POST['id'];
    $sql = "DELETE FROM `groups` WHERE id = ".$id;
    $conn->query($sql);
    if($conn->affected_rows > 0) echo "ok";
}

if($is == 'trash_phone')
{
    $id = $_POST['id'];
    $sql = "DELETE FROM `customers` WHERE id = ".$id;
    $conn->query($sql);
    if($conn->affected_rows > 0) echo "ok";
}

if($is == 'trash_phone_group')
{
    $id = $_POST['id'];
    $phone = $_POST['phone'];

    $sql = "SELECT `list` FROM `groups` WHERE `id` = $id";
    $rs = $conn->query($sql);
    $row = $rs->fetch_row();
    $phone_arr = explode(", ", $row[0]);
    $ret_arr = array_diff($phone_arr, array($phone));
    $ret_str = implode(", ", $ret_arr);

    $sql = "UPDATE `groups` SET `list` = '$ret_str' WHERE `id` = ".$id;
    $conn->query($sql);
    if($conn->affected_rows > 0) echo "ok";
}

if($is == 'show_phones')
{
    $id = $_POST['id'];
    $sql = "SELECT * FROM `customers` WHERE `audio_id` = $id";
    $rs = $conn->query($sql);
    $sn = 1;

    $str = "<table id=\"edit_cust_table\">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Phone Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>";
    while($row = $rs->fetch_assoc())
    {
        $str .= "<tr>
            <td>".$sn++."</td>
            <td>".$row['phone']."</td>
            <td><i class=\"fa fa-2x fa-trash-o z-click\" aria-hidden=\"true\" onclick=\"trash_phone(".$row['id'].")\"></i></td>
        </tr>";
    }
    $str .= "
                            </tbody>
                        </table>
                        <script>
        $('#edit_cust_table').DataTable({
        'paging': true,
        'columns': [
            {'width':'5%'},
            null,
            {'width':'5%'}
        ],
        'autoWidth': false,
        'lengthMenu': [[5, 20, 50, -1], [5, 20, 50, \"All\"]],
        'responsive': true,
        'scrollCollapse': true
    });                
                </script>        
                        ";

    echo $str;
}


if($is == 'show_phones_group')
{
    $id = $_POST['id'];
    $sql = "SELECT `list` FROM `groups` WHERE `id` = $id";
    $rs = $conn->query($sql);
    $row = $rs->fetch_row();
    $phonees = explode(", ", $row[0]);
    $sn = 1;

    $str = "<table id=\"edit_group_table\">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Phone Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>";
    foreach($phonees as $ph)
    {
        $str .= "<tr>
            <td>".$sn++."</td>
            <td>".$ph."</td>
            <td><i class=\"fa fa-2x fa-trash-o z-click\" aria-hidden=\"true\" onclick=\"trash_phone_group(".$ph.",".$id.")\"></i></td>
        </tr>";
    }
    $str .= "
                            </tbody>
                        </table>
                        <script>
        $('#edit_group_table').DataTable({
        'paging': true,
        'columns': [
            {'width':'5%'},
            null,
            {'width':'5%'}
        ],
        'autoWidth': false,
        'lengthMenu': [[5, 20, 50, -1], [5, 20, 50, \"All\"]],
        'responsive': true,
        'scrollCollapse': true
    });                
                </script>        
                        ";

    echo $str;
}

if($is == 'update slybroadcast login')
{
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "UPDATE `logins` SET `email`= '$email', `password`= '$password' WHERE `type`= 'slybroadcast'";
    $conn->query($sql);
    if($conn->error == "") echo "ok";
}

if($is == 'update_login_admin')
{
    $email = $_POST['email'];
    $pass  = $_POST['pass'];
    $pass_hash = hash(PWD_HASH, $pass);
    $sql = "UPDATE `logins` SET `email`= '$email', `password`= '$pass_hash' WHERE `type`= 'site'";
    $conn->query($sql);
    if($conn->error == "")
    {
        setcookie('email', $email, time() + (3600 * 24 * 7), "/");
        echo "ok";
    }
}

if($is == 'login')
{
    $email = $_POST['usr'];
    $pass = $_POST['pwd'];
    $hashed_user_pass = hash(PWD_HASH, $pass);
    $sql = "SELECT * FROM `logins` WHERE `type` = 'site' AND `email` = '$email' AND `password` = '$hashed_user_pass'";
    $rs = $conn->query($sql);
    if($rs->num_rows > 0)
    {
        $time = date("Y-m-d H:i:s");
        $ip = getUserIP();
        //$location = getLocationByIP($ip);
        $location = "";

        $session_id = hash(SESSION_HASH, "$email|$location|$time");
        $sql = "INSERT INTO `login_sessions` (`session_id`, `ip`, `when`, `location`) VALUES ('$session_id', '$ip', '$time', '$location')";
        $conn->query($sql);
        if($conn->affected_rows > 0)
        {
            setcookie('email', $email, time() + (3600 * 24 * 7), "/"); // 1 week
            setcookie('session_id', $session_id, time() + (3600 * 24 * 7), "/"); // 1 week
            echo 'ok';
        }
    }
}

if($is == 'add-group')
{
    $name = $_POST['name'];
    $phones = $_POST['list'];
    $phone_arr = explode("\n", $phones);
    $phone_arr = array_map('myClear', $phone_arr);
    $phone_arr = array_filter($phone_arr);
    $list = implode(", ", $phone_arr);
    if(existList($name))
    {
        $sql = "UPDATE `groups` SET `list` = CONCAT(`list`, ', ".$list."') WHERE `name` = '$name'";
        $conn->query($sql);
        if($conn->affected_rows > 0) echo "ok";
    }
    else
    {
        $sql = "INSERT INTO `groups` (`name`, `list`) VALUES ('$name', '$list')";
        $conn->query($sql);
        if($conn->affected_rows > 0) echo "ok";
    }

}

if($is == 'add_phones')
{
    $id = $_POST['id'];
    $phones = $_POST['phones'];
    $phone_arr = explode("\n", $phones);
    $phone_arr = array_map('myClear', $phone_arr);
    $phone_arr = array_filter($phone_arr);
    $err = true;
    foreach ($phone_arr as $ph)
    {
        $sql = "INSERT INTO `customers` (`phone`, `audio_id`) VALUES ('$ph', $id)";
        $conn->query($sql);
        if($conn->error == "") $err = false;  // Just one success is enough
    }
    if(!$err) echo "ok";
}


if($is == 'add_phones_group')
{
    $id = $_POST['id'];
    $phones = $_POST['phones'];
    $phone_arr = explode("\n", $phones);
    $phone_arr = array_map('myClear', $phone_arr);
    $phone_arr = array_filter($phone_arr);
    $pho = implode(", ", $phone_arr);
    $sql = "UPDATE `groups` SET `list` = CONCAT(`list`, ', ', '$pho') WHERE `id` = $id";
    $conn->query($sql);
    if($conn->affected_rows > 0) echo "ok";
}

if($is == "launch")
{
    $sql = "TRUNCATE TABLE `sessions`";
    $conn->query($sql);

    $campaign_id = $_POST['select_campaign'];
    $select_group = $_POST['select_group'];
    $text_group = $_POST['text_group'];             // $text_group
    $caller_id = myClear($_POST['caller_id']);      // $caller_id
    if($caller_id == null) die(json_encode(array('status'=>'error', 'last_error'=>'Invalid Caller ID, it has to be exactly 10 digits')));


    $sql = "SELECT `email`, `password` FROM `logins` WHERE `type` = 'slybroadcast' LIMIT 1";
    $rs = $conn->query($sql);
    $row_logins = $rs->fetch_row();
    $email_sly = $row_logins[0];                    // $email_sly
    $password_sly = $row_logins[1];                 // $password_sly

    

    if($text_group == "")
    {
        $sql = "SELECT `list` FROM `groups` WHERE `id` = $select_group";
        $rs = $conn->query($sql);
        $row = $rs->fetch_row();
        $text_group = $row[0];
        $customer_array = explode(",", $text_group);
    }
    else
    {
        $customer_array = explode("\n", $text_group);
    }

    $customer_array = array_map('myClear', $customer_array);
    $customer_array = array_filter($customer_array);
    $customer_array = array_unique($customer_array);

    if(count($customer_array) < 1) die(json_encode(array('status'=>'error', 'last_error'=>'None of the customer numbers are valid, only 10 digits allowed')));

    $success_numbers = "";
    $error = "";
    $count = 0;

    foreach ($customer_array as $cust_num)
    {
        $count++;
        $cust_num = trim($cust_num);                // $cust_num

        $sql = "SELECT
                    `aud`.`id` AS `audio_id`
                FROM
                    `customers` AS `cust`
                LEFT JOIN `audios` AS `aud` ON `cust`.`audio_id` = `aud`.`id`
                WHERE
                    `cust`.`phone` = '$cust_num'";
        $rs = $conn->query($sql);
        $row = $rs->fetch_row();
        $aud_id = $row[0];                      // $user_audio

        $resp = getCURL($email_sly, $password_sly, $caller_id, $cust_num, $campaign_id, $aud_id);

        if(strpos($resp, "session_id") !== FALSE )
        {
            $arr = explode("\n", $resp);
            $session_id = trim(str_replace("session_id=", "", $arr[1]));
            $datetime = date("Y-m-d H:i:s");

            $sql = "INSERT INTO `sessions` (`session_id`, `when`, `phone`) VALUES ('$session_id', '$datetime', '$cust_num')";
            $conn->query($sql);

            $success_numbers .= $cust_num.", ";
            writeToFile($progress, "$count)  V.Message sent to $cust_num <br>");

        }
        else
        {
            $error = $resp;
            writeToFile($progress, "$count)  Cant send to $cust_num <br>");
        }


    }
    unlink($progress);
    if($success_numbers != "") $final = array('status'=>'ok', 'numbers'=>$success_numbers);
    else $final = array('status'=>'error', 'last_error'=>$error);

    echo json_encode($final);
}



 

function getCURL($email_sly, $password_sly, $caller_id, $user_number, $campaign_id, $aud_id )
{
    // set post fields

    if($aud_id)
    {
        $post = [
            'c_uid'         => $email_sly,
            'c_password'    => $password_sly,
            'c_url'         => 'http://api101.net/play?type=customer&id='.$aud_id,
            'c_url_second'  => 'http://api101.net/play?type=campaign&id='.$campaign_id,
            'c_callerID'    => $caller_id,
            'c_phone'       => $user_number,
            'c_date'        => 'now'
        ];
    }
    else
    {
        $post = [
            'c_uid'         => $email_sly,
            'c_password'    => $password_sly,
            'c_url'         => 'http://api101.net/play?type=campaign&id='.$campaign_id,
            'c_callerID'    => $caller_id,
            'c_phone'       => $user_number,
            'c_date'        => 'now'
        ];
    }


    $ch = curl_init('https://www.mobile-sphere.com/gateway/vmb.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


/*
 * Function writes data to file
 * @params: string $fileName, string $data
 * @return $resp
 */
function writeToFile($fileName, $data)
{
    $file = fopen($fileName, "a+");
    $resp = fwrite($file, $data);
    fclose($file);
    return $resp;
}