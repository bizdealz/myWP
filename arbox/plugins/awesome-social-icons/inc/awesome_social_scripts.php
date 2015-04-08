<?php
/*---------------------------------------------------
Styling for social icons
----------------------------------------------------*/
function awesome_social_scripts()
		{
				$options = get_option('awesome_social_options');
				global $awesome_social_style;
				echo '<link href="'.plugin_dir_url(__FILE__).'css/font-awesome.min.css" rel="stylesheet">';
			?>
			<style>
			.awesome-social
			{
                margin-top:2px;
			color: white !important;
			text-align: center !important;
			<?php
			// Checking size if its large then set it
			if($options['size']=="48")
			{
			?>
			line-height: 51px !important;
			width: 48px !important;
			height: 48px !important;
			font-size: 2em !important;
			<?php
			}
			if($options['size']=="32")
			{
			// Checking size if its medium then set it
			?>
			line-height: 34px !important;
			width: 32px !important;
			height: 32px !important;
			font-size: 1.5em !important;
			<?php
			}
			if($options['size']=="24")
			{
			// Checking size if its small then set it
			?>
			line-height: 25px !important;
			width: 24px !important;
			height: 24px !important;
			font-size: 1em !important;
			<?php
			}
			?>

			<?php
			// If there is shadow settings
			if ($options['shadow'] == 1){}
			else
			{
			echo "text-shadow: 2px 2px 4px #000000;";
			}
			?>
			<?php
			// If there is round social icon settings
			if ($awesome_social_style == "round")
			{
			?>
			border-radius:50% !important;
			<?php
			}
			// If animation setting then use CSS3 for animation
			if ($options['animations'] == 1)
				{
				?>
				-webkit-transition: width <?php echo $options['animate_sec']; ?>s, height <?php echo $options['animate_sec']; ?>s, -webkit-transform <?php echo $options['animate_sec']; ?>s; /* For Safari 3.1 to 6.0 */
				transition: width <?php echo $options['animate_sec']; ?>s, height <?php echo $options['animate_sec']; ?>s, transform <?php echo $options['animate_sec']; ?>s;
				
				<?php
				}
				?>
				
			}
			<?php
			// Again if animation settings
				if ($options['animations'] == 1)
									{
									
									?>
			.awesome-social:hover
			{
                
			-webkit-transform: rotate(360deg); /* Chrome, Safari, Opera */
				transform: rotate(deg);
			}	
				<?php
				}
				?>
			.awesome_social_icons_links
			{
			outline:0 !important;
			}
			.awesome_social_icons_links:hover{
			text-decoration:none !important;
			}
			<?php // Social icons background settings ?>
			.fb-awesome-social
			{
			background: #3b5998;
			}
			.tw-awesome-social
			{
			background:#00aced;
			}
			.rss-awesome-social
			{
			background:#FA9B39;
			}
			.linkedin-awesome-social
			{
			background:#007bb6;
			}
			.youtube-awesome-social
			{
			background:#bb0000;
			}
			.flickr-awesome-social
			{
			background: #ff0084;
			}
			.pinterest-awesome-social
			{
			background:#cb2027;
			}
			.stumbleupon-awesome-social
			{
			background:#f74425 ;
			}
			.google-plus-awesome-social
			{
			background:#f74425 ;
			}
			.instagram-awesome-social
			{
			background:#517fa4 ;
			}
			.tumblr-awesome-social
			{
			background: #32506d ;
			}
			.vine-awesome-social
			{
			background: #00bf8f ;
			}
        /* Custom Background */     
                .awesome-social {
            background:<?php  echo $options['colorpicker'] ?> !important;
                }
             <?php
                    if($options['hover']==1)
                {
                    ?>
             .awesome-social{
                
-webkit-transition-property:color, text;
-webkit-transition-duration: 0.25s, 0.25s;
-webkit-transition-timing-function: linear, ease-in;
-moz-transition-property:color, text;
-moz-transition-duration:0.25s;
-moz-transition-timing-function: linear, ease-in;

-o-transition-property:color, text;
-o-transition-duration:0.25s;
-o-transition-timing-function: linear, ease-in;
             }
            .fb-awesome-social:hover
			{
			color: #3b5998 !important;
			}
			.tw-awesome-social:hover
			{
			color:#00aced !important;
			}
			.rss-awesome-social:hover
			{
			color:#FA9B39 !important;
			}
			.linkedin-awesome-social:hover
			{
			color:#007bb6 !important;
			}
			.youtube-awesome-social:hover
			{
			color:#bb0000 !important;
			}
			.flickr-awesome-social:hover
			{
			color: #ff0084 !important;
			}
			.pinterest-awesome-social:hover
			{
			color:#cb2027 !important;
			}
			.stumbleupon-awesome-social:hover
			{
			color:#f74425  !important;
			}
			.google-plus-awesome-social:hover
			{
			color:#f74425  !important;
			}
			.instagram-awesome-social:hover
			{
			color:#517fa4  !important;
			}
			.tumblr-awesome-social:hover
			{
			color: #32506d  !important;
			}
			.vine-awesome-social:hover
			{
			color: #00bf8f  !important;
			}
                 <?php
                }
                    ?>
              
                    
			</style>
<?php
	}
	add_action('wp_enqueue_scripts', 'awesome_social_scripts');
?>