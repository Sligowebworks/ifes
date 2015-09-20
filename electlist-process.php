<?php
    include( "includes/conf.php" );
    sess::set('errors', FALSE);
    sess::set('msg','');
    
    //cache the data
    foreach($_POST as $key => $value)
        sess::set($key,$value);
        
	if (trim($_POST['first_name'])=='' || trim($_POST['last_name'])=='') {
        sess::set_error('First and last names are required');
    }
    
	if (trim($_POST['email'])=='' || !eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}", $_POST['email'])) {
        sess::set_error('Email is not valid');
		header( "Location: electlist-signup.php" );
		exit();
    }
    
	$section = "utilities";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Signup for Electlist</title>
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
				<h2>My Eguide</h2>
				<?php
					// INCLUDES
					include( "admin/includes/class.phpmailer.php" );
					include( "admin/includes/Mailer.Class.php" );
	
					$_POST['date_created'] = date('Y-m-d');
					$auth = Common::generate_auth(40);
					$_POST['auth_string'] = $auth;
					$auth_string = md5($_POST['email'].$auth);
					$country_name = Common::get_country_from_db($_POST['interest_country']);
            		// DATABASE INSERT
            		foreach($_POST as $key => $value)
                        $_POST[$key] = Common::add_them_slashes($value);
					$db = new Db();
					$db->AutoInsert("data_warehouse");
					
					// EMAIL GENERATION
					include( "includes/email/electlist-signup.php" );
					
					$contacts[0] = "psalonen@esitemarketing.com";
					//$contacts[1] = "oluca@ifes.org";
					$subject = "Election Guide - Electlist Signup";
					$username = stripslashes($_POST['first_name'].' '.$_POST['last_name']);
					
					for( $i = 0; $i < count( $contacts ); $i++ ) {
						$Mailer = new Mailer($contacts[$i], 'forms@esitehotel.com', $username, $subject, $html_body);
						$Mailer->SendMail();
					}
					
					include( "includes/email/electlist-confirm.php" );
					$conf = new Mailer($_POST['email'], 'forms@esitehotel.com', 'Electlist', 'Please confirm your Electlist Subscription', $html_conf);
					$conf->SendMail();
						
					if (trim($_POST['prefix'])!='' && trim($_POST['last_name'])!='')
					   echo '<p>Dear '.trim($_POST['prefix']).' '.stripslashes(trim($_POST['last_name'])).', </p>';
					   
					echo '<p>Thank you for subscribing to Electlist. We have sent you an email with
                    the activation link. Please check your email and activate your account so you
                    can start getting Electlist Newsletters.</p>
					<p>Regards,<br />
					Election Guide</p>';
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
