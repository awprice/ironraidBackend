<?php

	$username = $_GET['username'];
	$key = $_GET['key'];
	$thread_id = $_GET['id'];
	
	$command = 'broadcast%20§9' . $username . '%20§7just%20started%20a%20new%20thread%20on%20the%20forums!%20Check%20it%20here:%20§9';

	$servers = array('localhost:25586', 'localhost:25587', 'localhost:25588', 'localhost:25589', 'localhost:25590', 'localhost:25591');

	if ($key == 'PASSWORD') {

		$url = 'https://ironraid.co/forums/threads/' . $thread_id;

		$shortenedurl = file_get_contents('http://irnrd.net/link/create.php?url=' . $url);

		$command = $command . $shortenedurl;

		foreach ($servers as &$value) {
			
			$ch = curl_init('http://mc.ironraid.co/backend/rcon/rcon.php?cmd=' . $command . '&server=' . $value . '&pass=PASSWORD');

			curl_exec($ch);

			curl_close($ch);			

		}

	}

?>