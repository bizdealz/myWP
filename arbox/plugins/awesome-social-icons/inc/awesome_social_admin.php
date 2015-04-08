<?php

/*---------------------------------------------------

add settings page to menu

----------------------------------------------------*/

function register_awesome_social_submenu_page()

		{

				add_submenu_page('options-general.php', 'Awesome Social', 'Awesome Social', 'manage_options', 'awesome-social', 'awesome_social_callback');

		}

add_action('admin_menu', 'register_awesome_social_submenu_page');

//Getting square icons image for Admin Settings 

$awesome_sq_icon = "Square Icons  <img src='" . plugin_dir_url(__FILE__) . "img/square_logo.png' /> &nbsp;  &nbsp;  &nbsp; ";

//Getting round icons image for Admin Settings 

$awesome_ro_icon = "Round Icons  <img src='" . plugin_dir_url(__FILE__) . "img/round_logo.png' />";

$style_options   = array(

				'square' => array(

								'value' => 'square',

								'label' => __($awesome_sq_icon, 'sampletheme')

				),

				'round' => array(

								'value' => 'round',

								'label' => __($awesome_ro_icon, 'sampletheme')

				)

);

$size_options = array(

	'48' => array(

		'value' =>	'48',

		'label' => __( 'Large', 'sampletheme' )

	),

	'32' => array(

		'value' =>	'32',

		'label' => __( 'Medium', 'sampletheme' )

	),

	'24' => array(

		'value' => '24',

		'label' => __( 'Small', 'sampletheme' )

	)

);

// Awesome Social  call back for Admin Settings

