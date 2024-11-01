<?php
/*
Plugin Name: South Africa Sociable Plugin
Plugin URI: http://timesurl.at/plugins/sociable/
Description: Automatically add links on your posts to popular social bookmarking sites. Go to Options -> Sociable for setup. This plugin is based on Peter Harkins Sociable plugin (Version 2.0) and the original version can be <a href="http://push.cx/sociable">found here</a>.
Version: 2.0.2
Author: Justin Hartman
Author URI: http://justinhartman.com/
*/

/*
Copyright 2006 Peter Harkins (ph@malaprop.org)
Copyright 2008 Justin Hartman (justin@hartman.me)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$sociable_known_sites = Array(
	'TimesURL' => Array(
		'favicon' => 'timesurl.png',
		'url' => 'http://timesurl.at/create_url.php?url=PERMALINK',
		'description' => 'Shorten & Email this link with TimesURL'
	),
	
	'Gatorpeeps' => Array(
		'favicon' => 'gatorpeeps.png',
		'url' => 'http://gatorpeeps.com/?url=PERMALINK&txt=TITLE'
	),

	'Muti' => Array(
		'favicon' => 'muti.png',
		'url' => 'http://muti.co.za/submit?url=PERMALINK&amp;title=TITLE',
	    'description' => 'Submit to Muti'
	),
	
	'laaik.it' => Array(
		'favicon' => 'laaikit.png',
		'url' => 'http://laaik.it/NewStoryCompact.aspx?uri=PERMALINK&amp;headline=TITLE&amp;cat=5e082fcc-8a3b-47e2-acec-fdf64ff19d12'
	),
	
	'LinkedIn' => Array(
		'favicon' => 'linkedin.png',
		'url' => 'http://www.linkedin.com/shareArticle?mini=true&amp;url=PERMALINK&amp;title=TITLE&amp;source=BLOGNAME'
	),
	
	'Ping.fm' => Array(
		'favicon' => 'ping.png',
		'url' => 'http://ping.fm/ref/?link=PERMALINK&amp;title=TITLE'
	),
	
	'Posterous' => Array(
		'favicon' => 'posterous.png',
		'url' => 'http://posterous.com/share?linkto=PERMALINK&amp;title=TITLE',
	    'description' => 'Share on Posterous'
	),
	
	'Facebook' => Array(
		'favicon' => 'facebook.png',
		'url' => 'http://www.facebook.com/share.php?u=PERMALINK&amp;t=TITLE'
	),
	
	'Friendfeed' => Array(
		'favicon' => 'friendfeed.png',
		'url' => 'http://www.friendfeed.com/share?title=TITLE&amp;link=PERMALINK'
	),

	'Google' => Array(
		'favicon' => 'google.png',
		'url' => 'http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=PERMALINK&amp;title=TITLE',
		'description' => 'Google Bookmarks'
	),

	'del.icio.us' => Array(
		'favicon' => 'delicious.png',
		'url' => 'http://del.icio.us/post?url=PERMALINK&amp;title=TITLE'
	),

	'Digg' => Array(
		'favicon' => 'digg.png',
		'url' => 'http://digg.com/submit?phase=2&amp;url=PERMALINK&amp;title=TITLE',
		'description' => 'bodytext'
	),

	'Reddit' => Array(
		'favicon' => 'reddit.png',
		'url' => 'http://reddit.com/submit?url=PERMALINK&amp;title=TITLE'
	),

	'StumbleUpon' => Array(
		'favicon' => 'stumbleupon.png',
		'url' => 'http://www.stumbleupon.com/submit?url=PERMALINK'
	),

	'Technorati' => Array(
		'favicon' => 'technorati.png',
		'url' => 'http://technorati.com/faves?add=PERMALINK'
	),
	
	'Twitter' => Array(
		'favicon' => 'twitter.png',
		'url' => 'http://twitter.com/home?status=TITLE%20-%20PERMALINK'
	),

);

$sociable_files = Array(
	'description_selection.js',
	'sociable-admin.css',
	'sociable.css',
	'sociable.php',
	'sociable.css',
	'sociable-admin.css',
	'images/',
	'images/delicious.png',
	'images/digg.png',
	'images/facebook.png',
	'images/friendfeed.png',
	'images/gatorpeeps.png',
	'images/google.png',
	'images/laaikit.png',
	'images/linkedin.png',
	'images/muti.png',
	'images/ping.png',
	'images/posterous.png',
	'images/reddit.png',
	'images/stumbleupon.png',
	'images/technorati.png',
	'images/timesurl.png',
	'images/twitter.png',
	'tool-man/',
	'tool-man/coordinates.js',
	'tool-man/core.js',
	'tool-man/css.js',
	'tool-man/drag.js',
	'tool-man/dragsort.js',
	'tool-man/events.js',
);


function sociable_html($display=Array()) {
	global $sociable_known_sites, $sociable_version;
	$active_sites = get_option('sociable_active_sites');

	$html = "";

	$imagepath = get_bloginfo('wpurl') . '/wp-content/plugins/timesurlat-sociable-plugin/images/';

	// if no sites are specified, display all active
	// have to check $active_sites has content because WP
	// won't save an empty array as an option
	if (empty($display) and $active_sites)
		$display = $active_sites;
	// if no sites are active, display nothing
	if (empty($display))
		return "";

	// Load the post's data
	$blogname = urlencode(get_bloginfo('wpurl'));
	global $wp_query; 
	$post = $wp_query->post;
	$permalink = urlencode(get_permalink($post->ID));
	$title = urlencode($post->post_title);
	$rss = urlencode(get_bloginfo('ref_url'));

	$html .= "\n<div class=\"sociable\">\n<span class=\"sociable_tagline\">\n";
	$html .= get_option("sociable_tagline");
	$html .= "\n\t<span>" . __("Share this post with the world.", 'timesurlat-sociable-plugin') . "</span>";
	$html .= "\n</span>\n<ul>\n";

	foreach($display as $sitename) {
		// if they specify an unknown or inactive site, ignore it
		if (!in_array($sitename, $active_sites))
			continue;

		$site = $sociable_known_sites[$sitename];
		$html .= "\t<li>";

		$url = $site['url'];
		$url = str_replace('PERMALINK', $permalink, $url);
		$url = str_replace('TITLE', $title, $url);
		$url = str_replace('RSS', $rss, $url);
		$url = str_replace('BLOGNAME', $blogname, $url);
		$url = str_replace('VERSION', $sociable_version, $url);

		$html .= "<a href=\"$url\" title=\"$sitename\"";
		if ($site['description'])
                    $html .= " onfocus=\"sociable_description_link(this, '{$site['description']}')\"";
                $html .= ">";
		$html .= "<img src=\"$imagepath{$site['favicon']}\" title=\"$sitename\" alt=\"$sitename\" class=\"sociable-hovers";
                if ($site['class'])
                    $html .= " sociable_{$site['class']}";
                $html .= "\" />";
		$html .= "</a></li>\n";
	}

	$html .= "</ul>\n</div>\n";

	return $html;
}

// Hook the_content to output html if we should display on any page
$sociable_contitionals = get_option('sociable_conditionals');
if (is_array($sociable_contitionals) and in_array(true, $sociable_contitionals)) {
	add_filter('the_content', 'sociable_display_hook');
	add_filter('the_excerpt', 'sociable_display_hook');
	
	function sociable_display_hook($content='') {
		$conditionals = get_option('sociable_conditionals');
		if ((is_home()     and $conditionals['is_home']) or
		    (is_single()   and $conditionals['is_single']) or
		    (is_page()     and $conditionals['is_page']) or
		    (is_category() and $conditionals['is_category']) or
		    (is_date()     and $conditionals['is_date']) or
		    (is_search()   and $conditionals['is_search']) or
		     0)
			$content .= sociable_html();
	
		return $content;
	}
}

// Hook wp_head to add css
add_action('wp_head', 'sociable_wp_head');
function sociable_wp_head() {
	if (in_array('Wists', get_option('sociable_active_sites')))
		echo '<script language="JavaScript" type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/timesurlat-sociable-plugin/wists.js"></script>';
        echo '<script language="JavaScript" type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/timesurlat-sociable-plugin/description_selection.js"></script>';
	echo '<link rel="stylesheet" type="text/css" media="screen" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/timesurlat-sociable-plugin/sociable.css" />';
}

// load wp rss functions for update checking.
if (!function_exists('parse_w3cdtf')) {
	require_once(ABSPATH . WPINC . '/rss-functions.php');
}

// Plugin config/data setup
if (function_exists('register_activation_hook')) {
	// for WP 2
	register_activation_hook(__FILE__, 'sociable_activation_hook');
} else {
	// for WP 1.5, which doesn't have any activation hook
	if (!is_array(get_option('sociable_active_sites')))
		sociable_activation_hook();
}
function sociable_activation_hook() {
	return sociable_restore_config(True);
}

// restore built-in defaults, optionally overwriting existing values
function sociable_restore_config($force=False) {
	// Load defaults, taking care not to smash already-set options
	global $sociable_known_sites;

	// Used to store sites in the db with the idea users would
	// add sites, but nobody will so I dropped the feature.
	// This should clean up any old installs.
	delete_option('sociable_known_sites');

	// active_sites defaults
	if ($force or !is_array(get_option('sociable_active_sites')))
		update_option('sociable_active_sites', array(
			'TimesURL',
			'Gatorpeeps',
			'Muti',
			'Twitter',
			'Posterous',
			'Facebook',
			'laaik.it',
		));

	// tagline defaults to a Hitchiker's Guide to the Galaxy reference
	if ($force or !is_string(get_option('sociable_tagline')))
		update_option('sociable_tagline', "<strong>" . __("Share this post:", 'timesurlat-sociable-plugin') . "</strong>");

	// only display on single posts and pages by default
	if ($force or !is_array(get_option('sociable_conditionals')))
		update_option('sociable_conditionals', array(
			'is_home' => False,
			'is_single' => True,
			'is_page' => True,
			'is_category' => False,
			'is_date' => False,
			'is_search' => False,
		));
}

// Hook the admin_menu display to add admin page
add_action('admin_menu', 'sociable_admin_menu');
function sociable_admin_menu() {
	add_submenu_page('options-general.php', 'South Africa Sociable', 'South Africa Sociable', 8, 'South Africa Sociable', 'sociable_submenu');
}

// Admin page header
add_action('admin_head', 'sociable_admin_head');
function sociable_admin_head() {
?>

<!-- The ToolMan lib provides drag and drop: http://tool-man.org/examples/sorting.html -->
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/timesurlat-sociable-plugin/tool-man/core.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/timesurlat-sociable-plugin/tool-man/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/timesurlat-sociable-plugin/tool-man/css.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/timesurlat-sociable-plugin/tool-man/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/timesurlat-sociable-plugin/tool-man/dragsort.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/timesurlat-sociable-plugin/tool-man/events.js"></script>
<script language="JavaScript" type="text/javascript"><!--
var dragsort = ToolMan.dragsort();
var junkdrawer = ToolMan.junkdrawer();

function save_reorder(id) {
	site_order = document.getElementById('site_order');

	old_order = site_order.value;
	new_order = junkdrawer.serializeList(document.getElementById('sociable_site_list'));
	site_order.value = new_order;

	if (!site_order.used || new_order == old_order)
		toggle_checkbox(id);
	site_order.used = true;
}

/* make checkbox action prettier */
function toggle_checkbox(id) {
	var checkbox = document.getElementById(id);

	checkbox.checked = !checkbox.checked;
	if (checkbox.checked)
		checkbox.parentNode.className = 'active';
	else
		checkbox.parentNode.className = 'inactive';
}
--></script>

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/timesurlat-sociable-plugin/sociable-admin.css" />
<?php
}

