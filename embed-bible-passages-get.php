<?php
/*
 * Output Bible Passages for loading into <div id="scriptures"></div>
 */
 
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
	require_once('embed-bible-passages.php');
	if (is_object($bible_passage)) {
		$query_string = '';
		if (isset($_REQUEST['reading-plan']) && $_REQUEST['reading-plan']) {
			$query_string .= '&reading-plan='.$_REQUEST['reading-plan'];
		}
		if (isset($_REQUEST['audio-format']) && $_REQUEST['audio-format']) {
			$query_string .= '&audio-format='.$_REQUEST['audio-format'];
		}
		if (isset($_REQUEST['requested_date']) && $_REQUEST['requested_date']) {
			list($month, $day, $year) = explode('/', $_REQUEST['requested_date']);
			$query_string .= "&date=$year-$month-$day";
		}
		echo $bible_passage->getBiblePassage($query_string);
	} else {
		_e('Error: Could not load plugin.');
	}

?>