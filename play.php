<?php
/**
 * Get BLOB Data from Database and ouput
 */
if(isset($_GET['id']))
{
  require_once __DIR__ . "/db.php";
  if($_GET['type'] == 'customer') $sql = $dbh->prepare("SELECT `audio`, LENGTH(`audio`) FROM `audios` WHERE `id` = ?");
  elseif($_GET['type'] == 'campaign') $sql = $dbh->prepare("SELECT `audio`, LENGTH(`audio`) FROM `campaigns` WHERE `id` = ?");
  $sql->execute(array($_GET['id']));
  $result = $sql->fetch();

  $audio = $result[0];
  $size = $result[1];
  
  header("Content-Length: $size");
  header("Content-Type: audio/wav");
  echo $audio;
}
