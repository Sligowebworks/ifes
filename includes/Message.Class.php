<?php

class Message {

    function Message() {

    }
    
    function show_messages() {
  		if (sess::get('errors')==TRUE) {
  		    echo '<p class="errors">'.(is_array(sess::get('errmsg')) ? implode('<br />',sess::get('errmsg')) : sess::get('errmsg')).'</p>';
  		    sess::set('errors', FALSE);
  		    sess::set('errmsg', '');
  		}
    		
  		if (sess::get('msgs')==TRUE) {
  		    echo '<p class="msgs">'.(is_array(sess::get('msgmsg')) ? implode('<br />',sess::get('msgmsg')) : sess::get('msgmsg')).'</p>';
  		    sess::set('msgs', FALSE);
	    	sess::set('msgmsg', '');
  		}
    }
}
?>
