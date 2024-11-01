<?php
/*
Plugin Name: SM Debug Bar
Plugin URI: http://sethmatics.com/extend/plugins/sm-content-widgets
Description: Developers who want to use PHP to print any variables, arrays and objects out onto the page can now do so without completely wrecking the page layout. Widget provided by http://sethmatics.com/.
Author: Seth Carstens
Version: 1.2
Author URI: http://sethmatics.com/

Special thanks to forum contributers: JochenT
*/

global $degubString; 
$degubString = '';

$currentWorkingFile = basename(__FILE__);
$cwdFullPath = dirname(__FILE__);
$cwd = basename($cwdFullPath);

//////////////////////////
//Setup Admin Page Panels
@include_once('adminpage.class.php');
$options = new TopPage(array(
		'menu_title' => 'Debug Bar',
		'page_title' => 'SM Debug Bar',
		'menu_slug' => 'sm_dbug_bar'
	));
$options->addTitle('Configure how you would like the Debug Bar to function. Click the help link above for more usage instructions.');
if(ini_get('allow_url_fopen') == 1) {
	$fh = fopen($cwdFullPath.'/help.html', 'r');
	$theHTML = fread($fh, filesize($cwdFullPath.'/help.html'));
	fclose($fh);
	$options->addHelp($theHTML);
}

$options->addSlider(array(
		'id' => 'sm_dbug_bar__height',
		'label' => 'Debug Bar Height',
		'desc' => 'The height of the debug bar when it is expanded from the wp-admin bar.',
		'standard' => '320',
		'min' => 200,
		'max' => 1200
	));
$options->addTextarea(array(
	'id' => 'sm_dbug_bar__admin_panel_vars',
	'label' => 'Watch Variables Panel Vars',
	'desc' => 'Choose which PHP variables to watch (should be global in scope or they will appear empty). One variable per line. Example: <br />$_POST<br />$_GET<br />WP_PLUGIN_URL',
	'standard' => 'WP_PLUGIN_URL',
));
//$other_site = new SubPage($top, $args);



//////////////////////////////////////////////////////////////
//load javascript libraries required to plugin enhancements.
function sm_dbug_bar_scripts(){
	//wp_register_script( 'sm-jquery-jstree' , plugins_url().'/sm-debug-bar/jslib/jquery.jstree.js');
	//wp_enqueue_script( 'sm-jquery-jstree', array('jquery-hotkeys') );
	//wp_register_style('sm-jstree-style', plugins_url().'/sm-debug-bar/jquery.jstree.css');
	//wp_enqueue_style('sm-jstree-style');
	//echo '<h1>Loaded JSTREE</h1>';
}
if(isset($_GET['page']) && $_GET['page'] == 'sm_dbug_bar') add_action('init', 'sm_dbug_bar_scripts');


////////////////////////////////////////////////////////
//initialization of plugin at the wordpress init action
function sm_debug_bar () {
	//do not add the debug bar unless the bar is on and admin is logged in with proper credentials
	if(is_admin_bar_showing() && current_user_can('edit_theme_options') ) {
		add_action('admin_bar_menu', 'sm_js_debug_bar_loader', 99);
		add_action('admin_bar_menu', 'sm_admin_bar_links', 99);
		add_action( 'wp_after_admin_bar_render', 'print_dbug', 99);
	}
}
add_action('init', 'sm_debug_bar', 99);


// Modify the wordpress 3.1 front end admin bar
// reference: http://sumtips.com/2011/03/customize-wordpress-admin-bar.html
function sm_admin_bar_links() {
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array(
		'id' => 'debug',
		'title' => __( 'Debug'),
		'href' =>  '#'
	) );
}

//Javascript that allows debug bar link to show and hide the debug div.
function sm_js_debug_bar_loader () {
?>
<!-- ADD DEBUG BAR LOADER -->
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#wp-admin-bar-debug a').click(function(){jQuery('#sm-debug-bar-content').slideToggle(); return false;});
		jQuery('.dbug_array_to_list a').click(function(){jQuery(this).parent().toggleClass('open'); jQuery(this).next().slideToggle(); return false;});
		jQuery('#sm-debug-bar-content').appendTo('#wpadminbar');
		SetupTooltips();	
	});
	ShowTooltip = function(e)
	{
		var text = jQuery(this).next('.show-tooltip-text');
		if (text.attr('class') != 'show-tooltip-text')
			return false;
	
		text.fadeIn()
			.css('top', e.pageY)
			.css('left', e.pageX+10);
	
		return false;
	}
	HideTooltip = function(e)
	{
		var text = jQuery(this).next('.show-tooltip-text');
		if (text.attr('class') != 'show-tooltip-text')
			return false;
	
		text.fadeOut();
	}
	
	SetupTooltips = function()
	{
		jQuery('.tooltip').each(function(){
				jQuery(this)
					.after(jQuery('<span/>')
						.attr('class', 'show-tooltip-text')
						.html(jQuery(this).html()))
					.attr('title', '');
			})
			.hover(ShowTooltip, HideTooltip);
		}
