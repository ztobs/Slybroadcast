<?php
include '../db.php';

$progress = "progress.txt";
//Temp
$is = $argv[1];
$campaign_id = $argv[2];
$select_group = $argv[3];
$text_group = urldecode($argv[4]);
$caller_id = myClear($argv[5]);
//temp



if($is == "launch")
{
	if(file_exists($progress)) unlink($progress);

    $sql = "TRUNCATE TABLE `sessions`";
    $conn->query($sql);

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
    
    if($success_numbers != "") $final = array('status'=>'ok', 'numbers'=>$success_numbers);
    else $final = array('status'=>'error', 'last_error'=>$error);

    writeToFile($progress, "Completed");
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