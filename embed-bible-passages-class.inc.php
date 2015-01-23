<?php

class EmbedBiblePassages {
	/* Shortcode of the form [embed_bible_passage reading_plan='bcp'] are replaced by the scriptures from a Bible Reading Plan
	 * of http://www.esvapi.org/api#readingPlanQuery.
	 */

	// NOTE THAT THE FOLLOWING COPYRIGHT NOTICE FROM THE SOURCE OF THE TEXT CROSSWAY BIBLE MUST BE KEPT ON THE PAGE.
	protected $esv_copyright	= 'Scripture taken from The Holy Bible, English Standard Version. Copyright &copy;2001 by <a href="http://www.crosswaybibles.org" target="_blank">Crossway Bibles</a>, a publishing ministry of Good News Publishers. Used by permission. All rights reserved. Text provided by the <a href="http://www.gnpcb.org/esv/share/services/" target="_blank">Crossway Bibles Web Service</a>. Reader: David Cochran Heath.';
	protected $access_key		= 'IP';
	protected $ajax_url			= '';
	protected $date_format		= 'l j F Y';
	protected $document_root	= '';
	protected $plan_source_link	= 'http://www.esvapi.org/v2/rest/readingPlanQuery?include-headings=false';
	protected $plugin_url		= '';
	protected $post_id			= 0;
	protected $powered_by		= 'Powered by<br /><a href="http://wordpress.org/extend/plugins/embed-bible-passages/" target="_blank" title="Embed Bible Passages">Embed Bible Passages</a><br />plugin for WordPress';
	protected $query_string		= '';
	protected $reading_plans	= array(
										'bcp'						=> 'Book of Common Prayer',
										'lsb'						=> 'Literary Study Bible',
										'esv-study-bible'			=> 'ESV Study Bible',
										'every-day-in-the-word'		=> 'Every Day in the Word',
										'one-year-tract'			=> 'M&#039;Cheyne One-Year Reading Plan',
										'outreach'					=> 'Outreach',
										'outreach-nt'				=> 'Outreach New Testament',
										'through-the-bible'			=> 'Through the Bible in a Year',
										);
	protected $show_poweredby	= false;
	protected $short_code_atts	= array(
										'reading_plan' 	=> 'bcp',
										);
	protected $use_calendar		= false;
	
