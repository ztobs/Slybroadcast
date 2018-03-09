<?php
set_time_limit(0);

include 'db.php';
$target_dir = "audio/";

// Empty directory before starting
foreach (glob($target_dir."/*.*") as $filename) {
    if (is_file($filename)) {
        unlink($filename);
    }
}




//$f_name = $_FILES["kartik-input-700"]["name"][0];
if($_POST['type'] == "customer")
{
    $input_audio = $_FILES["kartik-input-700"];
}
elseif ($_POST['type'] == "campaign")
{
    $input_audio = $_FILES["kartik-input-800"];
}

$f_name = $input_audio["name"][0];

$target_file = $target_dir . basename($input_audio["name"][0]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($input_audio["tmp_name"][0]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check file size
if ($input_audio["size"][0] > 32000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "wav" && $imageFileType != "mp3" ) {
    echo "Sorry, only mp3 & wav files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if ($_POST['type'] == "customer")
    {
        $c_name = $_POST['c_name'];
        $c_phone = $_POST['c_phone'];
        $audio = file_get_contents($input_audio["tmp_name"][0]);
        if($ret = insertCustomer($c_name, $c_phone, $audio))
        {
            $return = array('initialPreviewConfig'=>
                array(
                    'caption' => $f_name,
                    'url' => 'deleter',
                    'ret_data'=>$ret
                )
            );
            echo json_encode($return);
        }

    }
    elseif($_POST['type'] == "campaign")
    {
        $c_name = $_POST['c_name'];
        $audio = file_get_contents($input_audio["tmp_name"][0]);
        if($ret = insertCampaign($c_name, $audio))
        {
            $return = array('initialPreviewConfig'=>
                array(
                    'caption' => $f_name,
                    'url' => 'deleter',
                    'ret_data' => $ret
                )
            );
            echo json_encode($return);
        }
    }
}


?>