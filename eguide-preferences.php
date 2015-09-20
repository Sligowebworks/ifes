<?php
    include('includes/conf.php');
    include('includes/eguide_navi.php');
	$section = "eguide";
    if (!sess::is_loggedin()) {
        ob_end_clean();
        header('Location: eguide.php');
        exit();
    }
    
   	if($_POST['submit']) {
        $arr[] = (is_numeric($_POST['country_1'])) ? $_POST['country_1']:0;
        $arr[] = (is_numeric($_POST['country_2'])) ? $_POST['country_2']:0;
        $arr[] = (is_numeric($_POST['country_3'])) ? $_POST['country_3']:0;
        $arr[] = (is_numeric($_POST['country_4'])) ? $_POST['country_4']:0;
        $arr[] = (is_numeric($_POST['country_5'])) ? $_POST['country_5']:0;
        $arr = array_unique($arr);
        $x=1;
        foreach($arr as $value) {
            if ($value!=0) {
                $array[$x] = $value;
                $x++;
            }
        }
        //$array = array_pad($new, 5, 0);
        $array[1] = (is_numeric($array[1])) ? $array[1]:0;
        $array[2] = (is_numeric($array[2])) ? $array[2]:0;
        $array[3] = (is_numeric($array[3])) ? $array[3]:0;
        $array[4] = (is_numeric($array[4])) ? $array[4]:0;
        $array[5] = (is_numeric($array[5])) ? $array[5]:0;
        $db = new Db();
        $db->Query("UPDATE myeguide SET
        country_1=".$array[1].",
        country_2=".$array[2].",
        country_3=".$array[3].",
        country_4=".$array[4].",
        country_5=".$array[5]."
        WHERE id=".sess::get('id'));
        sess::set_msg('Your country preferences have been updated.');
        sess::set('country_1', $array[1]);
        sess::set('country_2', $array[2]);
        sess::set('country_3', $array[3]);
        sess::set('country_4', $array[4]);
        sess::set('country_5', $array[5]);
        ob_end_clean();
        header('location:eguide.php');
        exit();
	}
	
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - My Eguide Preferences</title>
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
				<h2>My Eguide Preferences:</h2>
				<?php
				eguide_navi(FALSE);
                Message::show_messages();
                
                echo '<form action="eguide-preferences.php" method="post" name="equide-form" id="equide-form">
				<h3>My Countries:</h3>';
				include( "includes/eguide_countries.php" );
                $signupcountry = array(''=>'Please Select');
                foreach($countries as $key=>$value)
                    $signupcountry[$key] = $value;

                for($x=1; $x<=5; $x++) {
                    echo '<div class="countrypicker"><label for="country_'.$x.'"></label>
                    '.Common::select_item("country_".$x, $signupcountry, sess::get('country_'.$x), ' style="width:300px;"').'</div>';
                }
                ?><input type="submit" name="submit" id="submit" value="Choose" /><br />
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
