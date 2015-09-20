<?php
    include( "includes/conf.php" );
if ($_REQUEST['ID']) {
  if (!$_REQUEST['ID'] || strlen($_REQUEST['ID'])!=32) {
    sess::set_error('Authorization Code is missing or invalid.');
  } else {
		$db = new Db();
		$db->Query("SELECT id,first_name,last_name,email,date_created,issues,
        country_1,country_2,country_3,country_4,country_5,signup_eguide
        FROM myeguide
        WHERE MD5(CONCAT(email,auth_string))='".$_REQUEST['ID']."'");
		if ($db->GetAffectedRows()==1) {
			$row = $db->fetchAssoc();
			$db1 = new Db();
			$db1->Query("UPDATE myeguide SET is_active=1 WHERE id=".$row['id']);
            
            if($row['signup_eguide']==1) {
                sess::set_msg('Your account has been confirmed. You can now access your choice of ElectionGuide services.');
                sess::set('loggedin',TRUE);
                foreach($row as $key => $value)
                    sess::set($key,$value);
                ob_end_clean();
                header('location: eguide.php');
                exit();
            } else {
                $str = '<p style="border: 1px solid silver;padding:5px;">Thank you for confirming your subscription to ElectList!
                To unsubscribe, follow the instructions in the ElectList! newsletter.<br /><br />
                Regards,<br />ElectionGuide</p>';
                sess::set_msg($str);
                header('location: newsletter.php');
                exit();
            }
		} else {
    	   sess::set_error('Authorization Code not found.');
		}
    }
} else if ($_REQUEST['IDE']) {
  if (!$_REQUEST['IDE'] || strlen($_REQUEST['IDE'])!=32) {
    sess::set_error('Authorization Code is missing or invalid.');
  } else {
		$db = new Db();
		$db->Query("SELECT id FROM myeguide WHERE MD5(auth_string)='".$_REQUEST['IDE']."'");
		if ($db->GetAffectedRows()==1) {
			$row = $db->fetchAssoc();
			$db1 = new Db();
			$db1->Query("UPDATE myeguide SET email=pending_email WHERE id=".$row['id']);
			$db1->Query("UPDATE myeguide SET pending_email='' WHERE id=".$row['id']);
    	   sess::set_msg('Your Account has been activated.');
		} else {
    	   sess::set_error('Authorization Code not found.');
		}
    }
}
header( "Location: eguide.php" );
exit();
?>




