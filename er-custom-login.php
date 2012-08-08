<?php
/*
Plugin Name: Erident Custom Login and Dashboard
Plugin URI: http://www.eridenttech.com/wp-plugins/erident-custom-login-and-dashboard
Description: Customize completly your WordPress Login Screen and Dashboard. Add your company logo to login screen, change background colors, styles etc. Customize your Dashboard footer text also for complete branding.
Version: 1.1
Author: Erident Technologies
Author URI: http://www.eridenttech.com/
License: GPL
*/

/*  Copyright 2012  Erident Technologies  (email : support@eridenttech.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function my_admin_head() {
        echo '<link rel="stylesheet" type="text/css" media="all" href="' .plugins_url('er-admin.css', __FILE__). '">';
		echo '<link rel="stylesheet" type="text/css" media="all" href="' .plugins_url('farbtastic/farbtastic.css', __FILE__). '">';
		echo '<script type="text/javascript" src="' .plugins_url('farbtastic/jquery.js', __FILE__). '"></script>';
		echo '<script type="text/javascript" src="' .plugins_url('farbtastic/farbtastic.js', __FILE__). '"></script>';
}

add_action('admin_head', 'my_admin_head');

/**
 * Add Settings link to plugins
 */
 function add_settings_link($links, $file) {
static $this_plugin;
if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
 
if ($file == $this_plugin){
$settings_link = '<a href="options-general.php?page=erident-custom-login-and-dashboard">'.__("Settings", "erident-custom-login-and-dashboard").'</a>';
 array_unshift($links, $settings_link);
}
return $links;
 }
add_filter('plugin_action_links', 'add_settings_link', 10, 2 );


add_filter('admin_footer_text', 'left_admin_footer_text_output'); //left side
function left_admin_footer_text_output($er_left) {
    $er_left =  get_option('wp_erident_dashboard_data_left');
    return $er_left;
}
 
add_filter('update_footer', 'right_admin_footer_text_output', 11); //right side
function right_admin_footer_text_output($er_right) {
    $er_right = get_option('wp_erident_dashboard_data_right');
    return $er_right;
}
/* Login Logo */
function er_login_logo() { 
 $er_logo = get_option('wp_erident_dashboard_image_logo');
 
 $er_login_width = get_option('wp_erident_dashboard_login_width');
 $er_login_radius = get_option('wp_erident_dashboard_login_radius');
 $er_login_border = get_option('wp_erident_dashboard_login_border');
 $er_login_border_thick = get_option('wp_erident_dashboard_border_thick');
 $er_login_border_color = get_option('wp_erident_dashboard_border_color');
 $er_login_bg = get_option('wp_erident_dashboard_login_bg');
 $er_login_text_color = get_option('wp_erident_dashboard_text_color');
 $er_login_link_color = get_option('wp_erident_dashboard_link_color');
 
 $check_shadow = get_option('wp_erident_dashboard_check_shadow');
	if($check_shadow == Yes) { 
 		$er_login_link_shadow = get_option('wp_erident_dashboard_link_shadow').' 0 1px 0';
	}
	else {
		$er_login_link_shadow = "none";
	}
 
 $er_top_bg_color = get_option('wp_erident_top_bg_color');
 $er_top_bg_image = get_option('wp_erident_top_bg_image');
 $er_top_bg_repeat = get_option('wp_erident_top_bg_repeat');
 $er_top_bg_xpos = get_option('wp_erident_top_bg_xpos');
 $er_top_bg_ypos = get_option('wp_erident_top_bg_ypos');

 $er_login_bg_image = get_option('wp_erident_login_bg_image');
 $er_login_bg_repeat = get_option('wp_erident_login_bg_repeat');
 $er_login_bg_xpos = get_option('wp_erident_login_bg_xpos');
 $er_login_bg_ypos = get_option('wp_erident_login_bg_ypos');
?>
    <style type="text/css">
        body.login {
			background: <?php echo $er_top_bg_color ?> url(<?php echo $er_top_bg_image ?>) <?php echo $er_top_bg_repeat ?> <?php echo $er_top_bg_xpos ?> <?php echo $er_top_bg_ypos ?>;
		}
		body.login div#login h1 a {
            background-image: url(<?php echo $er_logo ?>);
            padding-bottom: 30px;
			margin: 0 auto;
        }
		body.login #login {
		width:<?php echo $er_login_width ?>px;
		}
		.login form {
			border-radius:<?php echo $er_login_radius ?>px;
			border:<?php echo $er_login_border_thick ?>px <?php echo $er_login_border ?> <?php echo $er_login_border_color ?>;
			background:<?php echo $er_login_bg ?> url(<?php echo $er_login_bg_image ?>) <?php echo $er_login_bg_repeat ?> <?php echo $er_login_bg_xpos ?> <?php echo $er_login_bg_ypos ?>;
		}
		body.login div#login form p label {
			color:<?php echo $er_login_text_color ?>;
		}
		body.login #nav a, body.login #backtoblog a {
			color: <?php echo $er_login_link_color ?> !important;
		}
		body.login #nav, body.login #backtoblog {
			text-shadow: <?php echo $er_login_link_shadow ?>;
		}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'er_login_logo' );

