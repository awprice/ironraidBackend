<?php

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

$startTime = microtime(true);
$fileDir = dirname(__FILE__);

require($fileDir . '/library/XenForo/Autoloader.php');
XenForo_Autoloader::getInstance()->setupAutoloader($fileDir . '/library');

XenForo_Application::initialize($fileDir . '/library', $fileDir);
XenForo_Application::set('page_start_time', $startTime);

$link_server = mysql_connect('HOSTNAME', 'USERNAME', 'PASSWORD') or die('Could not connect: ' . mysql_error());
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

				$userId = $line_forums['user_id'];
				$userGroupId = $ranks[$line_server['rank']];
				 
				$writer = XenForo_DataWriter::create('XenForo_DataWriter_User');
				if ($userId) {
					$writer->setExistingData($userId);
				}
				 
				$writer->set('user_group_id', $userGroupId);
				$writer->save();

				$count = $count + 1;

			}

		}

		mysql_free_result($result_forums);

		mysql_close($link_forums);

	}

}

echo $count . ' users changed.';

?>