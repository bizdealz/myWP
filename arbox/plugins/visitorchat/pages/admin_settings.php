<?php
/**
 * 
 * ClientEngage VisitorChat for WordPress (http://www.clientengage.com)
 * Copyright 2013, ClientEngage (http://www.clientengage.com)
 *
 * You must have purchased a valid license from CodeCanyon in order to have 
 * the permission to use this file.
 * 
 * You may only use this file according to the respective licensing terms 
 * you agreed to when purchasing this item on CodeCanyon.
 * 
 * All PHP code utilising WordPress features is licensed as GPL version 2 or 
 * later. External libraries placed in the "vendors"-directory are subject to
 * their respective licensing terms (take note of their licenses).
 *  
 * However, other ClientEngage assets such as JavaScript code, 
 * CSS styles and images are not licensed as GPL version 2 or later and you are 
 * required to abide by the CodeCanyon Regular License. 
 * 
 *
 * @author          ClientEngage <contact@clientengage.com>
 * @copyright       Copyright 2013, ClientEngage (http://www.clientengage.com)
 * @link            http://www.clientengage.com ClientEngage
 * @since           ClientEngage VisitorChat for WordPress v 1.0
 * @license         GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * 
 */
defined('ABSPATH') or die('No direct access.');

$langFiles = array();
$handle = opendir(CEVC::i()->dir . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR);
while ($langFile = readdir($handle))
{
    if (!in_array($langFile, array('.', '..')) && (strpos($langFile, '.po') !== false || strpos($langFile, '.mo') !== false))
    {
        $expl = explode('.', $langFile);
        if (!in_array($expl[0], array('visitorchat-en_US', 'visitorchat-de_DE')))
        {
            $langFiles[] = str_Replace('visitorchat-', '', $expl[0]);
        }
    }
}
closedir($handle);