/* Login Links */
function my_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function er_login_logo_url_title() {
	$er_power_text = get_option('wp_erident_dashboard_power_text');
    return $er_power_text;
}
add_filter( 'login_headertitle', 'er_login_logo_url_title' );


/* Runs when plugin is activated */
register_activation_hook(__FILE__,'wp_erident_dashboard_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'wp_erident_dashboard_remove' );

function wp_erident_dashboard_install() {
/* Creates new database field */
add_option("wp_erident_dashboard_data_left", 'Powered by Erident Technologies', '', 'yes');
add_option("wp_erident_dashboard_data_right", '&copy; 2012 All Rights Reserved', '', 'yes');
add_option("wp_erident_dashboard_image_logo", plugins_url('images/default-logo.png', __FILE__), '', 'yes');
add_option("wp_erident_dashboard_power_text", 'Powered by Erident Technologies', '', 'yes');

add_option("wp_erident_dashboard_login_width", '350', '', 'yes');
add_option("wp_erident_dashboard_login_radius", '10', '', 'yes');
add_option("wp_erident_dashboard_login_border", 'solid', '', 'yes');
add_option("wp_erident_dashboard_border_thick", '4', '', 'yes');
add_option("wp_erident_dashboard_border_color", '#6e838e', '', 'yes');
add_option("wp_erident_dashboard_login_bg", '#dbdbdb', '', 'yes');
add_option("wp_erident_dashboard_text_color", '#000000', '', 'yes');
add_option("wp_erident_dashboard_link_color", '#21759B', '', 'yes');
add_option("wp_erident_dashboard_check_shadow", 'Yes', '', 'yes');
add_option("wp_erident_dashboard_link_shadow", '#ffffff', '', 'yes');

add_option("wp_erident_top_bg_color", '#f9fad2', '', 'yes');
add_option("wp_erident_top_bg_image", plugins_url('images/top_bg.jpg', __FILE__), '', 'yes');
add_option("wp_erident_top_bg_repeat", 'repeat', '', 'yes');
add_option("wp_erident_top_bg_xpos", 'top', '', 'yes');
add_option("wp_erident_top_bg_ypos", 'left', '', 'yes');
add_option("wp_erident_login_bg_image", plugins_url('images/form_bg.jpg', __FILE__), '', 'yes');
add_option("wp_erident_login_bg_repeat", 'repeat', '', 'yes');
add_option("wp_erident_login_bg_xpos", 'top', '', 'yes');
add_option("wp_erident_login_bg_ypos", 'left', '', 'yes');

add_option("wp_erident_dashboard_delete_db", 'No', '', 'yes');
}

function wp_erident_dashboard_remove() {
	$check_db = get_option('wp_erident_dashboard_delete_db');
	if($check_db == Yes) { 
/* Deletes the database field */
delete_option('wp_erident_dashboard_data_left');
delete_option('wp_erident_dashboard_data_right');
delete_option('wp_erident_dashboard_image_logo');
delete_option('wp_erident_dashboard_power_text');

delete_option('wp_erident_dashboard_login_width');
delete_option('wp_erident_dashboard_login_radius');
delete_option('wp_erident_dashboard_login_border');
delete_option('wp_erident_dashboard_border_thick');
delete_option('wp_erident_dashboard_border_color');
delete_option('wp_erident_dashboard_login_bg');
delete_option('wp_erident_dashboard_text_color');
delete_option('wp_erident_dashboard_link_color');
delete_option('wp_erident_dashboard_check_shadow');
delete_option('wp_erident_dashboard_link_shadow');

delete_option('wp_erident_top_bg_color');
delete_option('wp_erident_top_bg_image');
delete_option('wp_erident_top_bg_repeat');
delete_option('wp_erident_top_bg_xpos');
delete_option('wp_erident_top_bg_ypos');
delete_option('wp_erident_login_bg_image');
delete_option('wp_erident_login_bg_repeat');
delete_option('wp_erident_login_bg_xpos');
delete_option('wp_erident_login_bg_ypos');

delete_option('wp_erident_dashboard_delete_db');
	}
	else { }
}


if ( is_admin() ){

/* Call the html code */
add_action('admin_menu', 'wp_erident_dashboard_admin_menu');

function wp_erident_dashboard_admin_menu() {
add_options_page('Custom Login and Dashboard', 'Custom Login and Dashboard', 'administrator',
'erident-custom-login-and-dashboard', 'wp_erident_dashboard_html_page');
}
}

function wp_erident_dashboard_html_page() {
?>

<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div>
<h2>Erident Custom Login and Dashboard Settings</h2>
<p><i>Plugin Loads default values for all below entries. Please change the values to yours.</i></p>
<form class="wp-erident-dashboard" method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<div class="postbox">
<h3 class="hndle"><span>Dashboard Settings</span>
<span class="postbox-title-action">(These settings will be reflected when a user/admin logins to the WordPress Dashboard)</span>
</h3>
<div class="inside">
<table border="0">
  <tr valign="top">
    <th scope="row">Enter the text for dashboard left side footer:</th>
    <td>
    <input class="er-textfield" name="wp_erident_dashboard_data_left" type="text" id="wp_erident_dashboard_data_left"
value="<?php echo get_option('wp_erident_dashboard_data_left'); ?>" placeholder="Text for dashboard left side footer" />
	<br />
    <span class="description">This will replace the default "Thank you for creating with WordPress" on the bottom left side of dashboard</span>
	</td>
  </tr>
  <tr valign="top">
    <th scope="row">Enter the text for dashboard right side footer:</th>
    <td><input class="er-textfield" name="wp_erident_dashboard_data_right" type="text" id="wp_erident_dashboard_data_right"
value="<?php echo get_option('wp_erident_dashboard_data_right'); ?>" placeholder="Text for dashboard left right footer"  />
    <br />
    <span class="description">This will replace the default "WordPress Version" on the bottom right side of dashboard</span>
    </td>
  </tr>
</table>
</div><!-- end inside -->
</div><!-- end postbox -->

<div class="postbox">
<h3 class="hndle"><span>Login Screen Background</span>
<span class="postbox-title-action">(The following settings will be reflected on the "wp-login.php" page)</span>
</h3>
<div class="inside">
<table border="0">
  <tr valign="top">
    <th scope="row">Login Screen Background Color:</th>
    <td>
    <input class="er-textfield-small" type="text" id="wp_erident_top_bg_color" name="wp_erident_top_bg_color" value="<?php echo get_option('wp_erident_top_bg_color'); ?>" />
    <div id="ilctabscolorpicker4"></div>
    <br />
    <span class="description">Click the box to select a color.</span>
    </td>
  </tr>
  
  <tr valign="top">
    <th scope="row">Login Screen Background Image:</th>
    <td><input class="er-textfield" name="wp_erident_top_bg_image" type="text" id="wp_erident_top_bg_image"
value="<?php echo get_option('wp_erident_top_bg_image'); ?>" />
    <br />
    <span class="description">Add your own pattern/image url for the screen background. Leave blank if you don't need any images.</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Login Screen Background Repeat</th>
    <td>
    <?php 
	$er_screen_repeat = get_option('wp_erident_top_bg_repeat');
    switch($er_screen_repeat)
		  		{
				case 'none':
				$er_screen_a='selected="selected"';
				$er_screen_b=$er_screen_c=$er_screen_d="";
				break;
				
				case 'repeat':
				$er_screen_b='selected="selected"';
				$er_screen_a=$er_screen_c=$er_screen_d="";
				break;
				
				case 'repeat-x':
				$er_screen_c='selected="selected"';
				$er_screen_a=$er_screen_b=$er_screen_d="";
				break;
				
				case 'repeat-y':
				$er_screen_d='selected="selected"';
				$er_screen_a=$er_screen_b=$er_screen_c="";
				break;
				
				default:
				break;
				}
     ?>       
    <select class="er-textfield-small" name="wp_erident_top_bg_repeat" id="wp_erident_top_bg_repeat">
      <option value="no-repeat" <?php echo $er_screen_a; ?>>No Repeat</option>
      <option value="repeat" <?php echo $er_screen_b; ?>>Repeat</option>
      <option value="repeat-x" <?php echo $er_screen_c; ?>>Repeat-x</option>
      <option value="repeat-y" <?php echo $er_screen_d; ?>>Repeat-y</option>
    </select>
    
    <br />
    <span class="description">Select an image repeat option from dropdown.</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Background Position:</th>
    <td>Horizontal Position: <input class="er-textfield-small" name="wp_erident_top_bg_xpos" type="text" id="wp_erident_top_bg_xpos"
value="<?php echo get_option('wp_erident_top_bg_xpos'); ?>" />
	Vertical Position: <input class="er-textfield-small" name="wp_erident_top_bg_ypos" type="text" id="wp_erident_top_bg_ypos"
value="<?php echo get_option('wp_erident_top_bg_ypos'); ?>" />
    <br />
    <span class="description">The background-position property sets the starting position of a background image. If you entering the value in "pixels" or "percentage", add "px" or "%" at the end of value. This will not show any changes if you set the Background Repeat option as "Repeat". <a href="http://www.w3schools.com/cssref/pr_background-position.asp" target="_blank">More Info</a></span>
    </td>
  </tr>
  
</table>
</div><!-- end inside -->
</div><!-- end postbox -->


<div class="postbox">
<h3 class="hndle"><span>Login Screen Logo</span>
<span class="postbox-title-action">(Change the default WordPress logo and powered by text)</span>
</h3> 
<div class="inside">
<table>  
  <tr valign="top">
    <th scope="row">Logo Url:</th>
    <td><input class="er-textfield" name="wp_erident_dashboard_image_logo" type="text" id="wp_erident_dashboard_image_logo"
value="<?php echo get_option('wp_erident_dashboard_image_logo'); ?>" /> <span class="description">Default Logo Size 274px × 63px</span>
    <br />
    <span class="description">(URL path to image to replace default WordPress Logo. (You can upload your image with the media uploader)</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Powered by Text:</th>
    <td><input class="er-textfield" name="wp_erident_dashboard_power_text" type="text" id="wp_erident_dashboard_power_text"
value="<?php echo get_option('wp_erident_dashboard_power_text'); ?>" />
    <br />
    <span class="description">Show when mouse hover over custom Login logo</span>
    </td>
  </tr>
</table>
</div><!-- end inside -->
</div><!-- end postbox -->


<div class="postbox">
<h3 class="hndle"><span>Login Form Settings</span>
<span class="postbox-title-action">(The following settings will change the Login Form style)</span>
</h3>
<div class="inside">
<table>
  <tr valign="top">
    <th scope="row">Login form width:</th>
    <td><input class="er-textfield-small" name="wp_erident_dashboard_login_width" type="text" id="wp_erident_dashboard_login_width"
value="<?php echo get_option('wp_erident_dashboard_login_width'); ?>" />px
    <br />
    <span class="description">Total Form width(Enter in pixels). Default: 350px</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Login Form Border Radius:</th>
    <td><input class="er-textfield-small" name="wp_erident_dashboard_login_radius" type="text" id="wp_erident_dashboard_login_radius"
value="<?php echo get_option('wp_erident_dashboard_login_radius'); ?>" />px
    <br />
    <span class="description">Border Radius of Login Form. This is the option to make the corners rounded.(Enter in pixels)</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Login Border Style</th>
    <td>
    <?php 
	$er_border = get_option('wp_erident_dashboard_login_border');
    switch($er_border)
		  		{
				case 'none':
				$er_a='selected="selected"';
				$er_b=$er_c=$er_d=$er_e="";
				break;
				
				case 'solid':
				$er_b='selected="selected"';
				$er_a=$er_c=$er_d=$er_e="";
				break;
				
				case 'dotted':
				$er_c='selected="selected"';
				$er_a=$er_b=$er_d=$er_e="";
				break;
				
				case 'dashed':
				$er_d='selected="selected"';
				$er_a=$er_b=$er_c=$er_e="";
				break;
				
				case 'double':
				$er_e='selected="selected"';
				$er_a=$er_b=$er_c=$er_d="";
				break;
				
				default:
				break;
				}
     ?>       
    <select class="er-textfield-small" name="wp_erident_dashboard_login_border" id="wp_erident_dashboard_login_border">
      <option value="none" <?php echo $er_a; ?>>None</option>
      <option value="solid" <?php echo $er_b; ?>>Solid</option>
      <option value="dotted" <?php echo $er_c; ?>>Dotted</option>
      <option value="dashed" <?php echo $er_d; ?>>Dashed</option>
      <option value="double" <?php echo $er_e; ?>>Double</option>
    </select>

    <br />
    <span class="description">Select a Border Style option from dropdown.</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Login Border Thickness:</th>
    <td><input class="er-textfield-small" name="wp_erident_dashboard_border_thick" type="text" id="wp_erident_dashboard_border_thick"
value="<?php echo get_option('wp_erident_dashboard_border_thick'); ?>" />px
    <br />
    <span class="description">Thickness of Border (Enter value in pixels)</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Login Border Color:</th>
    <td>
    <input class="er-textfield-small" type="text" id="wp_erident_dashboard_border_color" name="wp_erident_dashboard_border_color" value="<?php echo get_option('wp_erident_dashboard_border_color'); ?>" />
    <div id="ilctabscolorpicker"></div>
    <br />
    <span class="description">Click the box to select a color.</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Login Form Background Color:</th>
    <td>
    <input class="er-textfield-small" type="text" id="wp_erident_dashboard_login_bg" name="wp_erident_dashboard_login_bg" value="<?php echo get_option('wp_erident_dashboard_login_bg'); ?>" />
    <div id="ilctabscolorpicker2"></div>
    <br />
    <span class="description">Click the box to select a color.</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Login Form Background Image:</th>
    <td><input class="er-textfield" name="wp_erident_login_bg_image" type="text" id="wp_erident_login_bg_image"
value="<?php echo get_option('wp_erident_login_bg_image'); ?>" />
    <br />
    <span class="description">Add your own pattern/image url to the form background. Leave blank if you don't need any images.</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Login Form Background Repeat</th>
    <td>
    <?php 
	$er_form_repeat = get_option('wp_erident_login_bg_repeat');
    switch($er_form_repeat)
		  		{
				case 'none':
				$er_login_a='selected="selected"';
				$er_login_b=$er_login_c=$er_login_d="";
				break;
				
				case 'repeat':
				$er_login_b='selected="selected"';
				$er_login_a=$er_login_c=$er_login_d="";
				break;
				
				case 'repeat-x':
				$er_login_c='selected="selected"';
				$er_login_a=$er_login_b=$er_login_d="";
				break;
				
				case 'repeat-y':
				$er_login_d='selected="selected"';
				$er_login_a=$er_login_b=$er_login_c="";
				break;
				
				default:
				break;
				}
     ?>       
    <select class="er-textfield-small" name="wp_erident_login_bg_repeat" id="wp_erident_login_bg_repeat">
      <option value="no-repeat" <?php echo $er_login_a; ?>>No Repeat</option>
      <option value="repeat" <?php echo $er_login_b; ?>>Repeat</option>
      <option value="repeat-x" <?php echo $er_login_c; ?>>Repeat-x</option>
      <option value="repeat-y" <?php echo $er_login_d; ?>>Repeat-y</option>
    </select>
    
    <br />
    <span class="description">Select an image repeat option from dropdown.</span>
    </td>
  </tr>
  
  <tr valign="top">
    <th scope="row">Background Position:</th>
    <td>Horizontal Position: <input class="er-textfield-small" name="wp_erident_login_bg_xpos" type="text" id="wp_erident_login_bg_xpos"
value="<?php echo get_option('wp_erident_login_bg_xpos'); ?>" />
	Vertical Position: <input class="er-textfield-small" name="wp_erident_login_bg_ypos" type="text" id="wp_erident_login_bg_ypos"
value="<?php echo get_option('wp_erident_login_bg_ypos'); ?>" />
    <br />
    <span class="description">The background-position property sets the starting position of a background image. If you entering the value in "pixels" or "percentage", add "px" or "%" at the end of value. This will not show any changes if you set the Background Repeat option as "Repeat". <a href="http://www.w3schools.com/cssref/pr_background-position.asp" target="_blank">More Info</a></span>
    </td>
  </tr>
  
  <tr valign="top">
    <th scope="row">Login Form Text Color</th>
    <td>
    <input class="er-textfield-small" type="text" id="wp_erident_dashboard_text_color" name="wp_erident_dashboard_text_color" value="<?php echo get_option('wp_erident_dashboard_text_color'); ?>" />
    <div id="ilctabscolorpicker3"></div>
    <br />
    <span class="description">Click the box to select a color.</span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row">Login Form Link Color</th>
    <td>
    <input class="er-textfield-small" type="text" id="wp_erident_dashboard_link_color" name="wp_erident_dashboard_link_color" value="<?php echo get_option('wp_erident_dashboard_link_color'); ?>" />
    <div id="ilctabscolorpicker5"></div>
    <br />
    <span class="description">Click the box to select a color.</span>
    </td>
  </tr>
  
  <tr valign="top">
    <th scope="row">Enable link shadow?</th>
    <td>
    <?php 
	$check_sh = get_option('wp_erident_dashboard_check_shadow');
	if($check_sh == Yes) { $sx = "checked"; } else { $sy = "checked"; } ?>

      <label>
        <input type="radio" name="wp_erident_dashboard_check_shadow" value="Yes" id="wp_erident_dashboard_check_shadow_0" <?php echo $sx; ?>  onclick="$('#hide-this').show('normal')" />
        Yes</label>

      <label>
        <input type="radio" name="wp_erident_dashboard_check_shadow" value="No" id="wp_erident_dashboard_check_shadow_1" <?php echo $sy; ?> onclick="$('#hide-this').hide('normal')" />
        No</label>
    <br />
    <span class="description">(Check an option)</span>
    </td>
  </tr>
  <tr valign="top" id="hide-this">
    <th scope="row">Login Form Link Shadow Color</th>
    <td>
    <input class="er-textfield-small" type="text" id="wp_erident_dashboard_link_shadow" name="wp_erident_dashboard_link_shadow" value="<?php echo get_option('wp_erident_dashboard_link_shadow'); ?>" />
    <div id="ilctabscolorpicker6"></div>
    <br />
    <span class="description">Click the box to select a color.</span>
    </td>
  </tr>
  
</table>
</div><!-- end inside -->
</div><!-- end postbox -->


<div class="postbox">
<h3 class="hndle"><span>Plugin Un-install Settings</span>
</h3>
<div class="inside">
<table border="0">
  <tr valign="top">
    <th scope="row">Delete custom settings upon plugin deactivation?</th>
    <td>
    <?php 
	$check = get_option('wp_erident_dashboard_delete_db');
	if($check == Yes) { $x = "checked"; } else { $y = "checked"; } ?>

      <label>
        <input type="radio" name="wp_erident_dashboard_delete_db" value="Yes" id="wp_erident_dashboard_delete_db_0" <?php echo $x; ?> />
        Yes</label>

      <label>
        <input type="radio" name="wp_erident_dashboard_delete_db" value="No" id="wp_erident_dashboard_delete_db_1" <?php echo $y; ?> />
        No</label>
    <br />
    <span class="description">(If you set "Yes" all custom settings will be deleted from database upon plugin deactivation)</span>
    </td>
  </tr>
</table>
</div><!-- end inside -->
</div><!-- end postbox -->


<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="wp_erident_dashboard_data_left,wp_erident_dashboard_data_right,wp_erident_dashboard_image_logo,wp_erident_dashboard_power_text,wp_erident_dashboard_login_width,wp_erident_dashboard_login_radius,wp_erident_dashboard_login_border,wp_erident_dashboard_border_thick,wp_erident_dashboard_border_color,wp_erident_dashboard_login_bg,wp_erident_dashboard_text_color,wp_erident_dashboard_delete_db,wp_erident_top_bg_color,wp_erident_top_bg_image,wp_erident_top_bg_repeat,wp_erident_login_bg_image,wp_erident_login_bg_repeat,wp_erident_dashboard_link_color,wp_erident_dashboard_link_shadow,wp_erident_dashboard_check_shadow,wp_erident_top_bg_xpos,wp_erident_top_bg_ypos,wp_erident_login_bg_xpos,wp_erident_login_bg_xpos" />

<p>
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>

<div class="er_notice2">
<h3>Quick Links</h3>
<ul>
    <li class="login-page"><a href="<?php bloginfo( 'wpurl' ); ?>/wp-login.php" target="_blank">Open Your WP Login Page in a New Tab</a></li>
    <li><a href="http://wordpress.org/extend/plugins/erident-custom-login-and-dashboard/" target="_blank">Plugin Documentation</a></li>
    <li><a href="http://wordpress.org/support/plugin/erident-custom-login-and-dashboard" target="_blank">Plugin Support Page</a></li>
    <li><a href="http://www.eridenttech.com/wp-plugins/erident-custom-login-and-dashboard" target="_blank">Plugin Website</a></li>
</ul>
</div>
	<div class="er_notice">
		<h3>Wants to customize your WordPress theme?</h3>
		<p>If you wants professional customization to your wordpress themes, Contact <a href="http://www.eridenttech.com" target="_blank">Erident Technologies</a></p>
		<ul>
			<li><a href="http://www.eridenttech.com/portfolio" target="_blank">Our WebDesign Portfolio</a></li>
			<li><a href="http://www.facebook.com/EridentTechnologies" target="_blank">Follow Us on Facebook</a></li>
            <li><a href="http://twitter.com/eridenttech" target="_blank">Find Us on Twitter</a></li>
		</ul>
	</div>
    
</div>
<script type="text/javascript">
 
  jQuery(document).ready(function() {
    jQuery('#ilctabscolorpicker').hide();
    jQuery('#ilctabscolorpicker').farbtastic("#wp_erident_dashboard_border_color");
    jQuery("#wp_erident_dashboard_border_color").click(function(){jQuery('#ilctabscolorpicker').slideDown()});
	jQuery("#wp_erident_dashboard_border_color").blur(function(){jQuery('#ilctabscolorpicker').slideUp()});
	
	jQuery('#ilctabscolorpicker2').hide();
	jQuery('#ilctabscolorpicker2').farbtastic("#wp_erident_dashboard_login_bg");
    jQuery("#wp_erident_dashboard_login_bg").click(function(){jQuery('#ilctabscolorpicker2').slideDown()});
	jQuery("#wp_erident_dashboard_login_bg").blur(function(){jQuery('#ilctabscolorpicker2').slideUp()});
	
	jQuery('#ilctabscolorpicker3').hide();
	jQuery('#ilctabscolorpicker3').farbtastic("#wp_erident_dashboard_text_color");
    jQuery("#wp_erident_dashboard_text_color").click(function(){jQuery('#ilctabscolorpicker3').slideDown()});
	jQuery("#wp_erident_dashboard_text_color").blur(function(){jQuery('#ilctabscolorpicker3').slideUp()});
	
	jQuery('#ilctabscolorpicker4').hide();
	jQuery('#ilctabscolorpicker4').farbtastic("#wp_erident_top_bg_color");
    jQuery("#wp_erident_top_bg_color").click(function(){jQuery('#ilctabscolorpicker4').slideDown()});
	jQuery("#wp_erident_top_bg_color").blur(function(){jQuery('#ilctabscolorpicker4').slideUp()});
	
	jQuery('#ilctabscolorpicker5').hide();
	jQuery('#ilctabscolorpicker5').farbtastic("#wp_erident_dashboard_link_color");
    jQuery("#wp_erident_dashboard_link_color").click(function(){jQuery('#ilctabscolorpicker5').slideDown()});
	jQuery("#wp_erident_dashboard_link_color").blur(function(){jQuery('#ilctabscolorpicker5').slideUp()});
	
	jQuery('#ilctabscolorpicker6').hide();
	jQuery('#ilctabscolorpicker6').farbtastic("#wp_erident_dashboard_link_shadow");
    jQuery("#wp_erident_dashboard_link_shadow").click(function(){jQuery('#ilctabscolorpicker6').slideDown()});
	jQuery("#wp_erident_dashboard_link_shadow").blur(function(){jQuery('#ilctabscolorpicker6').slideUp()});
  });
 
</script>
<?php
}
?>