<?php
    include( "includes/conf.php" );
   
    //cache the data
    foreach($_POST as $key => $value)
        sess::set($key,$value);
        
	if (trim($_POST['email'])=='' || !eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}", $_POST['email'])) {
        sess::set_error('Email is not valid');
		header( "Location: contact.php" );
		exit;
    }
    
	if (trim($_POST['comments'])=='') {
        sess::set_error('Comments is a required field');
		header( "Location: contact.php" );
		exit;
    }
    
	$section = "utilities";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Comments</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/ifes.css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
</head>

<body id="body-<?php echo $section; ?>">
	<?php include( "includes/jump-links.php" ); ?>
	<div id="wrapper">
		<?php include( "includes/header.php" ); ?>
		<hr />
		<div id="main-wrapper">
			<div id="content-wrapper">
				<h2>Contact Us:</h2>
				<?php
					// INCLUDES
					include( "admin/includes/class.phpmailer.php" );
					include( "admin/includes/Mailer.Class.php" );
					
					// PREPARE FOR DB INSERT
					$_POST['ds_id'] = 1;
					
					$comments_email = Common::strip_them_slashes( nl2br( $_POST['comments'] ) );
					foreach($_POST as $key => $value)
        				$_POST[$key] = Common::add_them_slashes($value);
					//$_POST['comments'] = Common::add_them_slashes( $_POST['comments'] );
					//$_POST['org_type'] = Common::add_them_slashes( $_POST['org_type'] );
					
					// DATABASE INSERT
					$db = new Db();
					$db->AutoInsert("data_warehouse");
					
					// EMAIL GENERATION
					include( "includes/email/contact.php" );
					
					$contacts[0] = "electionguide@ifes.org";
					$subject = "Election Guide - Comment Form Data";
					
					for( $i = 0; $i < count( $contacts ); $i++ ) {
						$Mailer = new Mailer($contacts[$i], $_POST['email'], $_POST['first_name']." ".$_POST['last_name'], $subject, $html_body);
						$Mailer->SendMail();
					}
					if (trim($_POST['title'])!='' && trim($_POST['last_name'])!='')
					   echo '<p>Dear '.trim(stripslashes($_POST['title'])).' '.trim(stripslashes($_POST['last_name'])).', </p>';
					   
					echo '<p>Thank you for your interest in Election Guide. Your comments
                    and/or information request have been sent to our team.</p>
					<p>Regards,<br />
					Election Guide</p>';
					sess::unset_all();
				?>
			</div>
			<hr />
			<div id="sidebar-wrapper">
				<?php
					include( "includes/sidebar-search.php" );
					include( "includes/sidebar-elections.php" );
					include( "includes/sidebar-news.php" );
				?>
			</div>
		</div>
		<hr id="clear-hack" />
		<?php include( "includes/footer.php" ); ?>
	</div>
</body>
</html>
