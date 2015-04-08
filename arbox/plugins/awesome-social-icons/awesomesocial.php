<?php
/*
Plugin Name: Awesome Social Icons
Plugin URI: http://photontechs.com/awesome-social-icons
Description: This Awesome Social Icons plugin allow you to put social icons which can be link with your social media profiles.
Version: 1.3
Author: Daniyal Ahmed
Author URI: http://www.photontechs.com
License: GNU General Public License v3.0
License URI: http://www.opensource.org/licenses/gpl-license.php
NOTE: This plugin is released under the GPLv2 license. The icons used in this plugin are the property
of their respective owners, and do not, necessarily, inherit the GPLv2 license.
*/
// Adding Admin Menu
require_once('inc/awesome_social_admin.php');
// Getting Values from options
$options = get_option('awesome_social_options');
// Creating Icons
require_once('inc/awesome_social_func.php');
// Getting Style for awesome icons
require_once('inc/awesome_social_scripts.php');
// Add settings link on plugin page
function awesome_social_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=awesome-social">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'awesome_social_settings_link' );
//Making Widget
class AwesomeSocial extends WP_Widget
		{
				function AwesomeSocial()
						{
								$widget_ops = array(
												'classname' => 'AwesomeSocial',
												'description' => 'Displays a Social icons.'
								);
								$this->WP_Widget('AwesomeSocial', 'Awesome Social Icons', $widget_ops);
						}
				function form($instance)
						{
								$instance = wp_parse_args((array) $instance, array(
												'title' => ''
								));
								$title    = $instance['title'];
								// Title for widget area
						?>
						  <p><label for="<?php
														echo $this->get_field_id('title');
						?>"><?php _e("Title: <br />","awesome_social");?><input id="<?php
														echo $this->get_field_id('title');
						?>" name="<?php
														echo $this->get_field_name('title');
						?>" type="text" value="<?php
														echo attribute_escape($title);
						?>" /></label></p>
						<?php
						}
				function update($new_instance, $old_instance)
						{
								$instance          = $old_instance;
								$instance['title'] = $new_instance['title'];
								return $instance;
						}
				function widget($args, $instance)
						{
								extract($args, EXTR_SKIP);
								echo $before_widget;
								$title   = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
								$options = get_option('awesome_social_options');
							
								echo "<div>";
								if (!empty($title))
												echo $before_title . $title . $after_title;
								;
								$makeawesome_icons = new Making_Awesome_Icons;
								// Getting Icons for widget
								$makeawesome_icons->Create_Awesome_Icons();
                                echo "</div>";								
                                echo $after_widget;
								
						}
		}
		// Making Shortcode
function awesome_social_shortcode()
		{
				$makeawesome_icons = new Making_Awesome_Icons;
				// Getting Icons for Shortcode
				$makeawesome_icons->Create_Awesome_Icons();
		}
add_shortcode("awesome_social_icon", "awesome_social_shortcode");
add_action('widgets_init', create_function('', 'return register_widget("AwesomeSocial");'));
?>