$languageString = implode(', ', array_unique($langFiles));
?>
<div class="wrap">
    <?php echo CEVC::i()->Helper->renderFlash(); ?>

    <div class="icon32 icon-settings"><br /></div>
    <h2><?php echo __('VisitorChat Settings', 'visitorchat'); ?></h2>

    <table>
        <tbody>
            <tr>
                <td>
                    <?php echo '<img src="' . CEVC::i()->url . '/img/admin/logo-clientengage-large.png" alt="ClientEngage VisitorChat Logo" class="cevcNEW_logo"> '; ?>
                </td>
                <td style="width: 275px; padding-left: 20px">
                    <p><?php echo __('Please use the settings below to change how VisitorChat is integrated with your WordPress Website.', 'visitorchat'); ?></p>
                </td>
            </tr>
        </tbody>
    </table>

    <form method="post" action="<?php echo CEVC::i()->isDemo ? '#' : 'options.php'; ?>">
        <?php settings_fields('cevc_options'); ?>
        <?php $options = get_option('cevc_options'); ?>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder ">
                <div class="stuffbox">
                    <h3><?php echo __('General VisitorChat Settings & System Information', 'visitorchat'); ?></h3>
                    <div class="inside">

                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php echo __('VisitorChat Version', 'visitorchat'); ?></th>
                                <td>
                                    <span><?php echo CEVC::$version; ?></span>
                                </td>
                            </tr>         
                            <tr valign="top">
                                <th scope="row"><?php echo __('VisitorChat Temporary Directory', 'visitorchat'); ?></th>
                                <td>
                                    <span><?php
                                        $tmpDir = CEVC::i()->dir . 'public_tmp';
                                        if (is_writable($tmpDir))
                                        {
                                            echo '<span style="color: green;">' . CEVC_String::insert(__('Writable: ":dir"', 'visitorchat'), array('dir' => $tmpDir)) . '</span>';
                                        }
                                        else
                                        {
                                            echo '<span style="color: red;">' . CEVC_String::insert(__('Not writable: ":dir"', 'visitorchat'), array('dir' => $tmpDir)) . '</span>';
                                            echo '<br />' . __('It is highly recommended that you give this directory write access to improve the performance of VisitorChat.', 'visitorchat');
                                        }
                                        ?></span>
                                </td>
                            </tr>         
                            <tr valign="top">
                                <th scope="row"><label for="input_apikey"><?php echo __('API-Key', 'visitorchat'); ?></label></th>
                                <td>
                                    <input id="input_apikey" name="cevc_options[api_key]" value="<?php echo isset($options['api_key']) ? $options['api_key'] : ''; ?>" style="width: 300px" />
                                </td>
                            </tr>         
                            <tr valign="top">
                                <th scope="row"><?php echo __('API-Endpoint', 'visitorchat'); ?></th>
                                <td>
                                    <span><?php echo admin_url('admin-ajax.php'); ?></span>
                                </td>
                            </tr>         
                            <tr valign="top">
                                <th scope="row"><?php echo __('WordPress System Locale', 'visitorchat'); ?></th>
                                <td>
                                    <?php echo '<strong>' . get_locale() . '</strong><br /><span style="color:#666;">' . CEVC_String::insert(__('You need to create your language files with <a href="http://www.poedit.net" target="_blank">PoEdit</a> at ":file_location". Please also use PoEdit to generate the respective *.mo-file.', 'visitorchat'), array('file_location' => CEVC::i()->dir . 'lang' . DIRECTORY_SEPARATOR . 'visitorchat-' . get_locale() . '.po')); ?></span>
                                </td>
                            </tr>  
                            <?php if (!empty($langFiles)): ?>
                                <tr valign="top">
                                    <th scope="row"><?php echo __('Installed Languages', 'visitorchat'); ?></th>
                                    <td>
                                        <span><?php echo CEVC_String::insert(__('In addition to the default languages English and German, you have additional language files. Please consider sharing these with me by emailing the .po and .mo files to :emailaddress and confirming that you are happy for me to include these in this item free-of-charge. In return (provided your language file is included in the item), you will receive a backlink from this item\'s item-page on CodeCanyon. You have the following additional languages:', 'visitorchat'), array('emailaddress' => '<a href="contact@clientengage.com">contact@clientengage.com</a>')); ?> <strong><?php echo $languageString; ?></strong></span>
                                    </td>
                                </tr>   
                            <?php endif; ?>

                            <?php if (false): ?>
                                <?php // TODO: include new functionality once the sender can be selected ?>
                                <tr valign="top">
                                    <th scope="row"><label for="input_first_message"><?php echo __('First Message', 'visitorchat'); ?></label></th>
                                    <td>
                                        <textarea id="input_first_message" name="cevc_options[first_message]" style="width: 300px; min-height: 100px;"><?php echo isset($options['first_message']) ? $options['first_message'] : ''; ?></textarea><br />
                                        <span style="color:#666;"><?php echo __('This message will automatically be sent to visitors one they started a new chat-session. You can use the placeholder {Username} to insert their name in the message. If you do not wish to automatically create a message, simply leave the field empty.', 'visitorchat'); ?></span>
                                    </td>
                                </tr>   
                            <?php endif; ?>

                            <tr valign="top">
                                <th scope="row"><label for="input_recipient"><?php echo __('Enquiry Recipient', 'visitorchat'); ?></label></th>
                                <td>
                                    <input id="input_recipient" name="cevc_options[enquiry_recipient]" value="<?php echo isset($options['enquiry_recipient']) ? $options['enquiry_recipient'] : ''; ?>" style="width: 300px" /><br />
                                    <span style="color:#666;"><?php echo __('Enquiry-notifications will be delivered to the above email address. VisitorChat uses the WordPress wp_mail() function. Therefore, you can use the WordPress plugin "WP SMTP" if you wish enquiry-notifications to be sent via SMTP.', 'visitorchat'); ?></span>
                                </td>
                            </tr>         
                            <tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
                            <tr valign="top" style="border-top:#ddd 1px solid;">
                                <th scope="row"><?php echo __('Database Options', 'visitorchat'); ?></th>
                                <td>
                                    <label><input name="cevc_options[visitorchat_reset_default_options]" type="checkbox" value="1" <?php isset($options['visitorchat_reset_default_options']) ? checked('1', $options['visitorchat_reset_default_options']) : ''; ?> /> <?php echo __('Reset VisitorChat-settings to factory-defaults upon plugin re-activation.', 'visitorchat'); ?></label>
                                    <br /><span style="color:#666;"><?php echo __('Please note that this will delete all of your VisitorChat settings and place the chat back into its default state. Your discussions and enquiries will remain.', 'visitorchat'); ?></span>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>


        <div id="poststuff">
            <div id="post-body" class="metabox-holder ">
                <div class="stuffbox">
                    <h3><?php echo __('VisitorChat Integration Settings', 'visitorchat'); ?></h3>
                    <div class="inside">

                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php echo __('VisitorChat Integration Mode', 'visitorchat'); ?></th>
                                <td>
                                    <label><input name="cevc_options[visitorchat_mode]" type="radio" value="optin" <?php checked('optin', $options['visitorchat_mode']); ?> /> <?php echo __('Opt-In', 'visitorchat'); ?> <span class="cevcNEW_label_description"><?php echo __('The VisitorChat will only be included if you activate it in the respective post\'s or page\'s meta-settings.', 'visitorchat'); ?></span></label><br />

                                    <label><input name="cevc_options[visitorchat_mode]" type="radio" value="optout" <?php checked('optout', $options['visitorchat_mode']); ?> /> <?php echo __('Opt-Out', 'visitorchat'); ?> <span class="cevcNEW_label_description"> <?php echo __('Opt-In', 'visitorchat'); ?> <span class="cevcNEW_label_description"><?php echo __('The VisitorChat will always be included, unless you deactivate it in the respective post\'s or page\'s meta-settings.', 'visitorchat'); ?></span></label><br />
                                    <span style="color:#666;">
                                        <?php echo __('Please select your preferred integration-mode above - this will determine how VisitorChat will be integrated in individual pages and posts.', 'visitorchat'); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php echo __('Overrides for Special Pages', 'visitorchat'); ?></th>
                                <td>
                                    <!--  Front -->
                                    <label><input name="cevc_options[show_front]" type="checkbox" value="1" <?php isset($options['show_front']) ? checked('1', $options['show_front']) : ''; ?> /> <?php echo __('Show on Frontpage', 'visitorchat'); ?> <em><?php echo __('(When the main blog page is being displayed.)', 'visitorchat'); ?></em></label><br />
                                    <!--  Home -->
                                    <label><input name="cevc_options[show_home]" type="checkbox" value="1" <?php isset($options['show_home']) ? checked('1', $options['show_home']) : ''; ?> /> <?php echo __('Show on Homepage', 'visitorchat'); ?> <em><?php echo __('(When the front of the site is displayed, whether it is posts or a Page. )', 'visitorchat'); ?></em></label><br />
                                    <!--  Comments Popup -->
                                    <label><input name="cevc_options[show_comments_popup]" type="checkbox" value="1" <?php isset($options['show_comments_popup']) ? checked('1', $options['show_comments_popup']) : ''; ?> /> <?php echo __('Show in Comments Popup', 'visitorchat'); ?> <em><?php echo __('(When in Comments Popup window.)', 'visitorchat'); ?></em></label><br />
                                    <!--  Category -->
                                    <label><input name="cevc_options[show_category]" type="checkbox" value="1" <?php isset($options['show_category']) ? checked('1', $options['show_category']) : ''; ?> /> <?php echo __('Show in Category Page', 'visitorchat'); ?> <em><?php echo __('(When any Category archive page is being displayed.)', 'visitorchat'); ?></em></label><br />
                                    <!--  Tag -->
                                    <label><input name="cevc_options[show_tag]" type="checkbox" value="1" <?php isset($options['show_tag']) ? checked('1', $options['show_tag']) : ''; ?> /> <?php echo __('Show in Tag Page', 'visitorchat'); ?> <em><?php echo __('(When any Tag archive page is being displayed.)', 'visitorchat'); ?></em></label><br />
                                    <!--  Tax -->
                                    <label><input name="cevc_options[show_tax]" type="checkbox" value="1" <?php isset($options['show_tax']) ? checked('1', $options['show_tax']) : ''; ?> /> <?php echo __('Show in Taxonomy Page', 'visitorchat'); ?> <em><?php echo __('(When any Taxonomy archive page is being displayed.)', 'visitorchat'); ?></em></label><br />
                                    <!--  Author -->
                                    <label><input name="cevc_options[show_author]" type="checkbox" value="1" <?php isset($options['show_author']) ? checked('1', $options['show_author']) : ''; ?> /> <?php echo __('Show in Author Page', 'visitorchat'); ?> <em><?php echo __('(When any Author page is being displayed.)', 'visitorchat'); ?></em></label><br />
                                    <!--  Date -->
                                    <label><input name="cevc_options[show_date]" type="checkbox" value="1" <?php isset($options['show_date']) ? checked('1', $options['show_date']) : ''; ?> /> <?php echo __('Show in Date Page', 'visitorchat'); ?> <em><?php echo __('(When any date-based archive page is being displayed (i.e. a monthly, yearly, daily or time-based archive).)', 'visitorchat'); ?></em></label><br />
                                    <!--  Search -->
                                    <label><input name="cevc_options[show_search]" type="checkbox" value="1" <?php isset($options['show_search']) ? checked('1', $options['show_search']) : ''; ?> /> <?php echo __('Show in Search Page', 'visitorchat'); ?> <em><?php echo __('(When a search result page archive is being displayed.)', 'visitorchat'); ?></em></label><br />
                                    <!--  404 -->
                                    <label><input name="cevc_options[show_404]" type="checkbox" value="1" <?php isset($options['show_404']) ? checked('1', $options['show_404']) : ''; ?> /> <?php echo __('Show in 404 Page', 'visitorchat'); ?> <em><?php echo __('(When a page displays after an "HTTP 404: Not Found" error occurs.)', 'visitorchat'); ?></em></label><br />
                                    <!--  Attachment -->
                                    <label><input name="cevc_options[show_attachment]" type="checkbox" value="1" <?php isset($options['show_attachment']) ? checked('1', $options['show_attachment']) : ''; ?> /> <?php echo __('Show in Attachment Page', 'visitorchat'); ?> <em><?php echo __('(When an attachment document to a post or Page is being displayed.)', 'visitorchat'); ?></em></label><br />
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>



        <div id="poststuff">
            <div id="post-body" class="metabox-holder ">
                <div class="stuffbox">
                    <h3><?php echo __('VisitorChat Layout Settings', 'visitorchat'); ?></h3>
                    <div class="inside">

                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php echo __('Layout Options', 'visitorchat'); ?></th>
                                <td>
                                    <p><?php echo CEVC_String::insert(__('Please use the settings below to individualise the look and feel of VisitorChat. If you are an advaned user, you can directly manipulate the CSS-stylesheet. However, please backup your custom changes before updating to a newer version of VisitorChat. The CSS-stylesheet can be found here: ":stylesheetpath".', 'visitorchat'), array('stylesheetpath' => CEVC::i()->dir . 'css' . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'visitorchat_style.css')); ?></p>
                                </td>
                            </tr>            
                            <tr valign="top">
                                <th scope="row"><label for="input_loadstyle"><?php echo __('Load Default Style', 'visitorchat'); ?></label></th>
                                <td>
                                    <select id="input_loadstyle" class="theme_selector">
                                        <option value="default"><?php echo __('Please select', 'visitorchat'); ?></option>
                                        <option value="red"><?php echo __('Red', 'visitorchat'); ?></option>
                                        <option value="blue"><?php echo __('Blue', 'visitorchat'); ?></option>
                                        <option value="green"><?php echo __('Green', 'visitorchat'); ?></option>
                                        <option value="black"><?php echo __('Black', 'visitorchat'); ?></option>
                                    </select>
                                    <script type="text/javascript">
                                        (function($) {
                                            $(document).ready(function() {
                                                $(".theme_selector").on("change", function(e) {
                                                    var selected = $(this).val();

                                                    if (selected !== "default")
                                                    {
                                                        var theme;
                                                        var themes = [
                                                            {name: "red", colour: "#af2c17", image: "vc_background_red.png"},
                                                            {name: "blue", colour: "#036", image: "vc_background_blue.png"},
                                                            {name: "green", colour: "#8bb82b", image: "vc_background_green.png"},
                                                            {name: "black", colour: "#151515", image: "vc_background_black.png"},
                                                        ];
                                                        for (var i = 0; i < themes.length; i++)
                                                        {
                                                            if (themes[i].name === selected)
                                                            {
                                                                theme = themes[i];
                                                            }
                                                        }

                                                        $(".theme_primarycolour").val(theme.colour);
                                                        $(".theme_backgroundimage").val(theme.image);

                                                        $(".theme_colour_preview").css("background-color", theme.colour);
                                                    }

                                                    $(this).val("selected");

                                                });
                                            });
                                        })(jQuery);
                                    </script>
                                </td>
                            </tr>    

                            <tr valign="top">
                                <th scope="row"><label for="select_gravatar"><?php echo __('Default Gravatar', 'visitorchat'); ?></label></th>
                                <td>
                                    <select id="select_gravatar" class="gravatar_selector">
                                        <option value="identicon"><?php echo __('Identicon', 'visitorchat'); ?></option>
                                        <option value="mm"><?php echo __('Person', 'visitorchat'); ?></option>
                                        <option value="custom"><?php echo __('Custom URL', 'visitorchat'); ?></option>
                                    </select>
                                    <input type="hidden" name="cevc_options[gravatar_default]" class="input_default_gravatar" value="<?php echo isset($options['gravatar_default']) ? $options['gravatar_default'] : ''; ?>" />

                                    <div class="input_gravatar_url" style="display:none;">
                                        <label for="custom_gravatar_url"><?php echo __('Custom Gravatar Image URL', 'visitorchat'); ?></label>
                                        <input id="custom_gravatar_url" name="gravatar_url" />
                                    </div>

                                    <script type="text/javascript">
                                        (function($) {
                                            $(document).ready(function() {

                                                if ($(".input_default_gravatar").val() === "mm" || $(".input_default_gravatar").val() === "identicon")
                                                {
                                                    $(".gravatar_selector").val($(".input_default_gravatar").val());
                                                }
                                                else
                                                {
                                                    $(".gravatar_selector").val("custom");
                                                    $(".input_gravatar_url").fadeIn();
                                                    $(".input_gravatar_url input").val($(".input_default_gravatar").val());
                                                }

                                                $(".gravatar_selector").on("change", function(e) {
                                                    var selected = $(this).val();

                                                    if (selected !== "custom")
                                                    {
                                                        $(".input_default_gravatar").val(selected);
                                                        $(".input_gravatar_url").fadeOut();
                                                    }
                                                    else
                                                    {
                                                        $(".input_gravatar_url").fadeIn();
                                                    }

                                                });

                                                $(".input_gravatar_url input").on("keyup", function() {
                                                    $(".input_default_gravatar").val($(this).val());
                                                });
                                            });
                                        })(jQuery);
                                    </script>
                                </td>
                            </tr>    

                            <tr valign="top">
                                <th scope="row"><?php echo __('Position', 'visitorchat'); ?></th>
                                <td>
                                    <label><input name="cevc_options[layout_position]" type="radio" value="right" <?php checked('right', $options['layout_position']); ?> /> <?php echo __('Right', 'visitorchat'); ?></label>&nbsp;&nbsp;&nbsp;
                                    <label><input name="cevc_options[layout_position]" type="radio" value="left" <?php checked('left', $options['layout_position']); ?> /> <?php echo __('Left', 'visitorchat'); ?></label>
                                </td>
                            </tr>    
                            <tr valign="top">
                                <th scope="row"><label for="input_primarycolour"><?php echo __('Primary Colour', 'visitorchat'); ?></label></th>
                                <td>
                                    <div class="theme_colour_preview" style="display: inline-block; width: 20px; background-color: <?php echo isset($options['layout_primarycolour']) ? $options['layout_primarycolour'] : ''; ?>">&nbsp;</div>
                                    <input id="input_primarycolour" class="theme_primarycolour" name="cevc_options[layout_primarycolour]" value="<?php echo isset($options['layout_primarycolour']) ? $options['layout_primarycolour'] : ''; ?>" style="width: 300px" />
                                </td>
                            </tr>   
                            <tr valign="top">
                                <th scope="row"><label for="input_backgroundimage"><?php echo __('Background Image', 'visitorchat'); ?></label></th>
                                <td>
                                    <input id="input_backgroundimage" class="theme_backgroundimage" name="cevc_options[layout_backgroundimage]" value="<?php echo isset($options['layout_backgroundimage']) ? $options['layout_backgroundimage'] : ''; ?>" style="width: 300px" /><br />
                                    <span style="color:#666;"><?php echo __('This image has to be available in the following directory:', 'visitorchat') . ' ' . CEVC::i()->url . '/img/chat/styles/backgrounds/'; ?></span>
                                </td>
                            </tr>   
                            <tr valign="top">
                                <th scope="row"><?php echo __('Additional Settings', 'visitorchat'); ?></th>
                                <td>
                                    <label><input name="cevc_options[layout_animatehover]" type="checkbox" value="1" <?php isset($options['layout_animatehover']) ? checked('1', $options['layout_animatehover']) : ''; ?> /> <?php echo __('Animate Hover', 'visitorchat'); ?> <em><?php echo __('(unless opened or hovered, the chat will appear semi-transparent)', 'visitorchat'); ?></em></label><br />
                                    <label><input name="cevc_options[layout_hidemobile]" type="checkbox" value="1" <?php isset($options['layout_hidemobile']) ? checked('1', $options['layout_hidemobile']) : ''; ?> /> <?php echo __('Hide for Mobile Devices', 'visitorchat'); ?> <em><?php echo __('(the chat will not appear for mobile devices accessing your site)', 'visitorchat'); ?></em></label>
                                </td>
                            </tr>                   
                        </table>



                    </div>
                </div>
            </div>
        </div>


        <p class="submit">
            <input type="submit" class="button-primary" value="<?php echo _e(__('Save Changes', 'visitorchat')); ?>" />
        </p>
    </form>

    <p style="margin-top:15px; font-style: italic;font-weight: bold;color: #26779a;">ClientEngage develops a range of solutions that help you get closer to your customers. Visit <a href="http://www.clientengage.com" target="_blank" style="color:#72a1c6;">www.clientengage.com</a> to find out more.</p>

</div>