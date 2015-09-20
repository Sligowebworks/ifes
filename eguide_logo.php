<?php
    include('includes/conf.php');
    include('includes/eguide_navi.php');
	$section = "eguide";

if ($_POST['op']=='login') {
	$user = $_POST['username'];
	$pass = $_POST['password'];
	if ($user!='' && $pass!='') {
		sess::is_valid($user,$pass);
	}
} else if ($_POST['op']=='forgot') {
	$email = trim($_POST['email']);

    if (trim($email)=='')
        sess::set_error('Email Address is required.');

    if (trim($email)!='') {
        if (!eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}", $email)){
            sess::set_error('Email Address is not valid. Please choose another.');
        }
    }

    if (sess::get('errors')) {
        ob_end_clean();
        header('location: eguide.php');
        exit();
    }

    $db = new Db();
    $db->Query("SELECT password FROM myeguide WHERE email='".$email."' AND signup_eguide=1");
    if ($db->GetAffectedRows()==1) {
        $data = $db->fetchAssoc();
        include( "admin/includes/class.phpmailer.php" );
  		include( "admin/includes/Mailer.Class.php" );
  		$subject = "Election Guide - Lost Password";

        $html_body = '<p>You or someone has requested the My Eguide password to be sent to this address.
        If it was not you, you can simply ignore this email.</p>
        <p>Your password is: '.$data['password'].'</p>
        <p>Regards,<br />My Eguide Staff</p>';
        $Mailer = new Mailer($email, 'eguide@ifes.org', 'Election Guide', $subject, $html_body);
        $Mailer->SendMail();
        sess::set_msg('Your password has been sent.');
        header('location: eguide.php');
        exit();
    } else {
        sess::set_error('Invalid email address.');
        header('location: eguide.php');
        exit();
    }
}

	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - My Eguide</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/ifes.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/msgs.css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
</head>
<body id="body-<?php echo $section; ?>">
	<?php include( "includes/jump-links.php" ); ?>
	<div id="wrapper">
		<?php include( "includes/header.php" ); ?>
		<hr />
		<div id="main-wrapper">
			<div id="content-wrapper">
			
				<!-- Ads come here -->
		<img align="right" src="images/ads/samplead.jpg" />
							
				<h2>My Eguide:</h2>
				<?php
				if (sess::is_loggedin()) {
                    $country_array = array(
                        sess::get('country_1'),
                        sess::get('country_2'),
                        sess::get('country_3'),
                        sess::get('country_4'),
                        sess::get('country_5'));
                    foreach($country_array as $value) {
                        if ($value>0)
                            $mycountries[] = $value;
                    }
                    if (is_array($mycountries)) {
                        $mycountries = array_unique($mycountries);
                        include( "includes/eguide_countries.php" );
                        foreach($mycountries as $country) {
                            if(in_array($country, array_keys($countries)))
                                $mylinkedcountries[] = $country;
                        }
                    }

                    eguide_navi(TRUE);
                    Message::show_messages();
                    if (count($mylinkedcountries)==0) {
                        echo '<p>You haven\'t chosen any countries yet. Please Choose some in your
                        <a href="eguide-preferences.php">Countries</a> page.</p>';
                    } else if (count($mylinkedcountries)<=5) {
                        if (count($mylinkedcountries)<5) {
                            $count = (count($mylinkedcountries)==1) ? '1 country': count($mylinkedcountries).' countries';
                            echo '<p>You have '.$count.' set. Choose more in your <a href="eguide-preferences.php">Countries</a> page.</p>';
                        }
                        include('includes/search_countries.php');
                        include_once('includes/ElectionList.Class.php');
                        require_once("admin/includes/ElectionView.Class.php");
                        require_once("admin/includes/CountryView.Class.php");

        				$x=0;
        				foreach($mylinkedcountries as $key => $value) {
        					//$styler = ($x==1) ? 'float:right;' :'float:left;';
        					echo '<div class="country-meta" style="'.$styler.'">
                            <h3 class="country-name"><a style="text-decoration:none;color:#1e466e;" href="country.php?ID='.$value.'">'.$countries[$value].'</a></h3>';
                            ElectionList::show_eguide_upcoming($value);
                            ElectionList::show_eguide_past($value);
                            ElectionList::show_eguide_news($value);
                            ElectionList::get_eguide_links($value);

        					echo '<img src="images/eguide-wide-bottom.gif" alt="" /></div>';
        					$x++;
        					if ($x==2) {
                                $x=0;
                            }
                        }
                    }
                } else {
                    ?>
					<p>With My Eguide, you can generate election news and information
                    tailored to your specific country interests. This portal allows you to
                    select up to five countries, for which up-to-the-minute information will
                    be displayed on your My Eguide page. Each country will display the name
                    and the date of the next election event, and any relevant ElectionGuide
                    or ElectList news. <a href="signup.php">Create a My Eguide profile</a>
                    today!</p>
                    
                    <h2>Login</h2>
                    <?php Message::show_messages(); ?>
                    <form action="eguide.php" method="post">
                    <p><label for="username">Username:</label><br />
                        <input type="text" name="username" id="username" value="" />(Email)<br />
                        <label for="password">Password:</label><br />
                        <input type="password" id="password" name="password" value="" /><br />
                        <input type="submit" name="submit" value="Login" />
                        <input type="hidden" name="op" value="login" />
                    </p>
					</form>

    				<h2>Forgot your password?</h2>
                    <p>Please enter your email address and we will email you your password.</p>
                    <form action="eguide.php" method="post">
                    <p><label for="email">Email:</label>
                        <input type="text" name="email" id="email" value="" />
                        <input type="submit" name="submit" value="Send Password" />
                        <input type="hidden" name="op" value="forgot" />
                    </p>
                    </form>
               <?php }	?>
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
