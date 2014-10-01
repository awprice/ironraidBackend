<?php

if ($_GET['key'] == 'PASSWORD') {

	$ranks = array(

		"owner" => "3",
		"headadmin" => "4",
		"admin" => "9",
		"helper" => "10",
		"platinum" => "8",
		"gold" => "7",
		"silver" => "6",
		"member" => "5",
		"player" => "2",

	);

	$count = 0;

	$link_server = mysql_connect('HOSTNAME', 'USER', 'PASSWORD') or die('Could not connect: ' . mysql_error());
	mysql_select_db('global') or die('Could not select database');

	$query_server = 'SELECT * FROM rank';

	$result_server = mysql_query($query_server) or die('Query failed: ' . mysql_error());

	while ($line_server = mysql_fetch_array($result_server, MYSQL_ASSOC)) {

		if ($line_server['rank'] == 'owner' || $line_server['rank'] == 'headadmin' || $line_server['rank'] == 'admin' || $line_server['rank'] == 'helper' || $line_server['rank'] == 'platinum' || $line_server['rank'] == 'gold' || $line_server['rank'] == 'silver' || $line_server['rank'] == 'member' || $line_server['rank'] == 'player') {

			$link_forums = mysql_connect('HOSTNAME', 'USER', 'PASSWORD') or die('Could not connect: ' . mysql_error());
			mysql_select_db('forums') or die('Could not select database');

			$query_forums = "SELECT * FROM xf_user WHERE username = '" . $line_server['player'] . "'";

			$result_forums = mysql_query($query_forums) or die('Query failed: ' . mysql_error());

			$line_forums = mysql_fetch_array($result_forums, MYSQL_ASSOC);

			if ($line_forums != 0) {

				$current_user_group = $ranks[$line_server['rank']];

				if ($current_user_group != $line_forums['user_group_id']) {

					$count = $count + 1;

				}

			}

			mysql_free_result($result_forums);

			mysql_close($link_forums);

		}

	}

	if ($count == 0) {

		$to  = 'PERSON TO RECEIVE MAIL' . ', ';
		$to .= 'support@ironraid.co';

		$subject = '24h Server-Forums Sync Reminder';

		$message = '
		<html>
		<head>
		  <title>24h Server-Forums Sync Reminder</title>
		</head>
		<body>
			<h1><font color="#5cb85c">There are no users that needed to be synced!</font></h1>
			<h2>Check up later with the link below if this changes.<h2>
			<a href="https://ironraid.co/backend/rank-forums/">https://ironraid.co/backend/rank-forums/</a>
		</body>
		</html>
		';

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: 24h Server-Forums Sync Reminder <backend@ironraid.co>' . "\r\n";

		mail($to, $subject, $message, $headers);

	} else {

		$to  = 'PERSON TO RECEIVE MAIL' . ', ';
		$to .= 'support@ironraid.co';

		$subject = '24h Server-Forums Sync Reminder';

		$message = '
		<html>
		<head>
		  <title>24h Server-Forums Sync Reminder</title>
		</head>
		<body>
			<h1><font color="#d9534f">There are ' . $count . ' users that needed to be synced.</font></h1>
			<h2>Please use the link below to make these changes.<h2>
			<a href="https://ironraid.co/backend/rank-forums/">https://ironraid.co/backend/rank-forums/</a>
		</body>
		</html>
		';

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: 24h Server-Forums Sync Reminder <backend@ironraid.co>' . "\r\n";

		mail($to, $subject, $message, $headers);

	}

}

?>