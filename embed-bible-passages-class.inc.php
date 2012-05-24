<?php

class EmbedBiblePassages {
	/* Shortcode of the form [embed_bible_passage reading-plan='bcp'] are replaced by the scriptures from a Bible Reading Plan
	 * of http://www.esvapi.org/api#readingPlanQuery.
	 */

	protected $access_key		= 'IP';
	// NOTE THAT THE FOLLOWING COPYRIGHT NOTICE FROM THE SOURCE OF THE TEXT CROSSWAY BIBLE MUST BE KEPT ON THE PAGE.
	protected $esv_copyright	= 'Scripture taken from The Holy Bible, English Standard Version. Copyright &copy;2001 by <a href="http://www.crosswaybibles.org" target="_blank">Crossway Bibles</a>, a publishing ministry of Good News Publishers. Used by permission. All rights reserved. Text provided by the <a href="http://www.gnpcb.org/esv/share/services/" target="_blank">Crossway Bibles Web Service</a>.';
	protected $short_code_atts	= array(
										'reading-plan' 	=> 'bcp',
										);
	protected $document_root	= '';
	protected $plan_source_link	= 'http://www.esvapi.org/v2/rest/readingPlanQuery?include-headings=false';
	protected $query_string		= '';
	protected $plugin_path		= '';
	protected $post_id			= 0;
	protected $powered_by		= 'Powered by<br /><a href="http://wordpress.org/extend/plugins/embed-bible-passages/" target="_blank" title="Embed Bible Passages">Embed Bible Passages</a><br />plugin for WordPress';
	protected $show_poweredby	= false;

	public function __construct () {
		$this->document_root	= 'http://'.$_SERVER['SERVER_NAME'];
		$this->plugin_path		= plugins_url('/embed-bible-passages/');
		$this->access_key		= get_option('embed_bible_passages_access_key');
		if (!$this->access_key) {
			$this->access_key = 'IP';
			update_option('embed_bible_passages_access_key', $this->access_key);
		}
		$this->show_poweredby	= get_option('embed_bible_passages_show_poweredby');
		if ('' == $this->show_poweredby) {
			$this->show_poweredby = false;
			update_option('embed_bible_passages_show_poweredby', $this->show_poweredby);
		}
		add_shortcode('embed_bible_passage', array(&$this, 'embedBiblePassage'));
		add_shortcode('embed_passage_date', array(&$this, 'passageDate'));
		add_action('admin_init', array(&$this, 'initialize_admin'));
		add_action('admin_menu', array(&$this, 'admin_add_page'));
	}

	public function initialize_admin () {
		if (function_exists('register_setting')) {
			$page_for_settings		= 'embed_bible_passages_plugin';
			$section_for_settings	= 'embed_bible_passages_section';
			add_settings_section($section_for_settings, 'Embed Bible Passages Settings', array(&$this, 'embed_bible_passages_section_heading'), $page_for_settings);
			add_settings_field('embed_bible_passages_access_key_id', 'Access key', array(&$this, 'embed_bible_passages_setting_values'), $page_for_settings, $section_for_settings);
			register_setting('embed_bible_passages_settings', 'embed_bible_passages_access_key', 'wp_filter_nohtml_kses');
			register_setting('embed_bible_passages_settings', 'embed_bible_passages_show_poweredby');
		}
	}

	public function embed_bible_passages_section_heading () {
		echo 'Access key (to request an access key fill out the form at <a href="http://www.esvapi.org/signup" target="_blank" title="ESV Bible Web Service - Request an API Key">http://www.esvapi.org/signup</a>):';
	}

	public function embed_bible_passages_setting_values () {
		$embed_bible_passages_access_key = get_option('embed_bible_passages_access_key');
		echo '<input id="embed_bible_passages_access_key_input" name="embed_bible_passages_access_key" size="35" type="text" value="'.$embed_bible_passages_access_key.'" />';
		echo '<div style="margin: 20px 0 10px -220px;"><input name="embed_bible_passages_show_poweredby" id="embed_bible_passages_show_poweredby_id" type="checkbox" value="1" class="code" ' . checked( 1, get_option('embed_bible_passages_show_poweredby'), false ) . ' /> Show "Powered by" attribution at bottom of page</div>';
	}

	public function admin_add_page() {
		add_options_page('Embed Bible Passages Settings', 'Embed Bible Passages', 'manage_options', 'embed_bible_passages_plugin', array(&$this, 'draw_options_page'));
	}

	public function draw_options_page () {
		echo '<div><h2>Embed Bible Passages Options</h2>';
		echo '<form method="post" action="options.php">';
		settings_fields('embed_bible_passages_settings');
		do_settings_sections('embed_bible_passages_plugin');
		echo '<p><input name="Submit" type="submit" value="';
		esc_attr_e('Save Changes');
		echo '" /></p>';
		echo '</form></div>';
		echo '<div style="width: 50%; margin-top: 25px;">If you find this plugin of value, please contribute to the cost of its developement:<div style="margin: auto; text-align: center"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="XR9J849YUCJ3A">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form><div style="font-size: 0.8em;">"Do not muzzle an ox while it is treading out the grain." and "The worker deserves his wages." <a href="http://www.biblegateway.com/passage/?search=1%20Timothy+5:18&version=NIV" target="_blank">1 Timothy 5:18</a></div></div></div>';
	}

	public function passageDate () {
		return date('l j F Y');
	}


	public function addCSS () {
		echo '<link rel="stylesheet" href="'.$this->plugin_path.'css/embed-bible-passages.css" type="text/css" />';
	}

	public function embedBiblePassage ($atts) {
		$this->query_string = http_build_query(shortcode_atts($this->short_code_atts, $atts));
		return $this->getBiblePassage('Could not retrieve readings.');
	}

	protected function getBiblePassage ($error_message = '') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$this->plan_source_link&key=$this->access_key&$this->query_string&date=".date(Y-m-d));
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$txt = trim(curl_exec($ch));
		if ($txt) {
			$rtn_str  = $txt;
			$rtn_str .= '<div style="font-size: 0.8em; width: 50%; float: left; margin: 0;">'.$this->esv_copyright.'</div>';
			$rtn_str .= '<div style="font-size: 0.8em; width: 50%; float: left; margin: 0; text-align: right;">';
			if ($this->show_poweredby) {
				$rtn_str .= $this->powered_by;
			} else {
				$rtn_str .= '&nbsp;';
			}
			$rtn_str .= '</div>';
		} else {
			$rtn_str = $error_message;
		}
		curl_close($ch);
		return $rtn_str;
	}

}

?>