<?php
// For Audio Recorder
/**
 * Request has Base64 Data
 * -----------------------
 * $_POST['audio'] is the Base64 encoded value of audio (WAV/MP3)
 */
if(isset($_POST['audio'])){
  $audio = base64_decode($_POST['audio']);
  
  echo $audio;
}

/**
 * Request has BLOB Data
 * ---------------------
 */
if(isset($_FILES['file'])){
  $audio = file_get_contents($_FILES['file']['tmp_name']);
  $name = $_POST['name'];
  $phone = $_POST['phone'];

  
  require_once __DIR__ . "/db.php";

  if ($_POST['type'] == "customer") $id = insertCustomer($name, $phone, $audio);
  elseif ($_POST['type'] == "campaign") $id = insertCampaign($name, $audio);


  if($id)
  {
      $res = array('url'=>"play?id=".$id."&type=".$_POST['type'], 'insert_id'=>$id);
      header("Content-Type: application/json", true);
      echo json_encode($res);
  }

}
