<?php

class Signup{

	function Signup() {

	}

    function sanity_check () {
        //$_POST['is_active'] = 0;
        if ($_POST['issues'])
            $_POST['issues'] = implode(',', $_POST['issues']);
        foreach($_POST as $key=>$value) {
            $_POST[$key] = Common::add_them_slashes($value);
        }
        
    }

    function get_info() {
        $id = settype(sess::get('id'), 'integer');
        $db = new Db;
        $db->Query("SELECT * FROM myeguide WHERE id=".$id);
        $data = $db->fetchAssoc();
        foreach($data as $key => $value) {
        	sess::set($key, $value);
        	$this->{$key} = $value;
        }
    }
    
    function update_subs() {
        $db = new Db;
		$id = settype(sess::get('id'), 'integer');
        $db->AutoUpdate('myeguide', ' WHERE id='.$id);
        sess::set_msg('Your Subscriptions have been updated.');
    }
    
    function update_password() {
        $db = new Db;
        $id = settype(sess::get('id'), 'integer');
        $db->AutoUpdate('myeguide', ' WHERE id='.$id);
        sess::set_msg('Your Password has been updated.');
    }
    
    function update_profile() {
        $db = new Db();
        $id = settype(sess::get('id'), 'integer');
        $db->Query("SELECT email, auth_string FROM myeguide WHERE id=".$id);
        $data = $db->fetchAssoc();
        if ($data['email'] != $_POST['email']) {
            include("admin/includes/class.phpmailer.php");
    		include("admin/includes/Mailer.Class.php");
            $_POST['pending_email'] = $_POST['email'];
            $_POST['email'] = $data['email'];
            $auth_string = md5($data['auth_string']);
            include("includes/email/eguide-confirm-email.php");
    		$conf = new Mailer($_POST['pending_email'], 'eguide@ifes.org', 'My Eguide', 'Please confirm your new email', $html_conf);
    		$conf->SendMail();
            sess::set_msg('We have sent you an email with the activation link. Please check your email and activate your new email.');
        }
        
        $this->sanity_check();
        $db = new Db;
		$id = settype(sess::get('id'), 'integer');
        $db->AutoUpdate('myeguide', ' WHERE id='.$id);
        sess::set_msg('Your Profile has been updated.');
        sess::set('issues', $_POST['issues']);
    }


    function cache_info() {
        foreach ($_POST as $key=>$value)
            $this->{$key} = $value;
    }

    function test_profile_info() {
        if (trim($_POST['first_name'])=='' || trim($_POST['last_name'])=='')
            sess::set_error('First and last names are required.');

        if (trim($_POST['email'])=='')
            sess::set_error('Email Address is required.');

        if (trim($_POST['email'])!=''){
            if (!eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}", $_POST['email'])){
                sess::set_error('Email Address is not valid. Please choose another.');
            }
        }
 
