<?php

	$html_conf = "<html>
		<head>
			<title>Election Guide - Eguide Email Change</title>
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
		</head>
		<body>
			<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
				<tr>
					<td>
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" style=\"font-family:Verdana;font-size:12px;\">
							<tr>
								<td align=\"center\"><p style=\"font-weight:bold;\">Election Guide<br />
                                Eguide Email Change</p></td>
							</tr>
						</table>
						<hr width=\"97%\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" style=\"font-family:Verdana;font-size:12px;\">
							<tr>
								<td>";

                        if (trim($_POST['title'])!='' && trim($_POST['last_name'])!='')
					       	$html_conf .= '<p>Dear '.trim($_POST['title']).' '.stripslashes(trim($_POST['last_name'])).', </p>';

                        $html_conf .= '<p>Thank you for updating your profile at My Eguide. Please click the link below to confirm<br />
                        <a href="http://72.3.240.213/ifes/confirm.php?IDE='.$auth_string.'">http://72.3.240.213/ifes/confirm.php?IDE='.$auth_string.'</a></p>
                        <p>Regards,<br />
						Election Guide</p>
						     
						</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
		</body>
	</html>';
?>