	public function __construct () {
		$this->document_root	= 'http://'.$_SERVER['SERVER_NAME'];
		$this->plugin_url		= plugins_url('/embed-bible-passages/');
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
		$this->use_calendar		= get_option('embed_bible_passages_use_calendar');
		if ('' == $this->use_calendar) {
			$this->use_calendar = false;
			update_option('embed_bible_passages_use_calendar', $this->use_calendar);
		}
		$this->ajax_url = admin_url('admin-ajax.php').'?action=put_bible_passage&';
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
			register_setting('embed_bible_passages_settings', 'embed_bible_passages_use_calendar');
		}
	}

	public function embed_bible_passages_section_heading () {
		_e('Access key (to request an access key fill out the form at <a href="http://www.esvapi.org/signup" target="_blank" title="ESV Bible Web Service - Request an API Key">http://www.esvapi.org/signup</a>):');
	}

	public function embed_bible_passages_setting_values () {
		echo '<input id="embed_bible_passages_access_key_input" name="embed_bible_passages_access_key" size="35" type="text" value="'.$this->access_key.'" />';
		echo '<div style="margin: 20px 0 10px -220px;"><input name="embed_bible_passages_use_calendar" id="embed_bible_passages_use_calendar_id" type="checkbox" value="1" class="code" '.checked(true, $this->use_calendar, false).' /> Show Date Picker Calendar</div>';
		echo '<div style="margin: 20px 0 10px -220px;"><input name="embed_bible_passages_show_poweredby" id="embed_bible_passages_show_poweredby_id" type="checkbox" value="1" class="code" '.checked(true, $this->show_poweredby, false).' /> Show "Powered by" attribution at bottom of page</div>';
	}

	public function admin_add_page() {
		add_options_page('Embed Bible Passages Settings', 'Embed Bible Passages', 'manage_options', 'embed_bible_passages_plugin', array(&$this, 'draw_options_page'));
	}

	public function draw_options_page () {
		echo '<div><h2>Embed Bible Passages Options</h2>';
		echo '<h3>Shortcode Format</h3>
			<p>This plugin provides the ability to embed Bible readings from the <a href="http://www.esvapi.org/api#readingPlanQuery" target="_blank">ESV Bible Web Service</a> into a post or page using shortcode of the form [embed_bible_passage reading_plan=\'bcp\'].
			</p><p>
			The values of reading_plan can be:
			<ul style="text-indent: 20px;">
				<li>bcp						- Book of Common Prayer</li>
				<li>lsb						- Literary Study Bible</li>
				<li>esv-study-bible			- ESV Study Bible</li>
				<li> every-day-in-the-word	- Every Day in the Word</li>
				<li>one-year-tract			- M&#039;Cheyne One-Year Reading Plan</li>
				<li>outreach				- Outreach</li>
				<li>outreach-nt				- Outreach New Testament</li>
				<li>through-the-bible		- Through the Bible in a Year</li>
				</ul>
			The default reading plan is bcp.</p>
			<p>Note that only the bcp and through-the-bible options have been tested for this plugin. The other options are provided by the ESV Bible Web Service and should also work.</p>
			<p>For more information about these reading plans, please see the <a href="http://www.esvbible.org/search/?q=devotions" target="_blank">ESVBible.org Devotions area</a>.</p>
			<p>The page opens with the plan reading for the current date. A date picker calendar is available (see option below) to enable users to choose readings for other dates.</p>
			<p>A tag to embed the current date is also available [embed_passage_date], although this is deprecated in favor of using the date picker calendar.</p>';
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
		echo '<p>The support of "<a href="http://www.thebiblechallenge.org/" target="_blank">The Bible Challenge</a>" for the development of several important features of this plugin is gratefully acknowledged.</p>';
	}

	public function passageDate () {
		return date($this->date_format);
	}

	public function addCSS () {
		wp_register_style('embed-bible-passages-jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('embed-bible-passages-jquery-ui');
		wp_register_style('embed-bible-passages', $this->plugin_url.'css/embed-bible-passages.css', array('embed-bible-passages-jquery-ui',), null);
		wp_enqueue_style('embed-bible-passages');
	}
	
	public function enqueueScripts () {
		wp_enqueue_script('jquery-ui-datepicker');
	} 

	public function addScriptureLoader () {
		echo "
			<script>
				var ajaxurl = '{$this->ajax_url}{$this->query_string}&requested_date=';
				
				// Load Scriptures initially
				var ebp_date_obj = new Date();
				jQuery('#scriptures').load(ajaxurl + encodeURI(ebp_date_obj.toDateString()));";
		if ($this->use_calendar) {
			echo "
				
				// Datepicker to load Scriptures for dates other than today
				jQuery(function() {
					jQuery('#datepicker').datepicker({
						autoSize:	true,
						onSelect:	function(dateText) {
										jQuery.get(ajaxurl + dateText, function(data) {
											jQuery('#scriptures').html(data);
										});
									}
					})
				});";
		}
		echo "
			</script>";
	}

	public function embedBiblePassage ($atts) {
		extract(shortcode_atts($this->short_code_atts, $atts));
		if (!in_array($reading_plan, array_keys($this->reading_plans))) {
			$reading_plan = 'bcp'; // default
		}
		$this->query_string = "reading-plan=$reading_plan";
		// Use mp3 instead of Flash if iPad or iPhone
		if (strpos($_SERVER["HTTP_USER_AGENT"], 'iPhone') !== false || strpos($_SERVER["HTTP_USER_AGENT"], 'iPad') !== false) {
			$this->query_string .= '&audio-format=mp3';
		}
		return $this->getBiblePassage();
	}

	protected function getBiblePassage ($query_string = '', $error_message = 'Could not retrieve readings.') {
		if ($query_string) {
			$this->query_string  = $query_string;
		} else {
			$this->query_string .= '&date='.date('Y-m-d'); // This is most likely never reached in the current version
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$this->plan_source_link&key=$this->access_key&$this->query_string");
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$txt = trim(curl_exec($ch));
		curl_close($ch);
		if ($txt) {
			if ($this->use_calendar) {
				parse_str($this->query_string);
				if ($date) {
					list($year, $month, $day) = explode('-', $date);
					$scriptures_date = date($this->date_format, mktime(0, 0, 1, $month, $day, $year));
				} else {
					$scriptures_date = date($this->date_format);
				}
				$rtn_str  = '<span class="scriptures-date">'.$scriptures_date.'</span>'.$txt;
			} else {
				$rtn_str  = $txt;
			}
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
		if ($query_string) {
			return $rtn_str; // with calendar, and loaded with calendar selection
		} elseif ($this->use_calendar) {
			return '<div title="'.__('Click on a date to open the readings for that day.').'" id="datepicker"></div><div id="scriptures"></div>'; // with calendar, but loaded without calendar selection
		} else {
			return '<div id="scriptures"></div>'; // no calendar
		}
	}
	
	public function putBiblePassage () {
		$query_string = '';
		if (isset($_REQUEST['reading-plan']) && $_REQUEST['reading-plan']) {
			$query_string .= '&reading-plan='.$_REQUEST['reading-plan'];
		}
		if (isset($_REQUEST['audio-format']) && $_REQUEST['audio-format']) {
			$query_string .= '&audio-format='.$_REQUEST['audio-format'];
		}
		if (isset($_REQUEST['requested_date']) && $_REQUEST['requested_date']) {
			if (preg_match("/[a-zA-Z]+/", $_REQUEST['requested_date']) === false) {
				list($month, $day, $year) = explode('/', $_REQUEST['requested_date']);
				$query_string .= "&date=$year-$month-$day";
			} else {
				$query_string .= "&date=".date("Y-m-d", strtotime($_REQUEST['requested_date']));
			}
		}
		echo $this->getBiblePassage($query_string);
		die();
	}

}

?>