        if (trim($_POST['password'])!='') {
            if (trim($_POST['password']) != trim($_POST['password1'])) {
                sess::set_error('Passwords do not match.');
            }
        }
        $db1 = new Db();
        $db1->Query("SELECT id FROM myeguide WHERE email='".$_POST['email']."' AND id!=".sess::get('id'));
        if ($db1->GetAffectedRows()==1)
            sess::set_error('This email is already taken. Please Choose another.');
    }
    
    function test_password_info() {

        if (trim($_POST['password']) == '')
            sess::set_error('Password is required.');

        if (trim($_POST['password1']) == '')
            sess::set_error('Retyping Password is required.');

        if (trim($_POST['password']) != trim($_POST['password1']))
            sess::set_error('Passwords do not match.');
    }
    
    function test_signup_info() {
        if (trim($_POST['first_name'])=='' || trim($_POST['last_name'])=='')
            sess::set_error('First and last names are required.');

        if (trim($_POST['email'])=='')
            sess::set_error('Email Address is required.');
            
        $db1 = new Db();
        $db1->Query("SELECT id FROM myeguide WHERE email='".$_POST['email']."'");
        if ($db1->GetAffectedRows()==1)
            sess::set_error('This email is already taken. Please Choose another.');
                
        if (trim($_POST['email'])!=''){
            if (!eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}", $_POST['email'])){
                sess::set_error('Email Address is not valid. Please choose another.');
            }
        }
    
        if (trim($_POST['password']) == '') 
            sess::set_error('Password is required.');
            
        if (trim($_POST['password1']) == '') 
            sess::set_error('Retyping Password is required.');
            
        if (trim($_POST['password']) != trim($_POST['password1'])) 
            sess::set_error('Passwords do not match.');
    }

    function profile_form() {
        $this->get_info();
        Message::show_messages();
        $chk = ($this->mail_format=='HTML') ? ' checked="checked"': '';
        $chk1 = ($this->mail_format=='Plain Text') ? ' checked="checked"': '';
       $ages = array(''=>'Please Select',
            'Under 18'=>'Under 18',
            '18-25'=>'18-25',
            '26-29'=>'26-29',
            '30-39'=>'30-39',
            '40-49'=>'40-49',
            '50-59'=>'50-59',
            'Over 60'=>'Over 60');

        $degrees = array(''=>'Please Select',
        'Grammar/Middle School'=>'Grammar/Middle School',
        'High School Diploma'=>'High School Diploma',
        'Some College'=>'Some College',
        'Bachelor\'s Degree'=>'Bachelor\'s Degree',
        'Some Graduate School'=>'Some Graduate School',
        'Graduate Degree'=>'Graduate Degree',
        'PhD'=>'PhD',
        'Other'=>'Other');

        $occupations = array(''=>'Please Select',
        'Accounting/Finance'=>'Accounting/Finance',
        'Executive/Senior Management'=>'Executive/Senior Management',
        'Professional/Managerial'=>'Professional/Managerial',
        'Technical/Engineering'=>'Technical/Engineering',
        'Administrative/Secretarial'=>'Administrative/Secretarial',
        'Sales/Marketing/Advertising'=>'Sales/Marketing/Advertising',
        'Customer Service/Support'=>'Customer Service/Support',
        'College/University Faculty'=>'College/University Faculty',
        'College/University Student'=>'College/University Student',
        'K-12 Student'=>'K-12 Student',
        'Writer/Journalist'=>'Writer/Journalist',
        'Homemaker'=>'Homemaker',
        'Retired'=>'Retired',
        'Currently Not Employed'=>'Currently Not Employed',
        'Other'=>'other');

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

        include( "includes/issues-array.php" );
        include( "includes/search_countries.php" );

        $signupcountry = array(''=>'Please Select');
        foreach($countries as $key=>$value)
		  $signupcountry[$key] = $value;
		  
        include( "includes/issues-array.php" );
        echo '<form action="eguide-profile.php" method="post" name="equide-form" id="equide-form">
        <input type="hidden" name="op" value="update" />
        <table class="form-table"><tbody>
        <tr>
            <th scope="row"><label for="first_name">* First Name:</label></th>
            <td><input type="text" name="first_name" id="first_name" style="width:200px;" value="'.$this->first_name.'" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="last_name">* Last Name:</label></th>
            <td><input type="text" name="last_name" id="last_name" style="width:200px;" value="'.$this->last_name.'" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="email">* Email:</label></th>
            <td><input type="text" name="email" id="email" style="width:200px;" value="'.$this->email.'" />';
            if ($this->pending_email!='')
                echo '[change pending]';
            echo '</td>
        </tr>
        <tr>
            <th scope="row"><label for="mail_format">* Mail Format:</label></th>
            <td><input type="radio" name="mail_format" value="HTML"'.$chk.' /> HTML
                <input type="radio" name="mail_format" value="Plain Text"'.$chk1.' /> Plain Text
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="age">* Age:</label></th>
            <td>'.Common::select_item('age', $ages, $this->age).'</td>
        </tr>
        <tr>
            <th scope="row"><label for="gender">* Gender:</label></th>
            <td><input type="radio" name="gender" value="Male"'.($this->gender=='Male'?' checked="checked"':'').' /> Male
                <input type="radio" name="gender" value="Female"'.($this->gender=='Female'?' checked="checked"':'').' /> Female
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="education">* Education:</label></th>
            <td>'.Common::select_item('education', $degrees, $this->education, ' style="width:250px;"').'</td>
        </tr>
        <tr>
            <th scope="row"><label for="country">* Country:</label></th>
            <td>'.Common::select_item('country', $signupcountry, $this->country, ' style="width:250px;"').'</td>
        </tr>
        <tr>
            <th scope="row"><label for="occupation">* Occupation:</label></th>
            <td>'.Common::select_item('occupation', $occupations, $this->occupation, ' style="width:250px;"').'</td>
        </tr>
        <tr>
            <th scope="row"><label for="company"> Organization:</label></th>
            <td><input type="text" name="company" id="company" style="width:200px;" value="'.$this->company.'" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="company_type"> Organization Type:</label></th>
            <td>'.Common::select_item('company_type', $org_types, $this->company_type, ' style="width:250px;"').'</td>
        </tr>
        <tr>
            <th scope="row"> Issues of Focus:</th>
            <td>';
            $myissues = explode(',', $this->issues);
            foreach($issues as $key=>$value) {
                $sel = (in_array($key, $myissues)) ? ' checked="checked"': '';
                echo '<input type="checkbox" name="issues[]" id="issues_'.$key.'" value="'.$key.'"'.$sel.' />
                <label for="issues_'.$key.'">'.$value.'</label><br />';
            }
        echo '</td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" id="submit" value="Update Profile" /></td>
        </tr>
        </tbody></table></form>';
        $this->password_form();
        if ($this->pending_email=='')
            $this->subscriptions();
        else
            $this->subscriptions_pending();
    }
    
    function password_form() {

        echo '<h2>Update Your Password</h2>
        <form action="eguide-profile.php" method="post" name="equide-form" id="equide-form">
        <input type="hidden" name="op" value="updatepass" />
        <table class="form-table"><tbody>
        <tr>
            <th scope="row"><label for="password">* Password:</label></th>
            <td><input type="password" name="password" id="password" value="" /></td>
        </tr>
        <tr>
            <th scope="row"><label for="password1">* Retype Password:</label></th>
            <td><input type="password" name="password1" id="password1" value="" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" id="submit" value="Update Password" /></td>
        </tr>
        </tbody></table></form>';

    }
    
    function subscriptions() {
        $this->get_info();
        echo '<a name="subs"></a><h2>Update Your Subscriptions</h2>
        <form action="eguide-profile.php" method="post" name="equide-form" id="equide-form">
        <input type="hidden" name="op" value="updatesubs" />
        <ul style="list-style-type:none;list-style-position:inside;">
            <li><input type="checkbox" class="noborder" name="signup_electlist" id="signup_electlist" value="1"'.(sess::get('signup_electlist')==1?' checked="checked"':'').' /> ElectList! Newsletter - a weekly newsletter with election-related news from around the world and the latest updates to ElectionGuide.</li>
        </ul>
        <input type="submit" name="submit" id="submit" style="margin-left:150px;" value="Update Subscriptions" />
        
        </form>';
    }
    
    function subscriptions_pending() {

        echo '<a name="subs"></a><h2>Update Your Subscriptions</h2>
        <p>Your email address is currently not active. Please activate your email address before changing your email subscriptions preferences.</p>';
    }
}

switch($_POST['op']) {
    case 'save':
    $grp = new Signup;
    $grp->cache_info();
    $grp->test_signup_info();
    if (sess::get('errors')==TRUE) {
        $grp->signup_form();
    } else {
        $grp->save_signup();
    }
    break;
    
    case 'edit':
    $s = new Signup();
	$s->profile_form();
	break;

    case 'updatesubs':
    $grp = new Signup();
    $grp->cache_info();
    $grp->update_subs();
    ob_end_clean();
    header("Location: eguide.php");
    break;
    
    case 'updatepass':
    $grp = new Signup();
    $grp->cache_info();
    $grp->test_password_info();
    if (sess::get('errors')==TRUE) {
        $grp->profile_form();
    } else {
        $grp->update_password();
        ob_end_clean();
        header("Location: eguide.php");
    }
    break;
    
    case 'update':
    $grp = new Signup();
    $grp->cache_info();
    $grp->test_profile_info();
    if (sess::get('errors')==TRUE) {
        $grp->profile_form();
    } else {
        $grp->update_profile();
        ob_end_clean();
        header("Location: eguide.php");
    }
    break;
    
    default:
    $s = new Signup();
	$s->signup_form();
	break;
}
?>