try{console.log('Loaded sm_debug_bar');} catch(e){}
</script>
<style>
#sm-debug-bar-content { display: none; }
#wpadminbar #sm-debug-bar-content * {
  color: #000000;
  font: 12px/26px Arial,Helvetica,sans-serif;
  text-shadow: none;
}
#wpadminbar #sm-debug-bar-content strong { font-weight: bold; }
#wpadminbar #sm-debug-bar-content li {
	list-style: outside disc;
	margin-left: 20px;
	float: none;
}
#wpadminbar #sm-debug-bar-content ul li.treeTitle { 
	font-weight: bold;
	list-style: none;
	margin-left: 0;
}
#wpadminbar #smDebugAdminPanelVars {
	border-top: 1px solid #336699; 
	padding-top: 5px; margin-top: 3px; position:relative; z-index: 1;
}
#wpadminbar #sm-debug-bar-content { 
	display: none; overflow: hidden; 
	height: <?php echo $height; ?>px; 
	min-width: 1980px;
	width: 100%;
	white-space: nowrap; 
	padding-left: 50px; padding-top: 10px; 
	background: #CCC; 
	text-align:left; color: #000000; font-size: 1.2em;
}
#wpadminbar #smDebugAdminPanelVars div.hr { border-bottom: 1px dashed #000; height: 1px; width: 95%; overflow: hidden;}
#wpadminbar #smDebugAdminPanelVars .dbug_array_to_list a { display: block; cursor: pointer; }
#wpadminbar #smDebugAdminPanelVars ul { display: none; }
#wpadminbar #smDebugAdminPanelVars ul.dbug_array_to_list { display: block; }
#wpadminbar #smDebugAdminPanelVars ul.dbug_array_to_list li { list-style: circle outside; margin: 0 0 0 13px; }
#wpadminbar #smDebugAdminPanelVars ul.dbug_array_to_list li.open { list-style: disc outside; }
#wpadminbar #smDebugAdminPanelVars ul.dbug_array_to_list li.hover a{ background: #FF9; }
#wpadminbar #smDebugAdminPanelVars ul.dbug_array_to_list li li{ list-style: disc inside; margin-left: 8px; }
#wpadminbar #smDebugAdminPanelVars ul.dbug_array_to_list li li:hover{ background: #FFC; }
#wpadminbar span.show-tooltip-text { display: none; position: absolute; font-size: 0.9em; background-color: #FFC; background-repeat: repeat-x; padding: 12px 12px 6px 12px; color: white; z-index: 99;
border: 1px solid #FFC;
-webkit-border-radius: 0.6em;
-moz-border-radius: 0.6em;
border-radius: 0.6em;
max-width: 300px;
white-space: normal;
line-height: 1.2em !important;

