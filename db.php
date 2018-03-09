<?php
define("HOST", "127.0.0.1");
define('USER', '***');
define('PASSWORD', '***');
define('DATABASE', '***');
define('PWD_HASH', 'sha256');
define('SESSION_HASH', 'md5');

$dbh = new PDO("mysql:dbname=".DATABASE.";host=".HOST.";port=3306", USER, PASSWORD);

$conn = new mysqli(HOST, USER, PASSWORD, DATABASE);
$conn->set_charset('utf8_bin');

function myClear ($phone)
{
    $phone_ = trim($phone);
    $phone_ = str_replace("\r", "", $phone_);
    $phone_ = preg_replace("/[^0-9]/", "", $phone_);
    $phone_ = str_replace(" ", "", $phone_);
    if(strlen($phone_) != 10) $phone_ = null;
    return $phone_;
};

function existAudio($name)
{
    global $conn;
    $sql = "SELECT * FROM `audios` WHERE `name`='$name'";
    $rs = $conn->query($sql);
    if(count($rs->fetch_assoc()) > 0) return true;
}

function existList($name)
{
    global $conn;
    $sql = "SELECT * FROM `groups` WHERE `name`='$name'";
    $rs = $conn->query($sql);
    if(count($rs->fetch_assoc()) > 0) return true;
}

function insertCustomer($name, $phone, $audio)
{
    $phone_arr = explode("\n", $phone);
    $phone_arr = array_map('myClear', $phone_arr);
    $phone_arr = array_filter($phone_arr);
    global $dbh;
    global $conn;

    if(existAudio($name))
    {
        $sql = $dbh->prepare("UPDATE `audios` SET `audio`= ? WHERE `name`= ? ");
        $sql->execute(array($audio, $name));

        $sql = $dbh->query("SELECT `id` FROM `audios` WHERE `name` = '$name'");
        $id = $sql->fetchColumn();

        foreach($phone_arr as $ph)
        {
            $sql = $dbh->prepare("INSERT INTO `customers` (`phone`, `audio_id`) VALUES (?, ?)");
            $sql->execute(array($ph, $id));
        }

        return $id;
    }
    else
    {
        $sql = $dbh->prepare("INSERT INTO `audios` (`name`, `audio`) VALUES (?,?)");
        $sql->execute(array($name, $audio));
        $id = $dbh->lastInsertId();

        foreach($phone_arr as $ph)
        {
            $conn->query("INSERT INTO `customers` (`phone`, `audio_id`) VALUES ('$ph', $id)");
        }

        return $id;
    }
}


function existCampaign($name)
{
    global $conn;
    $sql = "SELECT * FROM `campaigns` WHERE `name`='$name'";
    $rs = $conn->query($sql);
    if(count($rs->fetch_assoc()) > 0) return true;
}

function insertCampaign($name, $audio)
{

    global $dbh;
    if(existCampaign($name))
    {
        $sql = $dbh->prepare("UPDATE `campaigns` SET `audio`= ? WHERE `name`= ? ");
        $sql->execute(array($audio, $name));

        $sql = $dbh->query("SELECT `id` FROM `campaigns` ORDER BY `id` DESC LIMIT 1");
        $id = $sql->fetchColumn();
        return $id;
    }
    else
    {
        $sql = $dbh->prepare("INSERT INTO `campaigns` (`name`, `audio`) VALUES (?, ?)");
        $sql->execute(array($name, $audio));

        $sql = $dbh->query("SELECT `id` FROM `campaigns` ORDER BY `id` DESC LIMIT 1");
        $id = $sql->fetchColumn();
        return $id;
    }
}


function getCallStatus($email_sly, $password_sly, $session_id, $user_number)
{
    // set post fields
    $post = [
        'c_uid'         => $email_sly,
        'c_password'    => $password_sly,
        'session_id'    => $session_id,
        'c_phone'       => $user_number,
        'c_option'      => 'callstatus'
    ];

    $ch = curl_init('https://www.mobile-sphere.com/gateway/vmb.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}



function getPhonesByAudioId($id)
{
    global $conn;
    $myPhones = "";
    $sql = "SELECT `phone` FROM `customers` WHERE `audio_id`= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($phone);
    while($stmt->fetch())
    {
        $myPhones .= $phone.", ";
    }
    //return $myPhones;
    return rtrim($myPhones, ', ');
}


function is_loggedin()
{
    if(!isset($_COOKIE['session_id'])) return false;

    global $conn;
    $user_session = $_COOKIE['session_id'];
    $sql = "SELECT `session_id` FROM `login_sessions` WHERE `session_id` = '$user_session'";
    $rs = $conn->query($sql);
    if($rs->num_rows > 0) return true;
}


function logout()
{
    setcookie('email', "", time(), "/");
    setcookie('session_id', "", time(), "/");
    header("Location: login");
}

function getUserIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function getLocationByIP($ip)
{
    $resp = json_decode(file_get_contents('http://ip-api.com/json/'.$ip), TRUE);
    if($resp['status'] == 'success') return $resp['city'].", ".$resp['regionName'].", ".$resp['country'];

}