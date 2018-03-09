<?php



system("nohup php ./worker.php ".$_POST['is']." ".$_POST['select_campaign']." ".$_POST['select_group']." ".urlencode($_POST['text_group'])." ".$_POST['caller_id']." &", $code);




