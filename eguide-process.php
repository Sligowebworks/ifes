<?php
    include( "includes/conf.php" );
    $section = "eguide";
    sess::set('signup_eguide', ($_REQUEST['signup_eguide']!=1)?0:1);
    sess::set('signup_electlist', ($_REQUEST['signup_electlist']!=1)?0:1);
    foreach($_POST as $key => $value)
        sess::set($key,$value);

    if (sess::get('signup_electlist')!=1 && sess::get('signup_eguide')!=1)
        sess::set_error('At least one subscription checkbox needs to be checked.');
        
    if (sess::get('first_name')=='')
        sess::set_error('First name is required.');

    if (sess::get('last_name')=='')
        sess::set_error('Last name is required.');
        
    if (trim(sess::get('email'))=='' || !eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}", sess::get('email')))
        sess::set_error('Email is not valid.');
        
    if (sess::get('age')=='')
        sess::set_error('Age is required.');
        
    if (sess::get('education')=='')
        sess::set_error('Education is required.');

    if (sess::get('country')=='' || sess::get('country')=='any')
        sess::set_error('Country is required.');
        
    if (sess::get('signup_eguide')==1) {
        if (sess::get('password')=='')
            sess::set_error('Password is required.');

        if (sess::get('password1')=='')
            sess::set_error('Repeating the Password is required.');
            
        if (sess::get('password') != sess::get('password1'))
            sess::set_error('Password do not match.');
    } else {
        $_POST['password']=='';
    }
    
    if (sess::get('signup_eguide')==1) {
        $db = new Db();
        $db->Query("SELECT id FROM myeguide WHERE email='".sess::get('email')."' AND signup_eguide=1");
        if ($db->GetAffectedRows()>=1)
            sess::set_error('Email is already in use. Please select another.');
    
        $country_array = array(
            sess::get('country_1'),
            sess::get('country_2'),
            sess::get('country_3'),
            sess::get('country_4'),
            sess::get('country_5'));
        if (is_array($country_array))
            $countries = array_unique($country_array);
    
        if (count($countries)<=1)
            sess::set_error('At least one country is required.');
    }
    
    if (sess::get('errors')) {
        ob_end_clean();
        header('location: signup.php');
        exit();
    }

	$section = "utilities";
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Signup for My Eguide</title>
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
				<h2>ElectionGuide</h2>
				<?php
					// INCLUDES
            		include( "admin/includes/class.phpmailer.php" );
            		include( "admin/includes/Mailer.Class.php" );

            		$_POST['date_created'] = date('Y-m-d');
            		$auth = Common::generate_auth(40);
            		$_POST['auth_string'] = $auth;
            		$auth_string = md5($_POST['email'].$auth);
            		if(is_array($_POST['issues']))
          		        $_POST['issues'] = implode(',', $_POST['issues']);
                    else
                        $_POST['issues'] = '';

            		//$this->sanity_check();

            		// DATABASE INSERT
            		foreach($_POST as $key => $value)
                        $_POST[$key] = Common::add_them_slashes($value);
                        
            		$db = new Db();
            		$db->AutoInsert("myeguide");

            		// EMAIL GENERATION
            		include( "includes/issues-array.php" );
            		include( "includes/email/eguide-signup.php" );

            		//$contacts[0] = "psalonen@esitemarketing.com";
            		$contacts[0] = "oluca@ifes.org";
            		$subject = "Election Guide - Eguide Signup";

            		$username = $_POST['first_name']." ".$_POST['last_name'];

            		for( $i = 0; $i < count( $contacts ); $i++ ) {
            			$Mailer = new Mailer($contacts[$i], 'eguide@ifes.org', $username, $subject, $html_body);
            			$Mailer->SendMail();
            		}

            		include( "includes/email/eguide-confirm.php" );
            		$conf = new Mailer($_POST['email'], 'eguide@ifes.org', 'My Eguide', 'Please confirm your email', $html_conf);
            		$conf->SendMail();
            		
            		echo '<p>Dear '.trim(stripslashes($_POST['first_name'])).', </p>
                    <p>Thank you for signing up for ElectionGuide services.
                    We have sent you an email with the activation link. Please check your email and
                    activate your account so you can start receiving our emails and/or using My Eguide.</p>
                    <p>Regards,<br />Election Guide</p>';
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
