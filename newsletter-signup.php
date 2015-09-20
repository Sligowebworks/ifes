<?php
    include( "includes/conf.php" );
	$section = "newsletter";
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
            <h2>Signup for Electlist</h2>
        <p>* Required field</p>
<?php
        Message::show_messages();
        $countries = Common::get_countries_from_db(TRUE, 'Select Country');

        echo '<form action="electlist-process.php" method="post" name="equide-form" id="equide-form">
        <table class="form-table"><tbody>
        <tr>
        <th scope="row"><label for="country">Title: </label></th>
        <td>'.Common::select_item('prefix', Common::get_prefixes(), $this->prefix).'</td>
        </tr>
        <tr>
        <th scope="row"><label for="first_name">* First Name:</label></th>
        <td><input type="text" name="first_name" id="first_name" value="'.$this->first_name.'" /></td>
        </tr>
        <tr>
        <th scope="row"><label for="last_name">* Last Name:</label></th>
        <td><input type="text" name="last_name" id="last_name" value="'.$this->last_name.'" /></td>
        </tr>
        <tr>
        <th scope="row"><label for="email">* Email:</label></th>
        <td><input type="text" name="email" id="email" value="'.$this->email.'" /></td>
        </tr>
        <tr>
        <th scope="row"><label for="company">Organization:</label></th>
        <td><input type="text" name="company" id="company" value="'.$this->company.'" /></td>
        </tr>
        <tr>
        <th scope="row"><label for="interest_country">Select Country: </label></th>
        <td>'.Common::select_item('interest_country', $countries, $this->interest_country).'<br />
        Please select from the dropdown the country you are most interested in receiving information about.</td>
        </tr>
        <tr>
        <td></td>
        <td><input type="submit" name="submit" id="submit" value="Signup" /></td>
        </tr>
        </tbody></table></form>';
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
