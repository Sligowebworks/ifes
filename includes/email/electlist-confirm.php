<?php

$html_conf = "<html>
<head>
    <title>Election Guide - Electlist Subscription</title>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
</head>
<body>
<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
<tr><td>
    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" style=\"font-family:Verdana;font-size:12px;\">
        <tr>
            <td align=\"center\"><p style=\"font-weight:bold;\">Election Guide<br />
            Electlist Subscription Pending Activation</p></td>
        </tr>
    </table>
    <hr width=\"97%\">
    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" style=\"font-family:Verdana;font-size:12px;\">
    <tr>
        <td>";
  		if (trim($_POST['prefix'])!='' && trim($_POST['last_name'])!='')
   	        $html_conf .= '<p>Dear '.trim($_POST['prefix']).' '.stripslashes(trim($_POST['last_name'])).', </p>';

        $html_conf .= '<p>Thank you for subscribing to Electlist. Please click the link below to confirm<br />
        <a href="http://72.3.240.213/ifes/electlist-confirm.php?ID='.$auth_string.'">http://72.3.240.213/ifes/electlist-confirm.php?ID='.$auth_string.'</a></p>
        <p>Regards,<br />
        Election Guide</p>
        
        </td>
    </tr></table>
    </td>
    </tr>
</table>
</body>
</html>';
?>
