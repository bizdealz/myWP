<!-- A3 Admin Panel -->
<link rel="stylesheet" href="<?php echo plugins_url( '/Styles/Admin.css' , __FILE__ ); ?>" />
<link rel="stylesheet" href="<?php echo plugins_url( '/Styles/Icon-Map.css' , __FILE__ ); ?>" />

<script src="<?php echo plugins_url( '/Scripts/Admin.js' , __FILE__ ); ?>"></script>

<div class="wrap">
	<?php screen_icon( "options-general" ); ?>
	<h2>A3 <span class="txt-lgray">//</span> Social Sidebar</h2>
	
	<form method="post" action="options.php" id="A3SCS-Form">
		<?php settings_fields( "A3SCS_Options" ); ?>
		<?php $Options = get_option( "A3SCS" ); ?>

		<div class="metabox-holder has-right-sidebar">

			<!-- Sidebar -->
			<div class="inner-sidebar" id="A3SCS-Sidebar">
			
				<div class="postbox" id="A3SCS-Sidebar-Save">
					<div class="inside">
						<input type="submit" class="button-primary" style="width: 257px;" value="Save Changes" />
					</div>
				</div>
	
				<div class="postbox" id="A3SCS-Sidebar-About">
					<div class="handlediv" title="Click to Toggle"></div>
					<h3 class="hndle">Developer Info</h3>
					<div class="inside txt-justify">
						<a href="https://a3labs.net" target="_blank"><strong>A3 Labs, Inc.</strong></a> specializes in 
						web development, UI/UX design, and just about anything else we can think up.
						<div class="txt-center" style="margin-top: 10px;">
							<a href="http://facebook.com/a3labs" target="_blank">Facebook Page</a>
							&nbsp;&nbsp;<span class="txt-lgray">//</span>&nbsp;&nbsp;
							<a href="http://twitter.com/A3Labs" target="_blank">Twitter @A3Labs</a>
						</div>
					</div>
				</div>
	
				<div class="postbox" id="A3SCS-Sidebar-Support">
					<div class="handlediv" title="Click to Toggle"></div>
					<h3 class="hndle">Support &amp; Feedback</h3>
					<div class="inside txt-justify">
						Having trouble with the plugin?<br />Please check out our 	
						<a href="http://a3webtools.com/support/social-sidebar/" target="_blank"><strong>Support Page</strong></a>.
						<br /><br />
						If you have any feedback or suggestions on improving the plugin, please let us know via our 
						<a href="http://a3webtools.com/contact/" target="_blank"><strong>Contact Form</strong></a>.
					</div>
				</div>
	
			</div><!-- #Sidebar -->

			<!-- Main Content -->
			<div id="post-body A3SCS-Main" class="has-sidebar">
				<div id="post-body-content" class="has-sidebar-content">
	
					<!-- General Options -->
					<div class="postbox" id="A3SCS-General-Options">
						<div class="handlediv" title="Click to Toggle"></div>
						<h3 class="hndle" style="cursor:default;">General Options</h3>
						<div class="inside">
	
							<table class="form-table" style="clear:none;">
								<tr valign="top">
									<th scope="row"><label for="Position"><strong>Sidebar Position</strong></label></th>
									<td>
										<select name="A3SCS[Position]" class="A3SCS-Select">
											<option value="Left"  <?php if ( isset( $Options['Position']) && $Options['Position'] === "Left"  ) echo 'selected="selected"'; ?> >Left</option>
											<option value="Right" <?php if ( isset( $Options['Position']) && $Options['Position'] === "Right" ) echo 'selected="selected"'; ?> >Right</option>
										</select>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row"><label for="Theme"><strong>Sidebar Theme</strong></label></th>
									<td>
										<select name="A3SCS[Theme]" class="A3SCS-Select">
											<option value="Dark"  <?php if ( isset( $Options['Theme']) && $Options['Theme'] === "Dark"  ) echo 'selected="selected"'; ?> >Dark</option>
											<option value="Light" <?php if ( isset( $Options['Theme']) && $Options['Theme'] === "Light" ) echo 'selected="selected"'; ?> >Light</option>
											<option value="Trans" <?php if ( isset( $Options['Theme']) && $Options['Theme'] === "Trans" ) echo 'selected="selected"'; ?> >Transparent</option>
											<option value="Color" <?php if ( isset( $Options['Theme']) && $Options['Theme'] === "Color" ) echo 'selected="selected"'; ?> >Color</option>
										</select>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row"><label for="Size"><strong>Sidebar Links Size</strong></label></th>
									<td>
										<select name="A3SCS[Size]" class="A3SCS-Select">
											<option value="Small" <?php if ( isset( $Options['Size']) && $Options['Size'] === "Small" ) echo 'selected="selected"'; ?> >Small</option>
											<option value="Large" <?php if ( isset( $Options['Size']) && $Options['Size'] === "Large" ) echo 'selected="selected"'; ?> >Large</option>
										</select>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row"><label for="Style"><strong>Sidebar Links Style</strong></label></th>
									<td>
										<select name="A3SCS[Style]" class="A3SCS-Select">
											<option value="Square" <?php if ( isset( $Options['Style']) && $Options['Style'] === "Square" ) echo 'selected="selected"'; ?> >Square</option>
											<option value="Circle" <?php if ( isset( $Options['Style']) && $Options['Style'] === "Circle" ) echo 'selected="selected"'; ?> >Circle</option>
										</select>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row"><label for="Label"><strong>Link Label Style</strong></label></th>
									<td>
										<select name="A3SCS[Label]" class="A3SCS-Select">
											<option value="Square" <?php if ( isset( $Options['Label']) && $Options['Label'] === "Square" ) echo 'selected="selected"'; ?> >Square</option>
											<option value="Curve"  <?php if ( isset( $Options['Label']) && $Options['Label'] === "Curve"  ) echo 'selected="selected"'; ?> >Curved</option>
											<option value="Round"  <?php if ( isset( $Options['Label']) && $Options['Label'] === "Round"  ) echo 'selected="selected"'; ?> >Rounded</option>
											<option value="Fancy"  <?php if ( isset( $Options['Label']) && $Options['Label'] === "Fancy"  ) echo 'selected="selected"'; ?> >Fancy</option>
										</select>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row"><label for="Shadow"><strong>Box Shadow</strong></label></th>
									<td>
										<select name="A3SCS[Shadow]" class="A3SCS-Select">
											<option value="None"  <?php if ( isset( $Options['Shadow']) && $Options['Shadow'] === "None"  ) echo 'selected="selected"'; ?> >None</option>
											<option value="Bar"   <?php if ( isset( $Options['Shadow']) && $Options['Shadow'] === "Bar"   ) echo 'selected="selected"'; ?> >Apply to Bar</option>
											<option value="Links" <?php if ( isset( $Options['Shadow']) && $Options['Shadow'] === "Links" ) echo 'selected="selected"'; ?> >Apply to Links</option>
										</select>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row"><label for="Corners"><strong>Corners</strong></label></th>
									<td>
										<select name="A3SCS[Corners]" class="A3SCS-Select">
											<option value="None"  <?php if ( isset( $Options['Corners']) && $Options['Corners'] === "None"  ) echo 'selected="selected"'; ?> >None</option>
											<option value="Bar"   <?php if ( isset( $Options['Corners']) && $Options['Corners'] === "Bar"   ) echo 'selected="selected"'; ?> >Apply to Bar</option>
											<option value="Links" <?php if ( isset( $Options['Corners']) && $Options['Corners'] === "Links" ) echo 'selected="selected"'; ?> >Apply to Links</option>
										</select>
									</td>
								</tr>
							</table>
	
						</div>
					</div>
					
					<!-- Links List -->
					<div class="postbox" id="A3SCS-Sidebar-Links">
						<div class="handlediv" title="Click to Toggle"></div>
						<h3 class="hndle" style="cursor:default;">Sidebar Links</h3>
						<div class="inside">
							
							<table id="Links-Header">
								<tr>
									<td id="Link-Header-Handle">&nbsp;</td>
									<td id="Link-Header-Name">Name</td>
									<td id="Link-Header-URL">URL</td>
									<td id="Link-Header-Icon">Icon</td>
									<td id="Link-Header-NewWindow">New Window</td>
									<td id="Link-Header-NoFollow">No Follow</td>
									<td id="Link-Header-Remove">&nbsp;</td>
								</tr>
							</table>
							
							<div id="Links-List">
								<ul id="Links-Sort"></ul>
							</div>
							
							<input type="hidden" id="A3SCS-Links-List" name="A3SCS[Links_List]" value="<?php echo $Options['Links_List']; ?>" />
							
							<div id="A3SCS-Add-Link" class="button-primary">Add Link</div>
							<div style="clear: both;"></div>
							
						</div>
					</div>
					
					<!-- Icon Map -->
					<div class="postbox closed" id="A3SCS-Icon-Map">
						<div class="handlediv" title="Click to Toggle"></div>
						<h3 class="hndle" style="cursor:default;">Icon Map - Click to Toggle</h3>
						<div class="inside">
						
							<p class="description A3SCS-Note txt-center">
								This is a visual guide to the icon choices available for your sidebar links. 
								They are in alphabetical order with variations on the same line.
							</p>

							<div id="Map">

								<div class="Row">
									<div class="Icon Aid"><span>Aid</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Android"><span>Android</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Apple"><span>Apple</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Blogger"><span>Blogger</span></div>
									<div class="Icon Blogger-2"><span>Blogger-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Bookmark"><span>Bookmark</span></div>
									<div class="Icon Bookmark-2"><span>Bookmark-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Bubbles"><span>Bubbles</span></div>
									<div class="Icon Bubbles-2"><span>Bubbles-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Bullhorn"><span>Bullhorn</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Cog"><span>Cog</span></div>
									<div class="Icon Cog-2"><span>Cog-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon CSS3"><span>CSS3</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Delicious"><span>Delicious</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Deviantart"><span>Deviantart</span></div>
									<div class="Icon Deviantart-2"><span>Deviantart-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Dribbble"><span>Dribbble</span></div>
									<div class="Icon Dribbble-2"><span>Dribbble-2</span></div>
									<div class="Icon Dribbble-3"><span>Dribbble-3</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Facebook"><span>Facebook</span></div>
									<div class="Icon Facebook-2"><span>Facebook-2</span></div>
									<div class="Icon Facebook-3"><span>Facebook-3</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Flattr"><span>Flattr</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Flickr"><span>Flickr</span></div>
									<div class="Icon Flickr-2"><span>Flickr-2</span></div>
									<div class="Icon Flickr-3"><span>Flickr-3</span></div>
									<div class="Icon Flickr-4"><span>Flickr-4</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Forrst"><span>Forrst</span></div>
									<div class="Icon Forrst-2"><span>Forrst-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Foursquare"><span>Foursquare</span></div>
									<div class="Icon Foursquare-2"><span>Foursquare-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Github"><span>Github</span></div>
									<div class="Icon Github-2"><span>Github-2</span></div>
									<div class="Icon Github-3"><span>Github-3</span></div>
									<div class="Icon Github-4"><span>Github-4</span></div>
									<div class="Icon Github-5"><span>Github-5</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Google"><span>Google</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Google-Drive"><span>Google-Drive</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon GPlus"><span>GPlus</span></div>
									<div class="Icon GPlus-2"><span>GPlus-2</span></div>
									<div class="Icon GPlus-3"><span>GPlus-3</span></div>
									<div class="Icon GPlus-4"><span>GPlus-4</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Heart"><span>Heart</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Home"><span>Home</span></div>
									<div class="Icon Home-2"><span>Home-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon HTML5"><span>HTML5</span></div>
									<div class="Icon HTML5-2"><span>HTML5-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Instagram"><span>Instagram</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Joomla"><span>Joomla</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Lanyrd"><span>Lanyrd</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon LastFM"><span>LastFM</span></div>
									<div class="Icon LastFM-2"><span>LastFM-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon LinkedIn"><span>LinkedIn</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Linux"><span>Linux</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Mail"><span>Mail</span></div>
									<div class="Icon Mail-2"><span>Mail-2</span></div>
									<div class="Icon Mail-3"><span>Mail-3</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Paypal"><span>Paypal</span></div>
									<div class="Icon Paypal-2"><span>Paypal-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Picassa"><span>Picassa</span></div>
									<div class="Icon Picassa-2"><span>Picassa-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Pinterest"><span>Pinterest</span></div>
									<div class="Icon Pinterest-2"><span>Pinterest-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Power"><span>Power</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Reddit"><span>Reddit</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon RSS"><span>RSS</span></div>
									<div class="Icon RSS-2"><span>RSS-2</span></div>
									<div class="Icon RSS-3"><span>RSS-3</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Share"><span>Share</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Skype"><span>Skype</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Soundcloud"><span>Soundcloud</span></div>
									<div class="Icon Soundcloud-2"><span>Soundcloud-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Stackoverflow"><span>Stackoverflow</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Star"><span>Star</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Steam"><span>Steam</span></div>
									<div class="Icon Steam-2"><span>Steam-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Stumbleupon"><span>Stumbleupon</span></div>
									<div class="Icon Stumbleupon-2"><span>Stumbleupon-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Thumbs-Up"><span>Thumbs-Up</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Tumblr"><span>Tumblr</span></div>
									<div class="Icon Tumblr-2"><span>Tumblr-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Twitter"><span>Twitter</span></div>
									<div class="Icon Twitter-2"><span>Twitter-2</span></div>
									<div class="Icon Twitter-3"><span>Twitter-3</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon User"><span>User</span></div>
									<div class="Icon User-2"><span>User-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Users"><span>Users</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Vimeo"><span>Vimeo</span></div>
									<div class="Icon Vimeo-2"><span>Vimeo-2</span></div>
									<div class="Icon Vimeo-3"><span>Vimeo-3</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Windows"><span>Windows</span></div>
									<div class="Icon Windows8"><span>Windows8</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Wordpress"><span>Wordpress</span></div>
									<div class="Icon Wordpress-2"><span>Wordpress-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Xing"><span>Xing</span></div>
									<div class="Icon Xing-2"><span>Xing-2</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Yahoo"><span>Yahoo</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Yelp"><span>Yelp</span></div>
								</div>
	
								<div class="Row">
									<div class="Icon Youtube"><span>Youtube</span></div>
									<div class="Icon Youtube-2"><span>Youtube-2</span></div>
								</div>
	
							</div>

						</div>
					</div>
					
					<input type="submit" class="button-primary" value="Save Changes" />
	
				</div>
			</div><!-- #Content -->

		</div><!-- #Metabox-Holder -->
	
	</form>
	
