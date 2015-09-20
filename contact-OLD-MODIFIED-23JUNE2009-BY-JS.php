<?php
    include( "includes/conf.php" );
	$section = "utilities";

	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";

function sql_quote( $value ) 
{ 
if( get_magic_quotes_gpc() ) 
{ 
      $value = stripslashes( $value ); 
} 
//check if this function exists 
if( function_exists( "mysql_real_escape_string" ) ) 
{ 
      $value = mysql_real_escape_string( $value ); 
} 
//for PHP version < 4.3.0 use addslashes 
else 
{ 
      $value = addslashes( $value ); 
} 
return $value; 
} 


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/ifes.css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
    <script language="javascript" type="text/javascript">
    <!--
    function reset_it(){
        document.getElementById("title").value='';
        document.getElementById("first_name").value='';
        document.getElementById("last_name").value='';
        document.getElementById("email").value='';
        document.getElementById("org_type").value='';
        document.getElementById("comments").value='';
    }

    //-->
    </script>
</head>

<body id="body-<?php echo $section; ?>">
	<?php include( "includes/jump-links.php" ); ?>
	<div id="wrapper">
		<?php include( "includes/header.php" ); ?>
		<hr />
		<div id="main-wrapper">
			<div id="content-wrapper">
			
			<!-- AD begins here -  OL Feb 28, 2006-->
			<div class="ad banner"><a href="http://www.codeinc.com/" target="_blank"><img src="images/ads/rotate/codeinc.gif" alt="Advertisement" /> </a></div>
			<!-- AD ends here-->
						
			
				<h2>Contact Us:</h2>
				<p>We are very interested in hearing about your feedback or
                questions about ElectionGuide as we strive to keep our information
                clear and up to date. Please complete the form below and we
                will respond shortly.</p>
                
                <p>* Required field</p>
                <?php
				Message::show_messages();
					
				// also in email file in includes/email/contact.php
                $org_types = array(''=>'Please Select',
                'Advertising/Media/Public Relation'=>'Advertising/Media/Public Relation',
                'Agriculture/Forestry'=>'Agriculture/Forestry',
                'Architecture/Engineering/Construction'=>'Architecture/Engineering/Construction',
                'Consulting'=>'Consulting',
                'Entertainment'=>'Entertainment',
                'Finance/Banking/Accounting'=>'Finance/Banking/Accounting',
                'Government'=>'Government',
                'Hospitality/Travel'=>'Hospitality/Travel',
                'Internet-Related Services'=>'Internet-Related Services',
                'Legal Services'=>'Legal Services',
                'Manufacturing/distribution'=>'Manufacturing/Distribution',
                'Marketing/Communications'=>'Marketing/Communications',
                'Medical Services'=>'Medical Services',
                'Nonprofit'=>'Nonprofit',
                'Printing/Graphics'=>'Printing/Graphics',
                'Sales'=>'Sales',
                'Software Development'=>'Software Development',
                'Television/Radio/Print Publishing'=>'Television/Radio/Print Publishing',
                'Transportation/Utilities'=>'Transportation/Utilities',
                'Wholesale/Retail'=>'Wholesale/Retail',
                'Other'=>'Other');
                ?>
                <form action="contact-process.php" method="post" name="contact-form" id="contact-form">
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label for="title">Title:</label></th>
								<td><?php echo Common::select_item('title', Common::get_prefixes(), sess::get('title'), ' id="title"'); ?></td>
							</tr>
							<tr>
								<th scope="row"><label for="first_name">First Name:</label></th>
								<td><input type="text" name="first_name" id="first_name" value="<?php echo sess::get('first_name'); ?>" /></td>
							</tr>
							<tr>
								<th scope="row"><label for="last_name">Last Name:</label></th>
								<td><input type="text" name="last_name" id="last_name" value="<?php echo sess::get('last_name'); ?>" /></td>
							</tr>
							<tr>
								<th scope="row"><label for="email">* Email:</label></th>
								<td><input type="text" name="email" id="email" value="<?php echo sess::get('email'); ?>" /></td>
							</tr>
							<tr>
								<th scope="row"><label for="org_type">Organization Type:</label></th>
								<td><?php echo Common::select_item('org_type', $org_types, sess::get('org_type'), ' id="org_type"'); ?></td>
							</tr>
							<tr>
								<th scope="row"><label for="comments">* Comments:</label></th>
								<td><textarea name="comments" id="comments"><?php echo sess::get('comments') ?></textarea></td>
							</tr>
							<tr>
								<th scope="row">Mail Format:</th>
								<td>
									<input type="radio" name="mail_format" id="mail_format_html" value="html" checked="checked" /> <label for="mail_format_html">HTML</label> 
									<input type="radio" name="mail_format" id="mail_format_text" value="text" /> <label for="mail_format_text">Plain Text</label>
								</td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" name="submit" id="submit" value="Send Comments" />
                                <input type="button" name="reset" id="reset" value="Clear" onclick="reset_it();" /></td>
							</tr>
						</tbody>
					</table>
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