function sociable_message($message) {
	echo "<div id=\"message\" class=\"updated fade\"><p>$message</p></div>\n";
}

// Sanity check the upload worked
function sociable_upload_errors() {
	global $sociable_files;

	$cwd = getcwd(); // store current dir for restoration
	if (!@chdir('../wp-content/plugins'))
		return __("Couldn't find wp-content/plugins folder. Please make sure WordPress is installed correctly.", 'timesurlat-sociable-plugin');
	if (!is_dir('timesurlat-sociable-plugin'))
		return __("Can't find sociable folder.", 'timesurlat-sociable-plugin');
	chdir('timesurlat-sociable-plugin');

	foreach($sociable_files as $file) {
		if (substr($file, -1) == '/') {
			if (!is_dir(substr($file, 0, strlen($file) - 1)))
				return __("Can't find folder:", 'timesurlat-sociable-plugin') . " <kbd>$file</kbd>";
		} else if (!is_file($file))
		return __("Can't find file:", 'timesurlat-sociable-plugin') . " <kbd>$file</kbd>";
	}


	$header_filename = '../../themes/' . get_option('template') . '/header.php';
	if (!file_exists($header_filename) or strpos(@file_get_contents($header_filename), 'wp_head()') === false)
		return __("Your theme isn't set up for Sociable to load its style. Please edit <kbd>header.php</kbd> and add a line reading <kbd>&lt?php wp_head(); ?&gt;</kbd> before <kbd>&lt;/head&gt;</kbd> to fix this.", 'timesurlat-sociable-plugin');

	chdir($cwd); // restore cwd

	return false;
}

