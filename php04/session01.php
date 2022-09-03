<?php
session_start();


$old_ses_id = session_id();
// echo $sid;

// $_SESSION['name']='john';
// $_SESSION['age']=12;


//SES ID が更新される
session_regenerate_id(true);

$new_ses_id = session_id();

echo '古いセッション' .$old_ses_id;
echo '<br>';
echo '新しいセッション' .$new_ses_id;