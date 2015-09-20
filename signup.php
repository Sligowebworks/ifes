<?php
    include( "includes/conf.php" );
    $section = "eguide";
    
    if (sess::is_loggedin()) {
        header('location: eguide-profile.php#subs');
        exit();
    }
    
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
	if (sess::get('gender')=='' || !sess::get('gender'))
        sess::set('gender','Male');
    sess::set('signup_eguide', (sess::get('signup_eguide')!=1)?0:1);
    sess::set('signup_electlist', (sess::get('signup_electlist')!=1)?0:1);
    
    if (strpos($_SERVER['HTTP_REFERER'], 'eguide.php')) {
        sess::set('signup_eguide', 1);
        sess::set('signup_electlist', 0);
        $section = "eguide";
    }
    if (strpos($_SERVER['HTTP_REFERER'], 'newsletter.php')) {
        sess::set('signup_electlist', 1);
        sess::set('signup_eguide', 0);
        $section = "newsletter";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>IFES Election Guide - Signup</title>
	<link rel="stylesheet" type="text/css" media="screen" href="css/ifes.css" />
	<link rel="home" title="Home" href="http://www.electionguide.org/" />
<script language="javascript" type="text/javascript">
    <!--
    function getElement(e,f){
        if(document.layers){
            f=(f)?f:self;
            if(f.document.layers[e]) {
                return f.document.layers[e];
            }
            for(W=0;i<f.document.layers.length;W++) {
                return(getElement(e,fdocument.layers[W]));
            }
        }
        if(document.all) {
            return document.all[e];
        }
        return document.getElementById(e);
    }

    function show_eguide() {
        var extra = getElement("eguide_options");
        extra.style.display = 'block';
    }

    function hide_eguide() {
        var extra = getElement("eguide_options");
        extra.style.display = 'none';
    }
    //-->
    </script>
    <script language="javascript" type="text/javascript">
    <!--
    function reset_it(){
        document.getElementById("first_name").value='';
        document.getElementById("last_name").value='';
        document.getElementById("email").value='';
        document.getElementById("age").value='';
        document.getElementById("education").value='';
        document.getElementById("country").value='';
        document.getElementById("occupation").value='';
        document.getElementById("company").value='';
        document.getElementById("company_type").value='';
        if (document.getElementById("password")) {
            document.getElementById("password").value='';
            document.getElementById("password1").value='';
        }
        
        if (document.getElementById("country_1")) {
            document.getElementById("country_1").value='';
            document.getElementById("country_2").value='';
            document.getElementById("country_3").value='';
            document.getElementById("country_4").value='';
            document.getElementById("country_5").value='';
        }
    }

    //-->
    </script>
</head>

<body id="body-<?php echo $section; ?>" onload="<?php echo (sess::get('signup_eguide')==0)?'hide_eguide();':'show_eguide();'; ?>">
	<?php include( "includes/jump-links.php" ); ?>
	<div id="wrapper">
		<?php include( "includes/header.php" ); ?>
		<hr />
		<div id="main-wrapper">
			<div id="content-wrapper">
        <?php
        //include( "includes/Signup.Class.php" );
        include( "includes/signup-arrays.php" );
        include( "includes/issues-array.php" );
        include( "includes/search_countries.php" );
        
        $signupcountry = array(''=>'Please Select');
        foreach($countries as $key=>$value)
            $signupcountry[$key] = $value;
        
	    echo '<h2>Registration:</h2>';
	    Message::show_messages();
        echo '<form action="eguide-process.php" method="post" name="equide-form" id="equide-form">
        <table class="form-table"><tbody>
        <tr>
            <th scope="row"><label for="first_name">* First Name:</label></th>
            <td><input type="text" name="first_name" id="first_name" value="'.sess::get('first_name').'" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="last_name">* Last Name:</label></th>
            <td><input type="text" name="last_name" id="last_name" value="'.sess::get('last_name').'" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="email">* Email:</label></th>
            <td><input type="text" name="email" id="email" value="'.sess::get('email').'" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="mail_format">* Mail Format:</label></th>
            <td><input type="radio" name="mail_format" value="HTML" checked="checked" /> HTML
                <input type="radio" name="mail_format" value="Plain Text" /> Plain Text</td>
        </tr>
        <tr>
            <th scope="row"><label for="age">* Age:</label></th>
            <td>'.Common::select_item('age', $ages, sess::get('age')).'</td>
        </tr>
        <tr>
            <th scope="row"><label for="gender">* Gender:</label></th>
            <td><input type="radio" name="gender" value="Male"'.(sess::get('gender')=='Male'?' checked="checked"':'').' /> Male
                <input type="radio" name="gender" value="Female"'.(sess::get('gender')=='Female'?' checked="checked"':'').' /> Female</td>
        </tr>
        <tr>
            <th scope="row"><label for="education">* Education:</label></th>
            <td>'.Common::select_item('education', $degrees, sess::get('education'), ' id="education"').'</td>
        </tr>
        <tr>
            <th scope="row"><label for="country">* Country:</label></th>
            <td>'.Common::select_item('country', $signupcountry, sess::get('country'), ' style="width:250px;" id="country"').'</td>
        </tr>
        <tr>
            <th scope="row"><label for="occupation">* Occupation:</label></th>
            <td>'.Common::select_item('occupation', $occupations, sess::get('occupation'), ' id="occupation"').'</td>
        </tr>
        <tr>
            <th scope="row"><label for="company"> Organization:</label></th>
            <td><input type="text" name="company" id="company" value="'.sess::get('company').'" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="company_type"> Organization Type:</label></th>
            <td>'.Common::select_item('company_type', $org_types, sess::get('company_type'), ' id="company_type"').'</td>
        </tr>
        <tr>
            <th scope="row"> Issues of Focus:</th>
            <td>';
            //print_r(sess::get('issues'));
            //$myissues = explode(',', sess::get('issues'));
            foreach($issues as $key=>$value) {
                if (is_array(sess::get('issues')))
                    $sel = (in_array($key, sess::get('issues'))) ? ' checked="checked"': '';
                echo '<input type="checkbox" name="issues[]" id="issues_'.$key.'" value="'.$key.'"'.$sel.' />
                <label for="issues_'.$key.'">'.$value.'</label><br />';
            }
        echo '</td>
        </tr>
        </tbody></table>

        <table style="border:1px solid silver;padding:5px;margin:5px;"><tr>
            <td style="padding:5px;" valign="top"><input type="checkbox" class="noborder" name="signup_electlist" id="signup_electlist" value="1"'.(sess::get('signup_electlist')==1?' checked="checked"':'').' /></td>
            <td style="padding:5px;" valign="top"><p style="margin-bottom:0;"><strong>ElectList! Newsletter</strong> - a weekly newsletter with election-related news
            from around the world and the latest updates to ElectionGuide.</p></td>
        </tr></table>
        <table style="border:1px solid silver;padding:5px;margin:5px;"><tr>
            <td style="padding:5px;" valign="top"><input type="checkbox" class="noborder" name="signup_eguide" id="signup_eguide" value="1"'.(sess::get('signup_eguide')==1?' checked="checked"':'').' onclick="if(this.checked==true)show_eguide();else hide_eguide();"/></td>
           <td style="padding:5px;" valign="top"><p style="margin-bottom:0;"><strong>My Eguide Portal</strong> - election news and information tailored to your specific country interests. Select up to five countries for which up-to-the-minute information will be displayed on your My Eguide page when you log in from the Election Guide site.</p></td>
        </tr></table>
        
        <p>Already at My Eguide? If you already have a My Eguide account, please
        <a href="eguide.php">login</a> to edit your subscription information.</p>
        
        <div id="eguide_options">
        <h3>Additional information for subscribers to My Eguide Portal</h3>
        <p>Please create a password for your Eguide portal. Your password must be
        at least 6 characters long, and may include upper- and lower-case letters
        and numbers, but not spaces. Your password is are case sensitive. You will
        need this information to manage your MyEguide account as needed.</p>
        
        <table class="form-table"><tbody>
        <tr>
            <th scope="row"><label for="password">* Password:</label></th>
            <td><input type="password" name="password" id="password" value="" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="password1">* Retype Password:</label></th>
            <td><input type="password" name="password1" id="password1" value="" /></td>
        </tr>
        </tbody></table>
        
        <h3>My Eguide Country selections:</h3>
        <p>Please choose up to five countries for which we will display upcoming
        elections and news updates. At least one country is required.</p>
        <table class="form-table"><tbody>';
        
        include( "includes/eguide_countries.php" );

        $signupcountry = array(''=>'Please Select');
        foreach($countries as $key=>$value)
            $signupcountry[$key] = $value;
        for ($x=1; $x<=5;$x++) {
            echo '<tr>
                <th scope="row"><label for="country_'.$x.'">'.($x==1?'*':'').' Country '.$x.':</label></th>
                <td>'.Common::select_item('country_'.$x, $signupcountry, sess::get('country_'.$x), ' style="width:250px;" id="country_'.$x.'"').'</td>
            </tr>';
        }
        echo '</tbody></table>
        </div>
        <div style="text-align:center;">
            <input type="submit" name="submit" id="submit" value="Signup" />
            <input type="button" name="reset" id="reset" onclick="reset_it();" value="Clear" />
        </div></form>';
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
