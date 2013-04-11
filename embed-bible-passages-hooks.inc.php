<?php

if (class_exists('EmbedBiblePassages')) {
	$bible_passage = new EmbedBiblePassages();
	if (isset($bible_passage)) {
		add_action('wp_head', array(&$bible_passage, 'addCSS'), 1);
		add_action('wp_enqueue_scripts', array(&$bible_passage, 'enqueueScripts'));
		add_action('wp_footer', array(&$bible_passage, 'addDatePicker'), 1);
	}
}

?>