// The admin page
function sociable_submenu() {
	global $sociable_known_sites, $sociable_date, $sociable_files;

	// update options in db if requested
	if ($_REQUEST['restore']) {
		sociable_restore_config(True);
	sociable_message(__("Restored all settings to defaults.", 'timesurlat-sociable-plugin'));
	} else if ($_REQUEST['save']) {
		// update active sites
		$active_sites = Array();
		if (!$_REQUEST['active_sites'])
			$_REQUEST['active_sites'] = Array();
		foreach($_REQUEST['active_sites'] as $sitename=>$dummy)
			$active_sites[] = $sitename;
		update_option('sociable_active_sites', $active_sites);
		// have to delete and re-add because update doesn't hit the db for identical arrays
		// (sorting does not influence associated array equality in PHP)
		delete_option('sociable_active_sites', $active_sites);
		add_option('sociable_active_sites', $active_sites);

		// update conditional displays
		$conditionals = Array();
		if (!$_REQUEST['conditionals'])
			$_REQUEST['conditionals'] = Array();
		foreach(get_option('sociable_conditionals') as $condition=>$toggled)
			$conditionals[$condition] = array_key_exists($condition, $_REQUEST['conditionals']);
		update_option('sociable_conditionals', $conditionals);

		// update tagline
		if (!$_REQUEST['tagline'])
			$_REQUEST['tagline'] = "";
		update_option('sociable_tagline', $_REQUEST['tagline']);
		
		sociable_message(__("Saved changes.", 'timesurlat-sociable-plugin'));
	}

	if ($str = sociable_upload_errors())
		sociable_message("$str</p><p>" . __("In your plugins/timesurlat-sociable-plugin folder, you must have these files:", 'timesurlat-sociable-plugin') . ' <pre>' . implode("\n", $sociable_files) ); 

	// show active sites first and in order
	$active_sites = get_option('sociable_active_sites');
	$active = Array(); $disabled = $sociable_known_sites;
	foreach($active_sites as $sitename) {
		$active[$sitename] = $disabled[$sitename];
		unset($disabled[$site]);
	}
	uksort($disabled, "strnatcasecmp");

	// load options from db to display
	$tagline = get_option('sociable_tagline');
	$conditionals = get_option('sociable_conditionals');
	$updated = get_option('sociable_updated');

	// display options
?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

<div class="wrap" id="sociable_options">
<fieldset id="sociable_sites">

<h3><?php _e("South Africa Sociable Options", 'timesurlat-sociable-plugin'); ?></h3>

<p><?php _e("Drag and drop sites to reorder them. Only the sites you check will appear publicly.", 'timesurlat-sociable-plugin'); ?></p>

<ul id="sociable_site_list">
<?php foreach (array_merge($active, $disabled) as $sitename=>$site) { ?>
	<li
		id="<?php echo $sitename; ?>"
		class="sociable_site <?php echo (in_array($sitename, $active_sites)) ? "active" : "inactive"; ?>"
		onmouseup="javascript:save_reorder('cb_<?php echo $sitename; ?>');"
	>
		<input
			type="checkbox"
			id="cb_<?php echo $sitename; ?>"
			class="checkbox"
			name="active_sites[<?php echo $sitename; ?>]"
			onclick="javascript:toggle_checkbox('cb_<?php echo $sitename; ?>');"
			<?php echo (in_array($sitename, $active_sites)) ? ' checked="checked"' : ''; ?>
		/>
		<img src="../wp-content/plugins/timesurlat-sociable-plugin/images/<?php echo $site['favicon']?>" width="16" height="16" alt="" />
		<?php print $sitename; ?>
	</li>
<?php } ?>
</ul>
<input type="hidden" id="site_order" name="site_order" value="<?php echo join('|', array_keys($sociable_known_sites)) ?>" />
<script language="JavaScript" type="text/javascript"><!--
	dragsort.makeListSortable(document.getElementById("sociable_site_list"));
--></script>

</fieldset>
<div style="clear: left; display: none;"><br/></div>

<fieldset id="sociable_tagline">
<p>
<?php _e("Change the text displayed in front of the icons below. For complete customization, edit <kbd>sociable.css</kbd> in the Sociable plugin directory.", 'timesurlat-sociable-plugin'); ?>
</p>
<input type="text" name="tagline" value="<?php echo htmlspecialchars($tagline); ?>" />
</fieldset>


<fieldset id="sociable_conditionals">
<p><?php _e("The icons appear at the end of each blog post, and posts may show on many different types of pages. Depending on your theme and audience, it may be tacky to display icons on all types of pages.", 'timesurlat-sociable-plugin'); ?></p>

<ul style="list-style-type: none">
	<li><input type="checkbox" name="conditionals[is_home]"<?php echo ($conditionals['is_home']) ? ' checked="checked"' : ''; ?> /> <?php _e("Front page of the blog", 'timesurlat-sociable-plugin'); ?></li>
	<li><input type="checkbox" name="conditionals[is_single]"<?php echo ($conditionals['is_single']) ? ' checked="checked"' : ''; ?> /> <?php _e("Individual blog posts", 'timesurlat-sociable-plugin'); ?></li>
	<li><input type="checkbox" name="conditionals[is_page]"<?php echo ($conditionals['is_page']) ? ' checked="checked"' : ''; ?> /> <?php _e('Individual WordPress "Pages"', 'timesurlat-sociable-plugin'); ?></li>
	<li><input type="checkbox" name="conditionals[is_category]"<?php echo ($conditionals['is_category']) ? ' checked="checked"' : ''; ?> /> <?php _e("Category archives", 'timesurlat-sociable-plugin'); ?></li>
	<li><input type="checkbox" name="conditionals[is_date]"<?php echo ($conditionals['is_date']) ? ' checked="checked"' : ''; ?> /> <?php _e("Date-based archives", 'timesurlat-sociable-plugin'); ?></li>
	<li><input type="checkbox" name="conditionals[is_search]"<?php echo ($conditionals['is_search']) ? ' checked="checked"' : ''; ?> /> <?php _e("Search results", 'timesurlat-sociable-plugin'); ?></li>
</ul>
</fieldset>

<p class="submit"><input name="save" id="save" tabindex="3" value="<?php _e("Save Changes", 'timesurlat-sociable-plugin'); ?>" type="submit" /></p>
<p class="submit"><input name="restore" id="restore" tabindex="3" value="<?php _e("Restore Built-in Defaults", 'timesurlat-sociable-plugin'); ?>" type="submit" style="border: 2px solid #e00;" /></p>
</div>

</form>

<?php
}
?>