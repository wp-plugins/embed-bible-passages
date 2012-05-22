<?php

if (class_exists('EmbedBiblePassages')) {
	$bible_passage = new EmbedBiblePassages();
	if (isset($bible_passage)) {
		add_action('wp_head', array(&$bible_passage, 'addCSS'), 1);
	}
}

?>