function awesome_social_callback()

		{
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-form');
      wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker');
    wp_enqueue_script( 'wp-color-picker-script-handle', plugins_url('wp-color-picker-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
?>
<link href="<?php echo plugin_dir_url(__FILE__); ?>css/admin_style.css" rel="stylesheet">
<link href="<?php echo plugin_dir_url(__FILE__); ?>css/font-awesome.min.css" rel="stylesheet">
<?php
		settings_fields('awesome_social');
		$options = get_option('awesome_social_options');

				_e('<div class="wrap"><div id="icon-tools" class="icon32"></div>',"awesome_social");

				_e('<h2>Awesome Social Icons</h2>',"awesome_social");

		?>

		<script>

		// Activating tabs for Admin Settings
(function( $ ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.color-field').wpColorPicker();
    });
     
})( jQuery );
            (function( $ ) {
    $(function() {
         
        // Add Color Picker to all inputs that have 'color-field' class
        $( '.cpa-color-picker' ).wpColorPicker();
         
    });
})( jQuery );
            
		$j=jQuery.noConflict();

		$j(document).ready(function(){

		$j("#awesome_social_options_anim_sec").hide();

		$j("#awesome_social_options_anim_s").hide();

		$j('#awesome_social_animate').change(function() {

		if($j(this).is(":checked")) {

		$j("#awesome_social_options_anim_sec").fadeIn();

		$j("#awesome_social_options_anim_s").fadeIn();

		}

		else{

		$j("#awesome_social_options_anim_s").hide();

		$j("#awesome_social_options_anim_sec").fadeOut();

		}

		});

		$j("#awesome_settings").hide();

		$j("#tab_awsome_settings").click(function(){

		$j("#awesome_links").fadeOut();

		$j("#awesome_settings").fadeIn();

		});

		$j("#tab_awsome_links").click(function(){

		$j("#awesome_links").fadeIn();

		$j("#awesome_settings").fadeOut();

		});

		<?php

		// If animation checked, then show corresponding text box for seconds

		if ($options['animations'] == 1)

		{

		?>

			   $j("#awesome_social_options_anim_sec").show();

			   $j("#awesome_social_options_anim_s").show(100);

		<?php

		}

		?>

		

		});

		</script>

		<div class="awesome_spacer"></div>

		<div class="awesome_social_tabs">

		<a href="javascript:void(0);" id="tab_awsome_links"><span class="awesome_tabs">Links</span></a>

		<a href="javascript:void(0);"  id="tab_awsome_settings"><span class="awesome_tabs">Settings</span></a>

		</div>

		<div class="awesome_spacer"></div>

		<form method="post" action="options.php" id="awesome_social_form">

			<?php

				settings_fields('awesome_social');

				$options = get_option('awesome_social_options');

			?>

			<div id="awesome_settings">

			<?php

			// Getting social icons style type i.e Round or Sqaure for Admin Settings

				global $style_options,$size_options;

				$awesome_social_style = "";

				if (!isset($checked))

								$checked = '';

				foreach ($style_options as $option)

						{

								$radio_setting = $options['style_input'];

								if ('' != $radio_setting)

										{

												if ($options['style_input'] == $option['value'])

														{

																$awesome_social_style = $options['style_input'];

																$checked              = "checked=\"checked\"";

														}

												else

														{

																$checked = '';

														}

										}

			?>

											<label class="awesome_description"><input type="radio" name="awesome_social_options[style_input]" value="<?php

											esc_attr_e($option['value']);

			?>" <?php

											echo $checked;

			?> /> <?php

											echo $option['label'];

			?></label>

											<?php

									}

			?>

									<br />
<div id="other_settings">
				<h3><?php _e("Other Settings","awesome_social"); ?></h3>

					<hr />

					<table>

					<tr style="height:45px;">

					<td style="width: 300px;">

		<?php // Open Links in new tab setting ?>			

						<input id="awesome_social_options[linksnewtab]" name="awesome_social_options[linksnewtab]" type="checkbox" value="1" <?php

							checked('1', $options['linksnewtab']);

			?>  />

					

						<label class="awesome_description" for="awesome_social_options[linksnewtab]"><strong><?php _e("Open links new tab.","awesome_social"); ?></strong></label>

						</td>

			<?php // Animation Settings ?>			

									<td>

									<input id="awesome_social_animate" name="awesome_social_options[animations]" type="checkbox" value="1" <?php

							checked('1', $options['animations']);

			?>  />

					

						<label class="awesome_description" for="awesome_social_animate"><strong><?php _e("Animation on hover","awesome_social"); ?> </strong></label>

			<?php // Animation time settings ?>		

						<input  id="awesome_social_options_anim_sec" value="<?php

							esc_attr_e($options['animate_sec']);

			?>" name="awesome_social_options[animate_sec]" type="text" placeholder="Delay in Seconds" style="width:250px;" /> 

			<span id="awesome_social_options_anim_s"><em><?php _e("Animation delay in seconds. For-example 0.5","awesome_social") ?></em></span>



						</td>

						

						</tr>

			<tr style="height:45px;">

			<td>

			<hr />

			<?php // Font Shadow settings ?>

						<input id="awesome_social_options[shadow]" name="awesome_social_options[shadow]" type="checkbox" value="1" <?php

							checked('1', $options['shadow']);

			?>  />

									

							

						<label class="awesome_description" for="awesome_social_options[shadow]"><strong><?php _e("Don't use shadow in icons.","awesome_social"); ?></strong></label>

				</td>

				<td style="padding-top:8px;">

				<hr />

				<select name="awesome_social_options[size]">

										<?php

										// Setting size of Social icons. 

								$selected = $options['size'];

								$p = '';

								$r = '';



								foreach ( $size_options as $option ) {

									$label = $option['label'];

									if ( $selected == $option['value'] ) // Make default first in list

										$p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";

									else

										$r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";

								}

								echo $p . $r;

							?>

						</select>

						<label class="description" for="awesome_social_options[size]"><?php _e( '<strong>Select size of icons</strong>', 'awesome_social' ); ?></label>

								</td>
                </tr>
                        <tr>
                		<td>

			<hr />
<?php
    add_action( 'admin_enqueue_scripts', 'awesome_color_picker' );
function awesome_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}

    ?>
			

						<label class="samecolor" for="awesome_social_options[colorpicker]"><strong><?php _e("Use custom background color for all icons.","awesome_social"); ?></strong></label>
<input class="cpa-color-picker"  id="awesome_social_options[colorpicker]" value="<?php

							esc_attr_e($options['colorpicker']);

			?>" name="awesome_social_options[colorpicker]" type="text"  />
				</td>
                            			<td>


			<?php // on hover color settings ?>

						<input id="awesome_social_options[hover]" name="awesome_social_options[hover]" type="checkbox" value="1" <?php

							checked('1', $options['hover']);

			?>  />

									

							

						<label class="awesome_description" for="awesome_social_options[hover]"><strong><?php _e("Change icon color on hover - For Custom Background","awesome_social"); ?></strong></label>

				</td>

                        </tr>

				

					

							</table>


                </div>
									</div>

			<div id="awesome_links">

