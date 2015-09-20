<?php
    include('includes/conf.php');
    include('includes/eguide_navi.php');
	$section = "eguide";
    if (!sess::is_loggedin()) {
        ob_end_clean();
        header('Location: eguide.php');
        exit();
    }

	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - My Eguide Profile</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/ifes.css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
</head>
<body id="body-<?php echo $section; ?>">
	<?php include("includes/jump-links.php"); ?>
	<div id="wrapper">
		<?php include("includes/header.php"); ?>
		<hr />
		<div id="main-wrapper">
			<div id="content-wrapper">
				<h2>My Eguide Profile:</h2>
				<?php eguide_navi(FALSE); ?>
				<p>Here you can edit your profile, update your password and subscriptions.
                If you update your email address changes will not take effect until you have
                activated your new email. An email with the activation link will be sent to
                your new email address.</p>
				<?php 
				$_POST['op'] = ($_POST['op']) ? $_POST['op'] :'edit';
				include_once('includes/Signup.Class.php');
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