background-image: -webkit-gradient(linear, left top, left bottom, from(#FFC), to(#FF9)); /* Saf4+, Chrome */
background-image: -webkit-linear-gradient(top, #FFC, #FF9); /* Chrome 10+, Saf5.1+ */
background-image:    -moz-linear-gradient(top, #FFC, #FF9); /* FF3.6 */
background-image:     -ms-linear-gradient(top, #FFC, #FF9); /* IE10 */
background-image:      -o-linear-gradient(top, #FFC, #FF9); /* Opera 11.10+ */
background-image:         linear-gradient(top, #FFC, #FF9);	
}
</style>
<?php	
}


 // add dbug inside body tag above page content
function print_dbug() {
	if (current_user_can('edit_theme_options')) : 
	global $campus, $brandData, $currentWorkingFile, $cwd;
	$padding_bottom = 20;
	
	if(get_option('sm_dbug_bar__height')) $height = get_option('sm_dbug_bar__height')*1; 
	else $height = 320;
	
	?>
    <div id="sm-debug-bar-content">
         
        <div style="float: left; width: 400px; height: <?php echo $height-$padding_bottom; ?>px; overflow-y:auto; overflow-x: none;">
        	<h2 style="margin-bottom:0; margin-right: 5px; float:left; ">Admin Debug Panel </h2><span style="cursor: pointer; float: left; background: url(<?php echo WP_PLUGIN_URL.'/'.$cwd; ?>/help.png) no-repeat; float: left; height: 16px; width: 16px; overflow: hidden; text-indent:-9999px;" class="tooltip">This panel has elements that are listed as "watched" through the <strong>Debug Bar Options page</strong> in the wp-admin.</span><div style="clear: both;"></div>
			<div id="smDebugAdminPanelVars">
			<?php 
				//get the manually added variables from the $_GET vars
				if(isset($_GET['dbug'])) $get_debug = $_GET['dbug']; else  $get_debug = '';
				$varLoad = array_map('trim',explode(',', $get_debug));
				if(!empty($varLoad[0]) || (count($varLoad) > 1)) {
					echo "<!-- manually added ".count($varLoad)." variables from the $_GET vars -->";
					echo "\r\n\t\t\t".'<ul class="dbug_array_to_list" id="dbug_array_to_list_1">';
					foreach($varLoad as $num => $varName){ 
						global ${trim($varName, '$')}; 
						echo dbug_array_to_list(${trim($varName, '$')}, $varName);
					}
					echo "\r\n\t\t\t</ul><!-- end dbug_array_to_list_1 -->\r\n"; 
				}
				if(isset($_GET['dbug']) && $_GET['dbug']) echo '<div class="hr">---</div>';
				
                //get the debug watched variables from the wp-admin options
				$varLoad = array_map('trim',explode(PHP_EOL, get_option('sm_dbug_bar__admin_panel_vars')));
				if(!empty($varLoad[0]) || (count($varLoad) > 1)) {
					echo "<!-- manually added ".count($varLoad)." variables from the wp_options watched vars -->";
					echo "\r\n\t\t\t".'<ul class="dbug_array_to_list" id="dbug_array_to_list_2">';
					foreach($varLoad as $num => $varName){ 
						global ${trim($varName, '$')}; 
						echo dbug_array_to_list(${trim($varName, '$')}, $varName); 
					}
					echo "\r\n\t\t\t</ul><!-- end dbug_array_to_list_2 -->\r\n"; 
				}

            ?>
            </div>
        </div>
        <div style="float: left; width: 600px; height: <?php echo $height-$padding_bottom; ?>px; margin-left: 20px; padding-left: 20px; border-left: 1px solid #000;overflow-y:auto; overflow-x: none;">
        <h2 style="margin-bottom:0; margin-right: 5px; float:left; ">Debug Messages</h2><span style="cursor: pointer; float: left; background: url(<?php echo WP_PLUGIN_URL.'/'.$cwd; ?>/help.png) no-repeat; float: left; height: 16px; width: 16px; overflow: hidden; text-indent:-9999px;" class="tooltip">This panel collects and print any type of object sent to the plugin function like: <strong>dbug($myArray1);</strong> If sending to the function doesn't work, then your file must be loaded before this plugin in which case you will need to "global" the element (global $myArray1;) and watch it using the <strong>Admin Debug Panel</strong> (see panel help menu for more info).</span>
        <div style="clear: both;"></div>
    	<pre style="border-top: 1px solid #336699; white-space: pre-line; clear: both; padding-top: 5px; margin-top: 3px;"><?php dbug_print_messages(); ?></pre></div>
        <div style="clear:both"></div>
        <div id="debugStatusBar" style="background:#FF9; position: relative; left: -50px; padding-left: 50px; border-bottom: groove 2px;">Status bar, javscript execution console coming soon.</div>
    </div>
    <?php
	endif;
}

//Setup default debug string and functions to add messages as well as print the debug messages
if(!function_exists('dbug')) { function dbug($msg) {
	global $degubString, $debugArray;
	$debugArray[] = $msg;
	$degubString = $degubString.print_r($msg, true).PHP_EOL; 
}}

function dbug_print_messages() {
	global $degubString;
	echo print_r($degubString, true);
}

function dbug_array_to_list($array, $varName = false, $htmlID = false, $recursion = 0) {
	unset($list);
	if($recursion>50) return "Infinite Recursion, please contact plugin administrator.";
	
	if(!isset($array))
		return '';
	else
		$array = (array)$array;
	if($htmlID) $htmlID = "id=\"$htmlID\"";
	elseif($varName) $htmlID = 'id="'.trim($varName, '$').'"';
	
	if($varName) { $list = "\r\n\t\t\t\t"."<li $htmlID><a class=\"treeTitle\">".$varName."(".count($array).")</a>\r\n\t\t\t\t\t"."<ul>"; $endlist = "\r\n\t\t\t\t\t".'</ul>'."\r\n\t\t\t\t".'</li>'; }
	else {$list = "<ul $htmlID>"; $endlist = '</ul>'; }
	
	//if its a constant, give the constants value
	if(defined($varName)) $list .= "\r\n\t\t\t\t\t"."<li>".constant($varName)."</li>";
	//otherwise it must be an array of values, pull them all out and add them to the UL
	else {
		foreach ($array as $key => $value){
			if(is_string($value)) $list .= "\r\n\t\t\t\t\t"."<li><span class=\"sm-debug-bar-key\">[$key]</span> $value</li>";
			elseif(is_null($value) || empty($value)) $list .= "\r\n\t\t\t\t\t"."<li><span class=\"sm-debug-bar-key\">[$key]</span> (empty) ".print_r($value, true).'</li>';
			else { $list .= dbug_array_to_list((array)$value, $key, $key, $recursion++); }
		}
	}
	$list .= $endlist;
	return $list;
}

?>