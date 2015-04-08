<?php
/*---------------------------------------------------
Social Icons generator for front-end
----------------------------------------------------*/
//Checking is style is square or round.
$awesome_social_opt_front = array(
				'square' => array(
								'value' => 'square',
								'label' => __('', 'awesome-social')
				),
				'round' => array(
								'value' => 'round',
								'label' => __('', 'awesome-social')
				)
);
global $awesome_social_style;
if (!isset($checked))
				$checked = '';
foreach ($awesome_social_opt_front as $option)
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
		}
class Making_Awesome_Icons
		{
		// Generating Icons with respective links
				function Create_Awesome_Icons()
						{
						
								$options = get_option('awesome_social_options');
								// Checking if target is _self or _blank
								if ($options['linksnewtab'] == 1)
										{
												$target = 'target="_blank"';
										}
								else
										{
												$target = 'target="_self"';
										}
								// Checking if social icon value is set from admin settings then display that icon, other wise not.
								if ($options['facebook'])
										{
												$facebook = $options['facebook'];
												echo "<a  $target  class='awesome_social_icons_links' href='$facebook'>	<i class='fa fa-facebook fb-awesome-social awesome-social'></i></a>";
										}
								if ($options['twitter'])
										{
												$twitter = $options['twitter'];
												echo "<a $target class='awesome_social_icons_links' href='$twitter'>	<i class='fa fa-twitter tw-awesome-social awesome-social'></i></a>";
										}
								if ($options['rss'])
										{
												$rss = $options['rss'];
												echo "<a $target class='awesome_social_icons_links' href='$rss'>	<i class='fa fa-rss rss-awesome-social awesome-social'></i></a>";
										}
								if ($options['linkedin'])
										{
												$linkedin = $options['linkedin'];
												echo "<a $target class='awesome_social_icons_links' href='$linkedin'>	<i class='fa fa-linkedin linkedin-awesome-social awesome-social'></i></a>";
										}
								if ($options['youtube'])
										{
												$youtube = $options['youtube'];
												echo "<a $target class='awesome_social_icons_links' href='$youtube'>	<i class='fa fa-youtube youtube-awesome-social awesome-social'></i></a>";
										}
								if ($options['flickr'])
										{
												$flickr = $options['flickr'];
												echo "<a $target class='awesome_social_icons_links' href='$flickr'>	<i class='fa fa-flickr flickr-awesome-social awesome-social'></i></a>";
										}
								if ($options['pinterest'])
										{
												$pinterest = $options['pinterest'];
												echo "<a $target class='awesome_social_icons_links' href='$pinterest'>	<i class='fa fa-pinterest pinterest-awesome-social awesome-social'></i></a>";
										}
								if ($options['stumbleupon'])
										{
												$stumbleupon = $options['stumbleupon'];
												echo "<a $target class='awesome_social_icons_links' href='$stumbleupon'>	<i class='fa fa-stumbleupon stumbleupon-awesome-social awesome-social'></i></a>";
										}
								if ($options['google-plus'])
										{
												$google = $options['google-plus'];
												echo "<a $target class='awesome_social_icons_links' href='$google'>	<i class='fa fa-google-plus google-plus-awesome-social awesome-social'></i></a>";
										}
								if ($options['instagram'])
										{
												$instagram = $options['instagram'];
												echo "<a $target class='awesome_social_icons_links' href='$instagram'>	<i class='fa fa-instagram instagram-awesome-social awesome-social'></i></a>";
										}
								if ($options['tumblr'])
										{
												$tumblr = $options['tumblr'];
												echo "<a $target class='awesome_social_icons_links' href='$tumblr'>	<i class='fa fa-tumblr tumblr-awesome-social awesome-social'></i></a>";
										}
								if ($options['vine'])
										{
												$vine = $options['vine'];
												echo "<a $target class='awesome_social_icons_links' href='$vine'>	<i class='fa fa-vine vine-awesome-social awesome-social'></i></a>";
										}
						}
		}
?>