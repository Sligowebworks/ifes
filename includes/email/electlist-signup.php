<?php

	$html_body = "<html>
		<head>
			<title>Election Guide - Electlist Subscription</title>
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
		</head>
		<body>
			<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
				<tr>
					<td>
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" style=\"font-family:Verdana;font-size:12px;\">
							<tr>
								<td align=\"center\"><p style=\"font-weight:bold;\">Election Guide<br />
                                Electlist Subscription Pending Activation</p></td>
							</tr>
						</table>
						<hr width=\"97%\">
						<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" style=\"font-family:Verdana;font-size:12px;\">
							<tr>
								<td width=\"25%\" style=\"font-weight:bold;\">Date:</td>
								<td width=\"75%\">".date("d/m/Y")."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold;\">Time:</td>
								<td>".date( "g:i a" )."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold;\">Name:</td>
								<td>".stripslashes(trim($_POST['prefix']." ".$_POST['first_name']." ".$_POST['last_name']))."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold;\">E-mail Address:</td>
								<td>".$_POST['email']."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold;\">Organization:</td>
								<td>".stripslashes(trim($_POST['company']))."</td>
							</tr>
							<tr>
								<td valign=\"top\" style=\"font-weight:bold;\">Country:</td>
								<td>".$country_name."</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</body>
	</html>";

?>
