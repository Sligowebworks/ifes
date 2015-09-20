<?php

class sess {
    var $user = '';
    var $pass = '';

    function sess() {

    }

    function is_valid($user,$pass) {
	
		$user = mysql_real_escape_string($user);
		$pass = mysql_real_escape_string($pass);
		
    	$sql = "SELECT id,first_name,last_name,email,date_created,issues,
        country_1,country_2,country_3,country_4,country_5 FROM myeguide
        WHERE email='".$user."' AND password='".$pass."' AND signup_eguide=1 AND is_active=1";
    	$Db = new Db;
    	$Db->Query($sql);
        if($data = $Db->fetchAssoc()) {
            sess::set('loggedin',TRUE);
            foreach($data as $key => $value)
                sess::set($key,$value);

            return TRUE;
        } else {
            sess::set('loggedin',FALSE);
            sess::set_error('Username and/or password do not match our records. Please try again.');
            return FALSE;
        }
    }
    
    function set_error($value) {
    	sess::set('errors',TRUE);
        sess::add('errmsg',$value);
    }
    
    function set_msg($value) {
    	sess::set('msgs',TRUE);
        sess::add('msgmsg',$value);
    }
            
    function unset_all() {
    	unset($_SESSION[USERSESS]);
    }
    
    function set($key,$value) {
        $_SESSION[USERSESS][$key] = $value;        
    }
        
    function is_set($key) {
        return ($_SESSION[USERSESS][$key]) ? TRUE : FALSE;
    }
    
    function get($key) {
        return $_SESSION[USERSESS][$key];
    }
    
    function add($key,$value) {
        $_SESSION[USERSESS][$key][] = $value;
    }
    
    function is_loggedin() {
        return $_SESSION[USERSESS]['loggedin'];
    }

    function is_premium() {
        return $_SESSION[USERSESS]['premium'];
    }
}
?>
