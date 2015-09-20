<?php
        if (!($cnx = mysql_connect('localhost','eguide','n3w$iTE!'))) {
                die ("Could not connect: " . mysql_errors());
        } else {
                echo "<b>Worked</b>";
        }
?>
