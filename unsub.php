<?php
    include('includes/conf.php');
    include('includes/eguide_navi.php');
	$section = "eguide";

    if ($_POST['op']=='unsub') {
        if (($_POST['signup_electlist']==1 || $_POST['signup_eguide']==1) && trim($_POST['email'])!='') {
            $where = array();
            if ($_POST['signup_electlist']==1)
                $where[] = 'signup_electlist=0';

            if ($_POST['signup_eguide']==1)
                $where[] = 'signup_eguide=0';

            if ($_POST['signup_eguide']==1)
                $where[] = 'is_active=0';

            $db = new Db();
            $db->Query("SELECT id FROM myeguide WHERE email='".$_POST['email']."'");
            if ($db->GetAffectedRows()==1) {
                $db1 = new Db();
                $sql = "UPDATE myeguide SET ".implode(', ', $where)." WHERE email='".$_POST['email']."'";
                $db1->Query($sql);
            }
            sess::set_msg('Your subscription preferences have been updated.');
        } else {
            sess::set_error('No email address provided.');
        }
    }

echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Unsubscribe</title>
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
                <h2>Unsubscribe</h2>
                <?php Message::show_messages(); ?>
                <p>Please note that if you unsubscribe from My Eguide, you will not be able to login.</p>
                <form action="unsub.php" method="post" name="equide-form" id="equide-form">
                <input type="hidden" name="op" value="unsub" />
                <ul style="list-style-type:none;list-style-position:inside;">
                    <li><input type="checkbox" class="noborder" name="signup_electlist" id="signup_electlist" value="1" /> ElectList! Newsletter - a weekly newsletter with election-related news from around the world and the latest updates to ElectionGuide.</li>
                    <li><input type="checkbox" class="noborder" name="signup_eguide" id="signup_eguide" value="1" /> My Eguide Portal - election news and information tailored to your specific country interests.  Select up to five countries for which up-to-the-minute information will be displayed on your My Eguide page when you log in from the Election Guide site.</li>
                </ul>
                <table class="form-table"><tbody>
                <tr>
                    <th scope="row"><label for="email">* Email:</label></th>
                    <td><input type="text" name="email" id="email" style="width:200px;" value="" /></td>
                <tr>
                    <td></td>
                    <td><input type="submit" name="submit" id="submit" value="Unsubscribe" /></td>
                </tr>
                </tbody></table>
                </form>
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
