<?php

function eguide_navi($who=TRUE){
    echo '<p style="text-align:right">';
    echo ($who==TRUE)
        ? 'Welcome, '.sess::get('first_name').' '.sess::get('last_name')
        : '<a href="eguide.php">My Eguide</a>';
        
    echo ' | <a href="eguide-profile.php">Profile</a> |
        <a href="eguide-preferences.php">My Countries</a> |
        <a href="logout.php">Logout</a>
    </p>';
}
?>