<?php // Setting links for social icons from Admin Settings ?>

				<strong><?php _e("Enter the URL(s) for your various social profiles below. If you leave a profile URL field blank, it will not be used.","awesome_social") ?> </strong>

				<div class="awesome_spacer"></div>

							<table id="icons">

				<tr>

					<td>

					<i class="fa fa-facebook"></i><label for="awesome_social_options[facebook]" > Facebook</label><br /><input  id="awesome_social_options[facebook]" value="<?php

							esc_attr_e($options['facebook']);

			?>" name="awesome_social_options[facebook]" type="text" placeholder="Facebook URL" />

					</td>

					<td>

					<i class="fa fa-twitter"></i><label for="awesome_social_options[twitter]" > Twitter</label><br /><input  id="awesome_social_options[twitter]" value="<?php

							esc_attr_e($options['twitter']);

			?>" name="awesome_social_options[twitter]" type="text" placeholder="Twitter URL" />

					



					</td>

					<td>

					<i class="fa fa-rss"></i><label for="awesome_social_options[rss]" > Rss</label><br /><input  id="awesome_social_options[rss]" value="<?php

							esc_attr_e($options['rss']);

			?>" name="awesome_social_options[rss]" type="text" placeholder="Rss URL" />

				



					</td>

				</tr>

				<tr>

				

					<td>

						<hr />

					<i class="fa fa-linkedin"></i><label for="awesome_social_options[linkedin]" > Linkedin</label><br /><input  id="awesome_social_options[linkedin]" value="<?php

							esc_attr_e($options['linkedin']);

			?>" name="awesome_social_options[linkedin]" type="text" placeholder="Linkedin URL" />

									

						

					</td>

					<td>

						<hr />

					<i class="fa fa-youtube"></i><label for="awesome_social_options[youtube]" > Youtube</label><br /><input  id="awesome_social_options[youtube]" value="<?php

							esc_attr_e($options['youtube']);

			?>" name="awesome_social_options[youtube]" type="text" placeholder="Youtube URL" />

					</td>

					<td>

						<hr />

						<i class="fa fa-flickr"></i><label for="awesome_social_options[flickr]" > Flickr</label><br /><input  id="awesome_social_options[flickr]" value="<?php

							esc_attr_e($options['flickr']);

			?>" name="awesome_social_options[flickr]" type="text" placeholder="Flickr URL" />



						

					</td>

				</tr>



					<tr>

					<td>

						<hr />

											<i class="fa fa-pinterest"></i><label for="awesome_social_options[pinterest]" > Pinterest</label><br /><input  id="awesome_social_options[pinterest]" value="<?php

							esc_attr_e($options['pinterest']);

			?>" name="awesome_social_options[pinterest]" type="text" placeholder="Pinterest URL" />

						

						</td>

					<td>

						<hr />

												<i class="fa fa-stumbleupon"></i><label for="awesome_social_options[stumbleupon]" > Stumbleupon</label><br /><input  id="awesome_social_options[stumbleupon]" value="<?php

							esc_attr_e($options['stumbleupon']);

			?>" name="awesome_social_options[stumbleupon]" type="text" placeholder="Stumbleupon URL" />

						

					

				</td>

					<td>

						<hr />

						

															<i class="fa fa-google-plus"></i><label for="awesome_social_options[google-plus]" > Google Plus</label><br /><input  id="awesome_social_options[google-plus]" value="<?php

							esc_attr_e($options['google-plus']);

			?>" name="awesome_social_options[google-plus]" type="text" placeholder="Google Plus URL" />

						

					

					</td>

				</tr>

				

						<tr>

					<td>

						<hr />

								

					<i class="fa fa-instagram"></i><label for="awesome_social_options[instagram]" > Instagram</label><br /><input  id="awesome_social_options[instagram]" value="<?php

							esc_attr_e($options['instagram']);

			?>" name="awesome_social_options[instagram]" type="text" placeholder="Instagram URL" />

						

				

					</td>

					<td>

						<hr />

							<i class="fa fa-tumblr"></i><label for="awesome_social_options[tumblr]" > Tumblr</label><br /><input  id="awesome_social_options[tumblr]" value="<?php

							esc_attr_e($options['tumblr']);

			?>" name="awesome_social_options[tumblr]" type="text" placeholder="Tumblr URL" />

						

				

					</td>

					<td>

						<hr />

						

									<i class="fa fa-vine"></i><label for="awesome_social_options[vine]" > Vine</label><br /><input  id="awesome_social_options[vine]" value="<?php

							esc_attr_e($options['vine']);

			?>" name="awesome_social_options[vine]" type="text" placeholder="Vine URL" />

						

				</td>

				</tr>

			</table>		

			</div>

					<p class="submit">

							<input type="submit" class="button-primary" value="<?php

							_e('Save Options', 'awesomesocial');

			?>" />

						<?php

							echo "<img src='" . plugin_dir_url(__FILE__) . "img/loading.gif' id='awesome_social_load'/>";

			?>

						</p>

							<div class="awesome_spacer"></div>

								<div class="awesome_spacer"></div>

									<div class="awesome_spacer"></div>

									<p>Made With Love - By Daniyal Ahmed (<a href="http://photontechs.com/" target="_blank" >Photon Technologies</a>).</p><br />
        

					</form>

				<div id="saveResult"></div>

			<script type="text/javascript">

					jQuery(document).ready(function() {

					jQuery("#awesome_social_load").hide();

					jQuery('#awesome_social_form').submit(function() { 

					jQuery("#awesome_social_load").show();

					jQuery(this).ajaxSubmit({
                       

					success: function(){

					jQuery("#awesome_social_load").hide();

					jQuery('#saveResult').html("<div id='saveMessage' class='successModal'></div>");

					jQuery('#saveMessage').append("<p><?php

					echo htmlentities(__('Settings Saved Successfully', 'wp'), ENT_QUOTES);

					?></p>").show();

							 }, 

							 timeout:5000,
                error: function(data){
                 		jQuery("#awesome_social_load").hide();
                        
					jQuery('#saveResult').html("<div id='saveMessage' class='successModal'></div>");

					jQuery('#saveMessage').append("<p><?php

					echo htmlentities(__('Settings Saved Successfully', 'wp'), ENT_QUOTES);

					?></p>").show();
                    }
						  }); 

						  setTimeout("jQuery('#saveMessage').hide('slow');", 5000);

						  return false; 

					   });

					});

			</script>

			<?php

							echo '</div>';

		

		}

add_action('admin_init', 'awesome_social_options_init');

/**

 * Init plugin options to white list our options

 */

function awesome_social_options_init()

		{

				register_setting('awesome_social', 'awesome_social_options', 'awesome_social_icon_validate');

		}

function awesome_social_icon_validate($input)

		{

				global $size_options, $style_options;

				// Link new tab checkbox value is either 0 or 1

				if (!isset($input['linksnewtab']))

				$input['linksnewtab'] = null;

				$input['linksnewtab'] = ($input['linksnewtab'] == 1 ? 1 : 0);

				if (!isset($input['animations']))

				// Animation checkbox value is either 0 or 1

				$input['animations'] = null;

				$input['animations'] = ($input['animations'] == 1 ? 1 : 0);

				// Shadow checkbox value is either 0 or 1

				if (!isset($input['shadow']))

				$input['shadow'] = null;

				$input['shadow'] = ($input['shadow'] == 1 ? 1 : 0);

				// Our style option must actually be in our array of style options

				if (!isset($input['style_input']))

				$input['style_input'] = null;

				if (!array_key_exists($input['style_input'], $style_options))

				$input['style_input'] = null;

				// Social profiles links must be safe links with no HTML tags

				$input['facebook']     = wp_filter_nohtml_kses($input['facebook']);

				$input['twitter']     = wp_filter_nohtml_kses($input['twitter']);

				$input['rss']     = wp_filter_nohtml_kses($input['rss']);

				$input['linkedin']     = wp_filter_nohtml_kses($input['linkedin']);

				$input['youtube']     = wp_filter_nohtml_kses($input['youtube']);

				$input['flickr']     = wp_filter_nohtml_kses($input['flickr']);

				$input['pinterest']     = wp_filter_nohtml_kses($input['pinterest']);

				$input['stumbleupon']     = wp_filter_nohtml_kses($input['stumbleupon']);

				$input['google-plus']     = wp_filter_nohtml_kses($input['google-plus']);

				$input['instagram']     = wp_filter_nohtml_kses($input['instagram']);

				$input['tumblr']     = wp_filter_nohtml_kses($input['tumblr']);

				$input['vine']     = wp_filter_nohtml_kses($input['vine']);

				// Our size option must actually be in our array of size options

				if ( ! array_key_exists( $input['size'], $size_options ) )

				$input['size'] = null;

				return $input;

		}

?>