</div>

<!-- TEMPLATE: Item -->
<div id="Template-Item" class="Hidden">
	
	<li id="A3SCS-{{CODE}}" class="Link-Item">
		<div class="Link-Item-Handle">::</div>
		<div class="Link-Item-Name">
			<input type="text" id="A3SCS-{{CODE}}-Name" maxlength="200" />
		</div>
		<div class="Link-Item-URL">
			<input type="text" id="A3SCS-{{CODE}}-URL" />
		</div>
		<div class="Link-Item-Icon">
			<select id="A3SCS-{{CODE}}-Icon">
				<option value="Aid">Aid</option>
				<option value="Android">Android</option>
				<option value="Apple">Apple</option>
				<option value="Blogger">Blogger</option>
				<option value="Blogger-2">Blogger-2</option>
				<option value="Bookmark">Bookmark</option>
				<option value="Bookmark-2">Bookmark-2</option>
				<option value="Bubbles">Bubbles</option>
				<option value="Bubbles-2">Bubbles-2</option>
				<option value="Bullhorn">Bullhorn</option>
				<option value="Cog">Cog</option>
				<option value="Cog-2">Cog-2</option>
				<option value="CSS3">CSS3</option>
				<option value="Delicious">Delicious</option>
				<option value="Deviantart">Deviantart</option>
				<option value="Deviantart-2">Deviantart-2</option>
				<option value="Dribbble">Dribbble</option>
				<option value="Dribbble-2">Dribbble-2</option>
				<option value="Dribbble-3">Dribbble-3</option>
				<option value="Facebook">Facebook</option>
				<option value="Facebook-2">Facebook-2</option>
				<option value="Facebook-3">Facebook-3</option>
				<option value="Flattr">Flattr</option>
				<option value="Flickr">Flickr</option>
				<option value="Flickr-2">Flickr-2</option>
				<option value="Flickr-3">Flickr-3</option>
				<option value="Flickr-4">Flickr-4</option>
				<option value="Forrst">Forrst</option>
				<option value="Forrst-2">Forrst-2</option>
				<option value="Foursquare">Foursquare</option>
				<option value="Foursquare-2">Foursquare-2</option>
				<option value="Github">Github</option>
				<option value="Github-2">Github-2</option>
				<option value="Github-3">Github-3</option>
				<option value="Github-4">Github-4</option>
				<option value="Github-5">Github-5</option>
				<option value="Google">Google</option>
				<option value="Google-Drive">Google-Drive</option>
				<option value="GPlus">GPlus</option>
				<option value="GPlus-2">GPlus-2</option>
				<option value="GPlus-3">GPlus-3</option>
				<option value="GPlus-4">GPlus-4</option>
				<option value="Heart">Heart</option>
				<option value="Home">Home</option>
				<option value="Home-2">Home-2</option>
				<option value="HTML5">HTML5</option>
				<option value="HTML5-2">HTML5-2</option>
				<option value="Instagram">Instagram</option>
				<option value="Joomla">Joomla</option>
				<option value="Lanyrd">Lanyrd</option>
				<option value="LastFM">LastFM</option>
				<option value="LastFM-2">LastFM-2</option>
				<option value="LinkedIn">LinkedIn</option>
				<option value="Linux">Linux</option>
				<option value="Mail">Mail</option>
				<option value="Mail-2">Mail-2</option>
				<option value="Mail-3">Mail-3</option>
				<option value="Paypal">Paypal</option>
				<option value="Paypal-2">Paypal-2</option>
				<option value="Picassa">Picassa</option>
				<option value="Picassa-2">Picassa-2</option>
				<option value="Pinterest">Pinterest</option>
				<option value="Pinterest-2">Pinterest-2</option>
				<option value="Power">Power</option>
				<option value="Reddit">Reddit</option>
				<option value="RSS">RSS</option>
				<option value="RSS-2">RSS-2</option>
				<option value="RSS-3">RSS-3</option>
				<option value="Share">Share</option>
				<option value="Skype">Skype</option>
				<option value="Soundcloud">Soundcloud</option>
				<option value="Soundcloud-2">Soundcloud-2</option>
				<option value="Stackoverflow">Stackoverflow</option>
				<option value="Star">Star</option>
				<option value="Steam">Steam</option>
				<option value="Steam-2">Steam-2</option>
				<option value="Stumbleupon">Stumbleupon</option>
				<option value="Stumbleupon-2">Stumbleupon-2</option>
				<option value="Thumbs-Up">Thumbs-Up</option>
				<option value="Tumblr">Tumblr</option>
				<option value="Tumblr-2">Tumblr-2</option>
				<option value="Twitter">Twitter</option>
				<option value="Twitter-2">Twitter-2</option>
				<option value="Twitter-3">Twitter-3</option>
				<option value="User">User</option>
				<option value="User-2">User-2</option>
				<option value="Users">Users</option>
				<option value="Vimeo">Vimeo</option>
				<option value="Vimeo-2">Vimeo-2</option>
				<option value="Vimeo-3">Vimeo-3</option>
				<option value="Windows">Windows</option>
				<option value="Windows8">Windows8</option>
				<option value="Wordpress">Wordpress</option>
				<option value="Wordpress-2">Wordpress-2</option>
				<option value="Xing">Xing</option>
				<option value="Xing-2">Xing-2</option>
				<option value="Yahoo">Yahoo</option>
				<option value="Yelp">Yelp</option>
				<option value="Youtube">Youtube</option>
				<option value="Youtube-2">Youtube-2</option>
			</select>
		</div>
		<div class="Link-Item-Option">
			<input type="checkbox" id="A3SCS-{{CODE}}-NewWindow" />
		</div>
		<div class="Link-Item-Option">
			<input type="checkbox" id="A3SCS-{{CODE}}-NoFollow" />
		</div>
		<div class="Link-Item-Remove">
			<div class="A3SCS-Link-Remove button-secondary" onclick="ADMIN.Item_Remove('{{CODE}}');" title="Remove Item">X</div>
		</div>
		<div class="Link-Item-ID">{{CODE}}</div>
	</li>
	
</div>