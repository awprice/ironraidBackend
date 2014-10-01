<?php

	$username = $_GET['username'];
	$key = $_GET['key'];
	$reward = 5;
	$present = false;
	$command = 'broadcast%20§9' . $username . '%20§7just%20joined%20the%20forums%20and%20received%20§9' . $reward . '§7%20Vote%20Tokens!%20Join%20here:%20§9ironraid.co/forums';

	$servers = array('localhost:25586', 'localhost:25587', 'localhost:25588', 'localhost:25589', 'localhost:25590', 'localhost:25591');

	if ($key == 'PASSWORD') {

		$link = mysql_connect('HOSTNAME', 'USER', 'PASSWORD') or die('Could not connect: ' . mysql_error());
		mysql_select_db('global') or die('Could not select database');

		$query = "SELECT * FROM votes WHERE playername = '" . $username . "'";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {

			$present = true;

		}

		if ($present == false) {

			$query = "INSERT INTO `global`.`votes` (`id`, `playername`, `votes`) VALUES (NULL, '" . $username . "', '" . $reward . "')";
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		} else {

			$query = "UPDATE votes set votes = votes + " . $reward . " WHERE playername = '" . $username . "'";
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());

		}

		foreach ($servers as &$value) {
			
			$ch = curl_init('http://mc.ironraid.co/backend/rcon/rcon.php?cmd=' . $command . '&server=' . $value . '&pass=PASSWORD');

			curl_exec($ch);

			curl_close($ch);			

		}

	}

?>