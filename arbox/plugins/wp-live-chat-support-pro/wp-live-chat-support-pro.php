<?php
/*
  Plugin Name: WP Live Chat Support Pro
  Plugin URI: http://wp-livechat.com
  Description: The Pro version of the easiest to use website live chat plugin. Let your visitors chat with you and increase sales conversion rates with WP Live Chat. No third party connection required!
  Version: 4.4.10
  Author: WP Live Chat
  Author URI: http://wp-livechat.com
 */


/* 4.4.10 2015-03-23 Low Priority
 * Bug Fix: Bug in the banned user functionality
 * Enhancement: Stying improvement on the Live Chat dashboard
 * Enhancement: Strings are handled better for localization plugins (Pro)
 * Updated Translation Files:
 *  Spanish (Thank you Ana Ayelen Martinez)
 * 
 * 4.4.9 2015-03-17 - Low Priority
 * Bug Fix: Warnings for animations showing erroneously 
 * Bug Fix: Including and Excluding pages intermittent when using more than one page
 * 
 * 4.4.8 2015-03-16 - Low Priority
 * Bug Fix: Mobile Detect class caused Fatal error on some websites
 * Bug Fix: PHP Errors when editing user page
 * Bug Fix: Including and Excluding the chat window caused issues on some websites
 * Bug Fix: Online/Offline Toggle Switch did not work in some browsers (Pro)
 * New Feature: You can now Ban visitors from chatting with you based on IP Address (Pro)
 * New Feature: You can now choose if you want users to make themselves an agent (Pro) 
 * Bug Fix: Chat window would not hide when selecting the option to not accept offline messages (Pro) 
 * Enhancement: Support page added 
 * Updated Translations:
 *  French (Thank you Marcello Cavallucci)
 * New Translation added:
 *  Norwegian (Thank you Robert Nilsen)
 *  Hungarian (Thank you GInception)
 *  Indonesian (Thank you Andrie Willyanta)
 * 
 * 4.4.7 2015-02-18 - Low Priority
 * New Feature: You can now send an offline message to more than one email address (Pro)
 * New Feature: You can now specify if you want to be online or not (Pro)
 * New Feature: You can now choose to record your visitors IP address or not
 * 
 * 
 * 4.4.6 2015-02-13 - Medium Priority
 * Bug Fix: Styling Issues Related to Animations/Transitions
 * 
 * 4.4.5 2015-02-12 - Low Priority
 * New Feature: You can now apply an animation to the chat window on page load
 * New Feature: You can now choose from 5 colour schemes for the chat window
 * Enhancement: Aesthetic Improvement to list of agents (Pro)
 * Code Improvement: PHP error fixed
 * Updated Translations:
 *  German (Thank you Dennis Klinger)  
 * 
 * 4.4.4 2015-01-29 - Low Priority
 * New feature: Live Chat dashboard has a new layout and design
 * Code Improvement: jQuery Cookie updated to the latest version
 * Code Improvement: More Live Chat strings are now translatable 
 * New Live Chat Translation added:
 *  Spanish (Thank you Ana Ayelén Martínez)
 * 
 * 4.4.3 2015-01-21 - Low Priority
 * New Basic Feature: You can now view any live chats you have missed
 * New Pro Feature: You can record offline messages when live chat is not online
 * Code Improvements: Labels added to buttons
 * Code Improvements: PHP Errors fixed
 * 
 * Updated Translations:
 *  Italian (Thank You Angelo Giammarresi)
 * 
 * 4.4.2 2014-12-17 - Low Priority
 * New feature: The chat window can be hidden when offline (Pro only)
 * New feature: Desktop notifications added
 * Bug fix: Email address gave false validation error when not required.
 * 
 * Translations Added:
 *  Dutch (Thank you Elsy Aelvoet)
 * 
 * 
 * 4.4.1 2014-12-10 - Low Priority
 * New feature: You can now toggle between displaying or hiding the users name in your Live Chat messages
 * New feature: You can now choose to display the Live Chat window to all or only registered users
 * New feature: A user image will now display in the Live Chat message
 * Code Improvement: jQuery UI CSS is loaded from a local source as using an external source caused issues on some sites using SSL
 * Bug Fix: Only Admin users can make users Live Chat agents
 * 
 * New WP Live Chat Support Translations added:
 * Mongolian (Thank you Monica Batuskh)
 * Romanian (Thank you Sergiu Balaes)
 * Czech (Thank you Pavel Cvejn)
 * Danish (Thank you Mikkel Jeppesen Juhl)
 * 
 * WP Live Chat Support Translations that have been updated:
 * German (Thank you Dennis Klinger)
 * 
 * 4.4.0 2014-11-20
 * Chat UI Improvements
 * Small bug fixes
 * 
 * 4.3.3
 * New Feature: You can now include or exclude the chat window on certain pages
 * Code Improvements: (Checks for DONOTCACHE)
 * 
 * 4.3.2
 * It is not required to enter your name and email address anymore. 
 * Logged In users wont have to enter their details in.
 * Turn the chat on/off on a mobile device.
 * 
 * 4.3.1
 * Bug fix: sound was not played when user received a message from the admin
 *   
 * 4.3.0
 * Added "Quick Response" functionality
 * Small bug fixes
 * Internationalization update
 * New WP Live Chat Support Translation added:
 *  * Swedish (Thank You Tobias Sernhede)
 *  * French (Thank You Marcello Cavallucci)
 * 
 * 4.2.1
 * Code improvements (Errors fixed in IE)
 * Chat performance improvements
 * 
 * 4.2.0
 * High priority update
 * Significant performance improvements
 * Small bug fixes
 * 
 * 4.1.3
 * Code improvements (PHP warnings)
 * 
 * 4.1.2
 * Performance improvements:
 *  - Significant performence improvements
 *  - Visitor list is only updated once every 3 seconds
 *  - Introduced new constants to control the delay between long polling requests and requests within the long poll call
 *  - Chat window will now only show in one window (if user has multiple tabs open on your site)
 * Various bug fixes
 *  - Minor bugs fixed
 *  - Image bug (front end) fixed
 * 
 * 4.1.1
 * Major bug fix - in some instances admin couldnt chat (answered by other agent message)
 * Backend UI Improvements
 * 
 * 4.1.0
 * Vulnerability fix (JS injections). Thank you Patrik @ www.it-securityguard.com
 * New feature: You can now show the chat box on the left or right
 * Fixed bug in menu where mutli agent user could not access it
 * Fixed 403 bug when saving settings
 * Fixed Ajax Time out error (Lowered From 90 to 28)
 * Multi Agents is now standard in pro
 * Fixed PHP Notices (settings page)
 * Added option to auto pop up chat window
 * Added Italian language files. Thanks to Davide PantÃ¨
 * 
 * 4.0.3
 * Initiate chat bug fix
 * 
 * 4.0.2
 * Backwards compatibility checks
 * 
 * 4.0.1
 * Small bug fix
 * 
 * 4.0.0
 * Overhauled all ajax requests to server to to be less resource intensive
 * Added more localization to strings that weren't been localized
 * Added Feedback Menu
 * Added Welcome Page
 * Added Support For Multiple Agents (Add On)
 * Fixed "Chat Pending" Forever showing to user - Chat now displays admin is away message
 * Completed chats - Vistors are returned to browsing
 * Fixed Many Bugs
 * 
 * 
 * 3.08
 * 
 * Added more strings to po file
 * Fixed select boxes in settings to show selected option
 * Fixed table headers changing back to english on alternative launguage installs
 * 
 * 3.07
 * 
 * Fixed bug showing all errors
 * Fixed bug of function not found - wplc_get_home_path
 * 
 * 3.06
 * 
 * Fixed no validation on offline email send bug
 * Fixed Languages Bug
 * Fixed 500 error from ajax when wp-content had different name
 * 
 * 3.05
 * 
 * Fixed input height issues
 * Fixed input overlapping bottom of chat
 * Fixed Endless Connecting
 * Testing chat is now easier (No Crossing over to other browser) 
 * 
 * 3.04
 * 
 * Fixed Bug SHowing undefined variables
 * Fixed minimmize bug that starts ringing again
 * 
 * 3.03
 * 
 * Fixed Placeholder text not displaying in IE 
 * 
 * 3.02
 * 
 * Email bug fix
 * 
 * 3.01
 * 
 * Fixed Close & Minimixe Button Styling Issues
 * Fixed bug where text was not hidden when offline message was sent
 * Fixed bug that would hide text behind image if text was 2 lines
 * Fixed bug that continuesly scrolled chat down
 * Fixed styling of inputs on some themes (overlaps chat box)
 * Set CSS color for inputs
 * Fixed bug that wasn't alerting admin in wp-admin to chat if Alert via email was set (Pro)
 * Fixed Double opening bug if chat was moved
 * Fixed Bug to inform admin and user either or has ended the chat
 * Fixed other small bugs
 * 
 * 3.0
 * 
 * Updated Styling of plugin
 * Chat window is now draggable
 * fixed bug generating characters on activation
 * 
 * 2.9
 * 
 * Fix Update Plugin Bug
 * 
 * 2.8
 * 
 * Added Support For WP-Mail Now
 * Fixed bug not saving Alignment 
 * Fixed bug not saving if Enabled or not
 * 
 * 2.7
 * 
 * Chat Delay Value Shows Up Now
 * Added remove image button
 * Fixed image not showing on fresh install
 * Fixed image been lost on settings save
 * 
 * 2.6
 * 
 * Added support to email for proper smtp (phpMailer)
 * changed .live to .on
 * Fixed cookie bugs
 * Add delete history option
 * Set minimize bar to correct color
 * fixed bug where refresh page would make chat dissapear
 * Chat Initiation Bug fix
 * Set Datbase charset from Latin to UTF-8
 * Added error reporting
 * 
 * 2.5
 * Fixed a bug that was causing "page not found error"
 * Better UI for the settings page
 * Added the ability to end live chats 
 * 
 * 2.4
 * Fixed offline message email bug
 * 
 * 2.3
 * Major performance improvements
 * Plugin now uses the new media manager to upload your profile pic and logo
 * A sound now plays every time a visitor replies to one of your chat sessions
 *
 *
 * 2.2
 * You now have full control of the fill and font color of your live chat box
 * Enabled the ability to turn live chat on or off
 * Better notification of incoming live chats.
 * Added more localization support
 * Plugin should now be compatible with caching plugins.
 * Plugin now uses the normal WordPress update functionality
 *
 * 2.1
 * More precise functionality to handle if you are online or offline
 * Fixed a bug that recorded visitors when offline
 * Neatened up some code
 * Fixed some small bugs
 * 
 * 2.0
 * Added "not available" functionality. Allows the visitor to leave a message when the admin is offline.
 * You can now get notified via email if someone is trying to start a chat with you.
 * Better front-end UI.
 *
 *
 */

//error_reporting(E_ERROR);
global $wpdb;
global $wplc_pro_version;
global $wplc_tblname_offline_msgs;

$wplc_tblname_offline_msgs = $wpdb->prefix . "wplc_offline_messages";

$wplc_pro_version = "4.4.10";

//add_action('admin_menu', 'wplc_error_menu');

add_action('wp_head', 'wplc_pro_user_top_js');

require_once (plugin_dir_path(__FILE__) . "functions-pro.php");


register_activation_hook(__FILE__, 'wplc_pro_activate');
add_action('admin_head', 'wplc_register_pro_version');
add_action('admin_head', 'wplc_ma_head');
add_action('wp_logout', 'wplc_ma_agent_logout');
add_action('edit_user_profile_update', 'wplc_ma_set_user_as_agent'); // not current user profile
add_action('personal_options_update', 'wplc_ma_set_user_as_agent'); // current user profile
add_action('edit_user_profile', 'wplc_ma_custom_user_profile_fields'); // not current user profile
add_action('show_user_profile', 'wplc_ma_custom_user_profile_fields'); // current user profile
add_action('init', 'wplc_set_admin_to_access_chat');
add_action('init', 'wplc_pro_version_control');
add_action('admin_bar_menu', 'wplc_ma_online_agents', 100);
add_action('init', 'wplc_create_macro_post_type');
add_action('wp_ajax_wplc_macro', 'wplc_action_callback_pro');

//Ajax call backs
add_action('wp_ajax_nopriv_wplc_user_send_offline_message', 'wplc_action_callback_pro');
add_action('wp_ajax_nopriv_wplc_start_chat', 'wplc_action_callback_pro');
add_action('wp_ajax_wplc_user_send_offline_message', 'wplc_action_callback_pro');
add_action('wp_ajax_wplc_start_chat', 'wplc_action_callback_pro');
add_action('wp_ajax_wplc_ma_set_transient', 'wplc_action_callback_pro');
add_action('wp_ajax_wplc_ma_remove_transient', 'wplc_action_callback_pro');

function wplc_pro_version_control() {
    global $wplc_pro_version;

    /* add caps to admin */
    if (current_user_can('manage_options')) {
        global $user_ID;
        $user = new WP_User($user_ID);
        foreach ($user->roles as $urole) {
            if ($urole == "administrator") {
                $admins = get_role('administrator');
                $admins->add_cap('edit_wplc_quick_response');
                $admins->add_cap('edit_wplc_quick_response');
                $admins->add_cap('edit_other_wplc_quick_response');
                $admins->add_cap('publish_wplc_quick_response');
                $admins->add_cap('read_wplc_quick_response');
                $admins->add_cap('read_private_wplc_quick_response');
                $admins->add_cap('delete_wplc_quick_response');
            }
        }
    }



    $current_version = get_option("wplc_pro_current_version");
    if (!isset($current_version) || $current_version != $wplc_pro_version) {
        wplc_pro_update_db();
        update_option("wplc_pro_current_version", $wplc_pro_version);

        $wplc_pro_settings = get_option("WPLC_PRO_SETTINGS");
        if (isset($wplc_pro_settings['wplc_pro_chat_email_address'])) {
            
        } else {
            $wplc_pro_settings['wplc_pro_chat_email_address'] = get_option('admin_email');
        }
        update_option("WPLC_PRO_SETTINGS", $wplc_pro_settings);
    }
}

function wplc_pro_update_db() {
    global $wpdb;
    global $wplc_tblname_chats;
    global $wplc_tblname_offline_msgs;

    $sql = " SHOW COLUMNS FROM $wplc_tblname_chats WHERE `Field` = 'agent_id'";
    $results = $wpdb->get_results($sql);
    if (!$results) {
        $sql = "
            ALTER TABLE `$wplc_tblname_chats` ADD `agent_id` INT(11) NOT NULL ;
        ";
        $wpdb->query($sql);
    }

    $sql2 = "
        CREATE TABLE " . $wplc_tblname_offline_msgs . " (
          id int(11) NOT NULL AUTO_INCREMENT,
          timestamp datetime NOT NULL,
          name varchar(700) NOT NULL,
          email varchar(700) NOT NULL,
          message varchar(700) NOT NULL,
          ip varchar(700) NOT NULL,
          user_agent varchar(700) NOT NULL,
          PRIMARY KEY  (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
    ";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql2);

    $admins = get_role('administrator');
    $admins->add_cap('wplc_ma_agent');
    $uid = get_current_user_id();
    update_user_meta($uid, 'wplc_ma_agent', 1);
    update_user_meta($uid, "wplc_chat_agent_online", time());
}

function wplc_action_callback_pro() {
    $check = check_ajax_referer('wplc', 'security');
    if ($check == 1) {

        if ($_POST['action'] == "wplc_user_send_offline_message") {
            wplc_send_offline_message($_POST['name'], $_POST['email'], $_POST['msg'], $_POST['cid']);
            if(function_exists('wplc_pro_store_offline_message')){
                wplc_pro_store_offline_message($_POST['name'], $_POST['email'], $_POST['msg']);
            }
        }
        
        

        if ($_POST['action'] == "wplc_start_chat") {

            if ($_POST['cid']) {
                if ($_POST['name'] && $_POST['email']) {
                    echo wplc_user_initiate_chat(sanitize_text_field($_POST['name']), sanitize_email($_POST['email']), sanitize_text_field($_POST['cid']), $_POST['wplcsession']); // echo the chat session id
                } else {
                    echo "error2";
                }
            } else {
                if ($_POST['name'] && $_POST['email']) {
                    echo wplc_user_initiate_chat(sanitize_text_field($_POST['name']), sanitize_email($_POST['email']), null, $_POST['wplcsession']); // echo the chat session id
                } else {
                    echo "error2";
                }
            }
        }
        if ($_POST['action'] == "wplc_macro") {
            if (isset($_POST['postid'])) {
                $post_id = $_POST['postid'];
            } else {
                return false;
            }

            if ($post_id > 0) {
                $post_details = get_post($post_id);
                if ($post_details) {
                    echo json_encode(nl2br($post_details->post_content));
                } else {
                    echo json_encode("No post with that ID");
                    die();
                }
            } else {
                echo json_encode("No macro with that ID");
                die();
            }
        }
        if ($_POST['action'] == "wplc_ma_set_transient") {
            wplc_ma_set_agents_online($_POST['user_id']);
        }
        
        if($_POST['action'] == "wplc_ma_remove_transient"){
            wplc_ma_remove_agents_online($_POST['user_id']);
        }
    }


    die(); // this is required to return a proper result
}

function wplc_pro_version_check() {
    global $wplc_version;
    if ($wplc_version && ($wplc_version == "4.1.0") || floatval($wplc_version) < 4) {
        ?>
        <div class='error below-h1'>

            <p><?php _e("Dear User", "wplivechat") ?><br /></p>
            <p><?php _e("You are using an outdated version of WP Live Chat Support Basic. Please", "wplivechat") ?> <a href="http://wordpress.org/plugins/wp-live-chat-support/" target=\"_BLANK\"><?php _e("update to at least version", "wplivechat") ?> 4.1.2</a> <?php _e("to ensure all functionality is in working order", "wplivechat") ?>.</p>
            <p><strong><?php _e("You're live chat box on your website has been temporarily disabled until the basic plugin has been updated. This is to ensure a smooth and hassle-free user experience for both yourself and your visitors.", "wplivechat") ?></strong></p>
            <p><?php _e("You can update your plugin <a href='./update-core.php'>here</a> or <a href='./plugins.php'>here</a>.", "wplivechat") ?></strong></p>
            <p><?php _e("If you are having difficulty updating the plugin, please contact", "wplivechat") ?> nick@wp-livechat.com</p>

        </div>
        <?php
    }
}

function wplc_register_pro_version() {
    // pro version register
    if (!function_exists("wplc_init")) {
        echo "
        <div class='error below-h1'>

            <p>" . __("Dear Pro User", "wplivechat") . "<br /></p>
            <p>" . __("WP Live Chat Support Pro requires WP Live Chat Support to function. You can download the latest copy from", "wplivechat") . " <a href=\"http://wordpress.org/plugins/wp-live-chat-support/\">wordpress.org</a></p>

        </div>";
    }
}

function wplc_pro_activate() {

    if (!get_option("WPLC_PRO_SETTINGS")) {

        $wplc_current_user = wp_get_current_user();
        add_option('WPLC_PRO_SETTINGS', array(
            "wplc_chat_name" => __("Admin", "wplivechat"),
            "wplc_chat_pic" => plugin_dir_url(__FILE__) . "images/picture-for-chat-box.jpg",
            "wplc_chat_logo" => "",
            "wplc_chat_delay" => "10",
            "wplc_pro_fst1" => __("Questions?", "wplivechat"),
            "wplc_pro_fst2" => __("Chat with us", "wplivechat"),
            "wplc_pro_fst3" => __("Start live chat", "wplivechat"),
            "wplc_pro_sst1" => __("Start Chat", "wplivechat"),
            "wplc_pro_sst2" => __("Connecting. Please be patient...", "wplivechat"),
            "wplc_pro_tst1" => __("Reactivating your previous chat...", "wplivechat"),
            "wplc_pro_na" => __("Chat offline. Leave a message", "wplivechat"),
            "wplc_pro_intro" => __("Hello. Please input your details so that I may help you.", "wplivechat"),
            "wplc_pro_offline1" => __("We are currently offline. Please leave a message and we'll get back to you shortly.", "wplivechat"),
            "wplc_pro_offline2" => __("Sending message...", "wplivechat"),
            "wplc_pro_offline3" => __("Thank you for your message. We will be in contact soon.", "wplivechat"),
            "wplc_user_enter" => __("Press ENTER to send your message", "wplivechat"),
            "wplc_user_welcome_chat" => __("Welcome. How may I help you?", "wplivechat"),
            "wplc_pro_chat_notification" => "no",
            "wplc_pro_chat_email_address" => get_option('admin_email'),
            "wplc_include_on_pages" => "",
            "wplc_exclude_from_pages" => "",
        ));
        add_option('wplc_mail_type', 'wp_mail');
        add_option('wplc_mail_host');
        add_option('wplc_mail_port');
        add_option('wplc_mail_username');
        add_option('wplc_mail_password');
    }
    //delete_option('WPLC_PRO_SETTINGS');
    global $wpdb;
    global $wplc_tblname_chats;
    $sql = " SHOW COLUMNS FROM $wplc_tblname_chats WHERE `Field` = 'agent_id'";
    $results = $wpdb->get_results($sql);
    if (!$results) {
        $sql = "
            ALTER TABLE `$wplc_tblname_chats` ADD `agent_id` INT(11) NOT NULL ;
        ";
        $wpdb->query($sql);
    }
    $admins = get_role('administrator');
    $admins->add_cap('wplc_ma_agent');
    $uid = get_current_user_id();
    update_user_meta($uid, 'wplc_ma_agent', 1);
    update_user_meta($uid, "wplc_chat_agent_online", time());
}

function wplc_settings_page_pro() {
    include 'includes/settings_page_pro.php';
    return;
}

function wplc_admin_scripts() {
    wp_register_script("wplc-admin-pro-js", plugins_url('js/wplc_admin_pro.js', __FILE__), array('jquery'));
    wp_enqueue_script('wplc-admin-pro-js');
    
    $wpc_admin_js_strings = array(
        'accepting_chats' => __('You are currently accepting chats', 'wplivechat'),
        'not_accepting_chats' => __('You are not accepting chats', 'wplivechat'),
        'nonce' => wp_create_nonce("wplc"),
        'user_id' => get_current_user_id()
        );
    wp_localize_script('wplc-admin-pro-js', 'wplc_admin_strings', $wpc_admin_js_strings);
    
    wp_enqueue_script('jquery-ui-accordion');
    
    if (isset($_GET['page']) && $_GET['page'] == 'wplivechat-menu-settings') {
        wp_enqueue_media();
        wp_register_script('my-wplc-upload', plugins_url('js/media.js', __FILE__), array('jquery'), '1.0', true);
        wp_enqueue_script('my-wplc-upload');
    }
    
    if(isset($_GET['page']) && $_GET['page'] == 'wplivechat-menu'){
    
        wp_register_script('wplc_switchery', plugins_url('js/switchery.min.js', __FILE__), array('jquery'));
        wp_enqueue_script('wplc_switchery');

        wp_register_style('wplc_switchery_css', plugins_url('css/switchery.min.css', __FILE__));
        wp_enqueue_style('wplc_switchery_css');
    }
    
}

function wplc_admin_styles() {
    wp_register_style("wplc-admin-pro-css", plugins_url('css/chat_styles_pro.css', __FILE__));
    wp_enqueue_style('wplc-admin-pro-css');
    
    if (isset($_GET['page']) && $_GET['page'] == 'wplivechat-menu-settings') {
        wp_enqueue_style('thickbox');
    }
}

function wplc_pro_admin_display_history() {
    global $wpdb;
    global $wplc_tblname_chats;

    $results = $wpdb->get_results(
            "
	SELECT *
	FROM $wplc_tblname_chats
        WHERE `status` = 1
        ORDER BY `timestamp` DESC
	"
    );
    echo "
       <form method=\"post\" >
        <input type=\"submit\" value=\"".__('Delete History', 'wplivechat')."\" class='button' name=\"wplc-delete-chat-history\" /><br /><br />
       </form>

      <table class=\"wp-list-table widefat fixed \" cellspacing=\"0\">
	<thead>
	<tr>
		<th scope='col' id='wplc_id_colum' class='manage-column column-id sortable desc'  style=''><span>" . __("Date", "wplivechat") . "</span></th>
                <th scope='col' id='wplc_name_colum' class='manage-column column-name_title sortable desc'  style=''><span>" . __("Name", "wplivechat") . "</span></th>
                <th scope='col' id='wplc_email_colum' class='manage-column column-email' style=\"\">" . __("Email", "wplivechat") . "</th>
                <th scope='col' id='wplc_url_colum' class='manage-column column-url' style=\"\">" . __("URL", "wplivechat") . "</th>
                <th scope='col' id='wplc_status_colum' class='manage-column column-status'  style=\"\">" . __("Status", "wplivechat") . "</th>
                <th scope='col' id='wplc_action_colum' class='manage-column column-action sortable desc'  style=\"\"><span>" . __("Action", "wplivechat") . "</span></th>
        </tr>
	</thead>
        <tbody id=\"the-list\" class='list:wp_list_text_link'>
        ";
    if (!$results) {
        echo "<tr><td></td><td>" . __("No chats available at the moment", "wplivechat") . "</td></tr>";
    } else {
        foreach ($results as $result) {
            unset($trstyle);
            unset($actions);


            $url = admin_url('admin.php?page=wplivechat-menu&action=history&cid=' . $result->id);
            $actions = "<a href='$url' class='button' title='".__('View Chat History', 'wplivechat')."' target='_BLANK' id=''>".__('View Chat History', 'wplivechat')."</a>";
            $trstyle = "style='height:30px;'";

            echo "<tr id=\"record_" . $result->id . "\" $trstyle>";
            echo "<td class='chat_id column-chat_d'>" . $result->timestamp . "</td>";
            echo "<td class='chat_name column_chat_name' id='chat_name_" . $result->id . "'><img src=\"http://www.gravatar.com/avatar/" . md5($result->email) . "?s=40\" /> " . $result->name . "</td>";
            echo "<td class='chat_email column_chat_email' id='chat_email_" . $result->id . "'><a href='mailto:" . $result->email . "' title='Email " . ".$result->email." . "'>" . $result->email . "</a></td>";
            echo "<td class='chat_name column_chat_url' id='chat_url_" . $result->id . "'>" . $result->url . "</td>";
            echo "<td class='chat_status column_chat_status' id='chat_status_" . $result->id . "'><strong>" . wplc_return_status($result->status) . "</strong></td>";
            echo "<td class='chat_action column-chat_action' id='chat_action_" . $result->id . "'>$actions</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

function wplc_admin_pro_view_chat_history($cid) {
    wplc_pro_draw_chat_area($cid);
}

add_action('admin_print_scripts', 'wplc_admin_scripts');
add_action('admin_print_styles', 'wplc_admin_styles');

function wplc_pro_output_box() {

    global $wplc_version;
    if ($wplc_version && ($wplc_version == "4.1.0") || floatval($wplc_version) < 4) {
        return;
    }           
    
    $wplc_is_admin_logged_in = wplc_ma_is_agent_online();
    $wplc_settings = get_option("WPLC_SETTINGS");
    $wplc_pro_settings = get_option('WPLC_PRO_SETTINGS');
    if(isset($wplc_pro_settings['wplc_theme']) ){
        $wplc_theme = $wplc_pro_settings['wplc_theme'];
        // come back here
        if($wplc_theme == 'theme-1'){
            $wplc_settings_fill = "#DB0000";
            $wplc_settings_font = "#FFFFFF";
        } else if ($wplc_theme == 'theme-2'){
            $wplc_settings_fill = "#000000";
            $wplc_settings_font = "#FFFFFF";
        } else if ($wplc_theme == 'theme-3'){
            $wplc_settings_fill = "#DB30B3";
            $wplc_settings_font = "#FFFFFF";
        } else if ($wplc_theme == 'theme-4'){
            $wplc_settings_fill = "#1A14DB";
            $wplc_settings_font = "#F7FF0F";
        } else if ($wplc_theme == 'theme-5'){
            $wplc_settings_fill = "#3DCC13";
            $wplc_settings_font = "#FF0808";
        } else if ($wplc_theme == 'theme-6'){
            if ($wplc_settings["wplc_settings_fill"]) {
                $wplc_settings_fill = "#" . $wplc_settings["wplc_settings_fill"];
            } else {
                $wplc_settings_fill = "#ec832d";
            }
            if ($wplc_settings["wplc_settings_font"]) {
                $wplc_settings_font = "#" . $wplc_settings["wplc_settings_font"];
            } else {
                $wplc_settings_font = "#FFFFFF";
            }
        } else {
            if ($wplc_settings["wplc_settings_fill"]) {
                $wplc_settings_fill = "#" . $wplc_settings["wplc_settings_fill"];
            } else {
                $wplc_settings_fill = "#ec832d";
            }
            if ($wplc_settings["wplc_settings_font"]) {
                $wplc_settings_font = "#" . $wplc_settings["wplc_settings_font"];
            } else {
                $wplc_settings_font = "#FFFFFF";
            }
        }
    } else {
        if ($wplc_settings["wplc_settings_fill"]) {
            $wplc_settings_fill = "#" . $wplc_settings["wplc_settings_fill"];
        } else {
            $wplc_settings_fill = "#ec832d";
        }
        if ($wplc_settings["wplc_settings_font"]) {
            $wplc_settings_font = "#" . $wplc_settings["wplc_settings_font"];
        } else {
            $wplc_settings_font = "#FFFFFF";
        }
    }
    
    if(isset($wplc_pro_settings['wplc_using_localization_plugin']) && $wplc_pro_settings['wplc_using_localization_plugin'] == 1){
        $wplc_using_locale = true;
    } else {
        $wplc_using_locale = false;
    }
    
    /********************* EDIT THESE STRINGS WHEN USING A LOCALIZATION PLUGIN **************************/
    /****************************************START*******************************************************/
    $wplc_fst_1 = __('Questions?', 'wplivechat');
    $wplc_fst_2 = __('Chat with us', 'wplivechat');
    $wplc_fst_3 = __('Start live chat', 'wplivechat');

    $wplc_sst_1 = __('Start Chat', 'wplivechat');
    $wplc_sst_2 = __('Connecting. Please be patient...', 'wplivechat');

    $wplc_tst = __('Reactivating your previous chat...', 'wplivechat');

    $wplc_na = __('Chat offline. Leave a message', 'wplivechat');

    $wplc_intro = __('Hello. Please input your details so that I may help you.', 'wplivechat');

    $wplc_offline_1 = __('We are currently offline. Please leave a message and we\'ll get back to you shortly.', 'wplivechat');
    $wplc_offline_2 = __('We are currently offline. Please leave a message and we\'ll get back to you shortly.', 'wplivechat');
    $wplc_offline_3 = __('Thank you for your message. We will be in contact soon.', 'wplivechat');

    $wplc_enter = __('Press ENTER to send your message', 'wplivechat');

    $wplc_welcome = __('Welcome. How may I help you?', 'wplivechat');
    
    $wplc_replacement = __('Please click \'Start Chat\' to initiate a chat with an agent', 'wplivechat');
    /****************************************************************************************************/
    /****************************************END*********************************************************/

    
    
    if ($wplc_is_admin_logged_in == 1 or $wplc_is_admin_logged_in == true) {
        $wplc_tl_msg = "<div style=\"color: " . $wplc_settings_font . " !important;\"><strong>" . ($wplc_using_locale ? $wplc_fst_1 : stripslashes($wplc_pro_settings['wplc_pro_fst1'])) . "</strong> " . ( $wplc_using_locale ? $wplc_fst_2 : stripslashes($wplc_pro_settings['wplc_pro_fst2'])) ."</div>";
    } else {
        $wplc_tl_msg = "<span class='wplc_offline'>" . ($wplc_using_locale ? $wplc_na : stripslashes($wplc_pro_settings['wplc_pro_na'])) . "</span>";
    }
    
    
    ?>    
    <div class="wp-live-chat-wraper">
        <div id="wp-live-chat-header" style="background-color: <?php echo $wplc_settings_fill; ?> !important; color: <?php echo $wplc_settings_font; ?> !important;">
            <?php if ($wplc_pro_settings['wplc_chat_pic']) { ?>
                <div id="wp-live-chat-image">
                    <div id="wp-live-chat-inner-image-div">
                        <img src="<?php echo urldecode($wplc_pro_settings['wplc_chat_pic']); ?>" width="40px"/>
                    </div>
                </div> 
            <?php } ?>
            <i id="wp-live-chat-minimize" class="fa fa-minus" style="display:none;" alt="<?php _e('Minimize Chat Window', 'wplivechat'); ?>" title="<?php _e('Minimize Chat Window', 'wplivechat'); ?>"></i>
            <i id="wp-live-chat-close" class="fa fa-times" style="display:none;" alt="<?php _e('Close Chat Window', 'wplivechat'); ?>" title="<?php _e('Close Chat Window', 'wplivechat'); ?>"></i>

            <div id="wp-live-chat-1" >
                <div style="display:block; ">
                    <?php echo $wplc_tl_msg; ?>
                </div>
            </div>
        </div>
        <div id="wp-live-chat-2" style="display:none;">
            <?php
            if ($wplc_is_admin_logged_in == 1 or $wplc_is_admin_logged_in == true) {  // admin is logged in
                ?>
                <div id="wp-live-chat-2-info">
                <?php echo ($wplc_using_locale ? $wplc_intro : stripslashes($wplc_pro_settings['wplc_pro_intro'])); ?> 
                </div>

                <?php
                $wplc_settings = get_option("WPLC_SETTINGS");

                if (isset($wplc_settings['wplc_loggedin_user_info']) && $wplc_settings['wplc_loggedin_user_info'] == 1) {
                    $wplc_use_loggedin_user_details = 1;
                } else {
                    $wplc_use_loggedin_user_details = 0;
                }
                $wplc_loggedin_user_name = '';
                $wplc_loggedin_user_email = '';
                if ($wplc_use_loggedin_user_details == 1) {
                    global $current_user;

                    if ($current_user->data != null) {
                        //Logged in. Get name and email
                        $wplc_loggedin_user_name = $current_user->user_nicename;
                        $wplc_loggedin_user_email = $current_user->user_email;
                    }
                } else {
                    $wplc_loggedin_user_name = '';
                    $wplc_loggedin_user_email = '';
                }

                if (isset($wplc_settings['wplc_require_user_info']) && $wplc_settings['wplc_require_user_info'] == 1) {
                    $wplc_ask_user_details = 1;
                } else {
                    $wplc_ask_user_details = 0;
                }

                if ($wplc_ask_user_details == 1) {
                    //Ask the user to enter name and email
                    ?>
                    <input type="text" name="wplc_name" id="wplc_name" value="<?php echo $wplc_loggedin_user_name; ?>" placeholder="<?php _e("Name", "wplivechat"); ?>" />
                    <input type="text" name="wplc_email" id="wplc_email" wplc_hide="0" value="<?php echo $wplc_loggedin_user_email; ?>" placeholder="<?php _e("Email", "wplivechat"); ?>"  />
                    <?php
                } else {
                    //Dont ask the user
                    echo '<div style="padding: 7px; text-align: center;">';
                    if (isset($wplc_settings['wplc_user_alternative_text'])) {
                        echo ($wplc_using_locale ? $wplc_replacement : stripslashes($wplc_settings['wplc_user_alternative_text']));
                    }
                    echo '</div>';

                    $wplc_random_user_number = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
                    ?>
                    <input type="hidden" name="wplc_name" id="wplc_name" value="<?php if ($wplc_loggedin_user_name != '') {
                    echo $wplc_loggedin_user_name;
                } else {
                    echo 'user' . $wplc_random_user_number;
                } ?>" />
                    <input type="hidden" name="wplc_email" id="wplc_email" wplc_hide="1" value="<?php if ($wplc_loggedin_user_email != '' && $wplc_loggedin_user_email != null) {
                    echo $wplc_loggedin_user_email;
                } else {
                    echo $wplc_random_user_number . '@' . $wplc_random_user_number . '.com';
                } ?>" />
                    <?php
                }
                
                

                ?>
                <input id="wplc_start_chat_btn" type="button" value="<?php echo ($wplc_using_locale ? $wplc_sst_1 : $wplc_pro_settings['wplc_pro_sst1']); ?>" style="background-color: <?php echo $wplc_settings_fill; ?> !important; color: <?php echo $wplc_settings_font; ?> !important;"/>
            <?php
        } else {  // admin logged out
            ?>
            <div id="wp-live-chat-2-info">
            <?php echo ($wplc_using_locale ? $wplc_offline_1 : stripslashes($wplc_pro_settings['wplc_pro_offline1'])); ?>
            </div>
            <div id="wplc_message_div" >
                <input type="text" name="wplc_name" id="wplc_name" value="" placeholder="<?php _e("Name", "wplivechat"); ?>" /> 
                <input type="text" name="wplc_email" id="wplc_email" value=""  placeholder="<?php _e("Email", "wplivechat"); ?>"/>
                <textarea name="wplc_message" id="wplc_message" placeholder="<?php _e("Message", "wplivechat"); ?>"></textarea>
                <input id="wplc_na_msg_btn" type="button" value="<?php _e("Send message", "wplivechat"); ?>" style="background-color: <?php echo $wplc_settings_fill; ?> !important; color: <?php echo $wplc_settings_font; ?> !important;"/>
            </div>
        <?php } ?>
        </div>
        <div id="wp-live-chat-3" style="display:none;">
            <p><?php echo ($wplc_using_locale ? $wplc_sst_2 : stripslashes($wplc_pro_settings['wplc_pro_sst2'])); ?></p>
        </div>
        <div id="wp-live-chat-react" style="display:none;">
        </div>
        <div id="wp-live-chat-4" style="display:none;">
        <?php if (!empty($wplc_pro_settings['wplc_chat_logo'])) { ?>
                <div id="wplc_logo" style="">
                    <img class="wplc_logo_class" src="<?php echo urldecode(stripslashes($wplc_pro_settings['wplc_chat_logo'])); ?>" style="display:block; margin-bottom:5px; margin-left:auto; margin-right:auto;height:40px;" alt="<?php echo get_bloginfo('name'); ?>" title="<?php echo get_bloginfo('name'); ?>" />
                </div>
        <?php } ?>
            <div id="wplc_chatbox">
                <span class='wplc-admin-message'>
                    <?php echo ($wplc_using_locale ? $wplc_welcome : stripslashes($wplc_pro_settings['wplc_user_welcome_chat'])); ?>
                </span>
                <br />
                <div class='wplc-clear-float-message'></div>
            </div>
            <p style="text-align:center; font-size:11px;"><?php echo ($wplc_using_locale ? $wplc_enter : stripslashes($wplc_pro_settings['wplc_user_enter'])); ?></p>
            <p>
                <input type="text" name="wplc_chatmsg" id="wplc_chatmsg" value="" />
                <input type="hidden" name="wplc_cid" id="wplc_cid" value="" />
                <input id="wplc_send_msg" type="button" value="<?php _e("Send", "wplivechat"); ?>" style="display:none;" />
            </p>
        </div>
    </div>
    <?php
}

function wplc_head_pro() {
    global $wpdb;

    if (isset($_POST['wplc-delete-chat-history'])) {
        wplc_delete_history();
    }

    if (isset($_POST['wplc_save_settings'])) {
        $wplc_data = array();
        if (isset($_POST['wplc_settings_align'])) {
            $wplc_data['wplc_settings_align'] = esc_attr($_POST['wplc_settings_align']);
        }
        if (isset($_POST['wplc_settings_fill'])) {
            $wplc_data['wplc_settings_fill'] = esc_attr($_POST['wplc_settings_fill']);
        }
        if (isset($_POST['wplc_settings_font'])) {
            $wplc_data['wplc_settings_font'] = esc_attr($_POST['wplc_settings_font']);
        }
        if (isset($_POST['wplc_settings_enabled'])) {
            $wplc_data['wplc_settings_enabled'] = esc_attr($_POST['wplc_settings_enabled']);
        }
        if (isset($_POST['wplc_auto_pop_up'])) {
            $wplc_data['wplc_auto_pop_up'] = esc_attr($_POST['wplc_auto_pop_up']);
        }

        if (isset($_POST['wplc_require_user_info'])) {
            $wplc_data['wplc_require_user_info'] = esc_attr($_POST['wplc_require_user_info']);
        }
        if (isset($_POST['wplc_loggedin_user_info'])) {
            $wplc_data['wplc_loggedin_user_info'] = esc_attr($_POST['wplc_loggedin_user_info']);
        }
        if (isset($_POST['wplc_user_alternative_text']) && $_POST['wplc_user_alternative_text'] != '') {
            $wplc_data['wplc_user_alternative_text'] = esc_attr($_POST['wplc_user_alternative_text']);
        } else {
            $wplc_data['wplc_user_alternative_text'] = __("Please click 'Start Chat' to initiate a chat with an agent", "wplivechat");
        }
        if (isset($_POST['wplc_enabled_on_mobile'])) {
            $wplc_data['wplc_enabled_on_mobile'] = esc_attr($_POST['wplc_enabled_on_mobile']);
        }
        if (isset($_POST['wplc_display_name'])) {
            $wplc_data['wplc_display_name'] = esc_attr($_POST['wplc_display_name']);
        }
        if (isset($_POST['wplc_display_to_loggedin_only'])) {
            $wplc_data['wplc_display_to_loggedin_only'] = esc_attr($_POST['wplc_display_to_loggedin_only']);
        }
        if (isset($_POST['wplc_hide_when_offline'])) {
            $wplc_data['wplc_hide_when_offline'] = esc_attr($_POST['wplc_hide_when_offline']);
        }
                
        if(isset($_POST['wplc_record_ip_address'])){
            $wplc_data['wplc_record_ip_address'] = esc_attr($_POST['wplc_record_ip_address']);
        }
        
        update_option('WPLC_SETTINGS', $wplc_data);

        $wplc_pro_data = array();
        if (isset($_POST['wplc_pro_name'])) {
            $wplc_pro_data['wplc_chat_name'] = esc_attr($_POST['wplc_pro_name']);
        }
        if (isset($_POST['wplc_upload_pic'])) {
            $wplc_pro_data['wplc_chat_pic'] = esc_attr(urlencode(base64_decode($_POST['wplc_upload_pic'])));
        }
        if (isset($_POST['wplc_upload_logo'])) {
            $wplc_pro_data['wplc_chat_logo'] = esc_attr(urlencode(base64_decode($_POST['wplc_upload_logo'])));
        }
        if (isset($_POST['wplc_pro_delay'])) {
            $wplc_pro_data['wplc_chat_delay'] = esc_attr($_POST['wplc_pro_delay']);
        }
        if (isset($_POST['wplc_pro_chat_notification'])) {
            $wplc_pro_data['wplc_pro_chat_notification'] = esc_attr($_POST['wplc_pro_chat_notification']);
        }

        if (isset($_POST['wplc_pro_na'])) {
            $wplc_pro_data['wplc_pro_na'] = esc_attr($_POST['wplc_pro_na']);
        }
        if (isset($_POST['wplc_pro_chat_email_address'])) {
            $wplc_pro_data['wplc_pro_chat_email_address'] = esc_attr($_POST['wplc_pro_chat_email_address']);
        }
        if (isset($_POST['wplc_pro_fst1'])) {
            $wplc_pro_data['wplc_pro_fst1'] = esc_attr($_POST['wplc_pro_fst1']);
        }
        if (isset($_POST['wplc_pro_fst2'])) {
            $wplc_pro_data['wplc_pro_fst2'] = esc_attr($_POST['wplc_pro_fst2']);
        }
        if (isset($_POST['wplc_pro_fst3'])) {
            $wplc_pro_data['wplc_pro_fst3'] = esc_attr($_POST['wplc_pro_fst3']);
        }
        if (isset($_POST['wplc_pro_sst1'])) {
            $wplc_pro_data['wplc_pro_sst1'] = esc_attr($_POST['wplc_pro_sst1']);
        }
        if (isset($_POST['wplc_pro_sst2'])) {
            $wplc_pro_data['wplc_pro_sst2'] = esc_attr($_POST['wplc_pro_sst2']);
        }
        if (isset($_POST['wplc_pro_tst1'])) {
            $wplc_pro_data['wplc_pro_tst1'] = esc_attr($_POST['wplc_pro_tst1']);
        }

        if (isset($_POST['wplc_pro_offline1'])) {
            $wplc_pro_data['wplc_pro_offline1'] = esc_attr($_POST['wplc_pro_offline1']);
        }
        if (isset($_POST['wplc_pro_offline2'])) {
            $wplc_pro_data['wplc_pro_offline2'] = esc_attr($_POST['wplc_pro_offline2']);
        }
        if (isset($_POST['wplc_pro_offline3'])) {
            $wplc_pro_data['wplc_pro_offline3'] = esc_attr($_POST['wplc_pro_offline3']);
        }

        if (isset($_POST['wplc_pro_intro'])) {
            $wplc_pro_data['wplc_pro_intro'] = esc_attr($_POST['wplc_pro_intro']);
        }

        if (isset($_POST['wplc_user_enter'])) {
            $wplc_pro_data['wplc_user_enter'] = esc_attr($_POST['wplc_user_enter']);
        }
        if (isset($_POST['wplc_user_welcome_chat'])) {
            $wplc_pro_data['wplc_user_welcome_chat'] = esc_attr($_POST['wplc_user_welcome_chat']);
        }

        if (isset($_POST['wplc_include_on_pages'])) {
            $wplc_pro_data['wplc_include_on_pages'] = esc_attr($_POST['wplc_include_on_pages']);
        }
        if (isset($_POST['wplc_exclude_from_pages'])) {
            $wplc_pro_data['wplc_exclude_from_pages'] = esc_attr($_POST['wplc_exclude_from_pages']);
        }
        
        if(isset($_POST['wplc_animation'])){
            $wplc_pro_data['wplc_animation'] = esc_attr($_POST['wplc_animation']);
        }
        
        if(isset($_POST['wplc_theme'])){
            $wplc_pro_data['wplc_theme'] = esc_attr($_POST['wplc_theme']);
        }

        if(isset($_POST['wplc_auto_online'])){
            $wplc_pro_data['wplc_auto_online'] = esc_attr($_POST['wplc_auto_online']);
        }
        
        if(isset($_POST['wplc_make_agent'])){
            $wplc_pro_data['wplc_make_agent'] = esc_attr($_POST['wplc_make_agent']);
        }
        if(isset($_POST['wplc_ban_users_ip'])){
            $wplc_banned_ip_addresses = explode('<br />', nl2br($_POST['wplc_ban_users_ip']));
            foreach($wplc_banned_ip_addresses as $key => $value) {
                $data[$key] = trim($value);
            }
            $wplc_banned_ip_addresses = maybe_serialize($data);

            update_option('WPLC_BANNED_IP_ADDRESSES', $wplc_banned_ip_addresses);
        }
        
        if(isset($_POST['wplc_using_localization_plugin'])){
            $wplc_pro_data['wplc_using_localization_plugin'] = esc_attr($_POST['wplc_using_localization_plugin']);
        }
        
        update_option('WPLC_PRO_SETTINGS', $wplc_pro_data);

        update_option('wplc_mail_type', $_POST['wplc_mail_type']);
        update_option('wplc_mail_host', $_POST['wplc_mail_host']);
        update_option('wplc_mail_port', $_POST['wplc_mail_port']);
        update_option('wplc_mail_username', $_POST['wplc_mail_username']);
        update_option('wplc_mail_password', $_POST['wplc_mail_password']);
        update_option("WPLC_HIDE_CHAT", $_POST['wplc_hide_chat']);

        echo "<div class='updated'>";
        _e("Your settings have been saved.", "wplivechat");
        echo "</div>";
    }
}

function wplc_pro_return_delay() {
    $wplc_delay = get_option("WPLC_PRO_SETTINGS");
    return $wplc_delay['wplc_chat_delay'];
}

function wplc_pro_admin_menu_layout_display() {

    if (!isset($_GET['action'])) {
        $wplc_current_user_id = get_current_user_id();
        ?>
        <div class='wplc_page_title'>
            <h1><?php _e("Chat sessions", "wplivechat"); ?></h1>

            <p><?php _e("Please note: This window must be open in order to receive new chat notifications.", "wplivechat"); ?></p>
            <?php
            $is_agent = get_user_meta($wplc_current_user_id, 'wplc_ma_agent', true);            
            if(!$is_agent){
                $warning = "<p style='color: red;'><b>".__('You are not a chat agent. Please make yourself a chat agent before trying to chat to visitors', 'wplivechat')."</b></p>";
                echo $warning;
            }
            ?>
        </div>    
        <?php wplc_pro_version_check(); ?>        
        <?php
            //if(function_exists("wplc_ma_register")){
                $agent_id = wplc_ma_check_if_user_is_agent();
            //} else { 
                //$agent_id = true;
            //} 
        ?>                
        <div class="wplc_admin_dashboard_container">            
            <?php 
                $wplc_pro_settings = get_option("WPLC_PRO_SETTINGS");
                if(isset($wplc_pro_settings['wplc_auto_online'])  && $wplc_pro_settings['wplc_auto_online'] == 1 ){
                    ?>
            <div style="padding: 10px 0;"><input type="checkbox" class="wplc_switchery" name="wplc_agent_status" id="wplc_agent_status" <?php if(get_user_meta($wplc_current_user_id, "wplc_chat_agent_online", true)){ echo 'checked'; } ?> /><div id="wplc_agent_status_text" style="display: inline-block; padding-left: 10px;"></div></div>
            <?php
                }
                ?>                    
            <div id="wplc_sound"></div>
            <div id="wplc_admin_chat_area">
                <?php echo wplc_list_chats_pro($agent_id); ?>
            </div>
            <div id="wplc_admin_visitor_area">
                <?php echo wplc_list_visitors($agent_id); ?>
            </div>
        </div>
        <?php
    } else {

        if (isset($_GET['aid'])) {
            //if(function_exists("wplc_ma_register")){
            wplc_ma_update_agent_id($_GET['cid'], $_GET['aid']);
            //}
        }
        if ($_GET['action'] == 'ac') {
            wplc_change_chat_status($_GET['cid'], 3);
            wplc_pro_draw_chat_area($_GET['cid']);
        } else if ($_GET['action'] == 'history' && function_exists("wplc_register_pro_version")) {
            wplc_admin_pro_view_chat_history($_GET['cid']);
        } else if ($_GET['action'] == 'rc' && function_exists("wplc_register_pro_version")) {
            wplc_admin_pro_request_chat($_GET['cid']);
        }
    }
}

function wplc_admin_pro_request_chat($cid) {
    wplc_change_chat_status($cid, 6); // 6 = request chat
    wplc_pro_draw_chat_area($cid);
}

function wplc_pro_draw_chat_area($cid) {



    global $wpdb;
    global $wplc_tblname_chats;
    $results = $wpdb->get_results(
            "
        SELECT *
        FROM $wplc_tblname_chats
        WHERE `id` = '$cid'
        LIMIT 1
        "
    );

    foreach ($results as $result) {
        $status = "";
        if ($result->status == 1 || $result->status == 8) {
            $status = "Previous";
        } else if ($result->status == 3) {
            $status = "Active";
        } else if ($result->status == 6) {
            $status = "Awaiting";
        }
        ?>
        <style>

            .wplc-clear-float-message{
                clear: both;
            }

        </style>
        <?php
        $user_data = maybe_unserialize($result->ip);
        if (is_array($user_data)) {
            $user_ip = $user_data['ip'];
            $browser = wplc_return_browser_string($user_data['user_agent']);
            $browser_image = wplc_return_browser_image($browser, "16");
        } else {
            $user_ip = $user_data;
            $browser = __("Unknown", "wplivechat");
            $browser_image = wplc_return_browser_image($browser, "16");
        }
        
        if($user_ip == ""){
            $user_ip = __('IP Address not recorded', 'wplivechat');
        } else {
            $user_ip = "<a href='http://www.ip-adress.com/ip_tracer/" . $user_ip . "' title='".__('Whois for' ,'wplivechat')." ".$user_ip."'>".$user_ip."</a>";
        } 


        global $wplc_basic_plugin_url;

        echo "<h2>$status Chat with " . $result->name . "</h2>";
        echo "<style>#adminmenuwrap { display:none; } #adminmenuback { display:none; } #wpadminbar { display:none; } #wpfooter { display:none; } .update-nag { display:none; }</style>";

        echo "<div class=\"end_chat_div\"><a href=\"javascript:void(0);\" class=\"wplc_admin_close_chat button\" id=\"wplc_admin_close_chat\">" . __("End chat", "wplivechat") . "</a></div>";

        echo "<div id='admin_chat_box'>";

        if ($result->status == 6) {
            echo "<strong>" . __("Attempting to open the chat window... Please be patient.", "wplivechat") . "</strong>";
            return;
        }

        if ($result->status != 6) {
            echo "<div class='admin_chat_box'>";
            echo "  <div class='admin_chat_box_inner' id='admin_chat_box_area_" . $result->id . "'>" . wplc_return_chat_messages($cid) . "</div>";

            if ($result->status == 3) {
                echo "  <div class='admin_chat_box_inner_bottom'>" . wplc_return_chat_response_box($cid) . "</div>";
            }
            echo "</div>";
            echo "<div class='admin_visitor_info'>";
            echo "  <div style='float:left; width:100px;'><img src=\"http://www.gravatar.com/avatar/" . md5($result->email) . "\" class=\"admin_chat_img\" /></div>";
            echo "  <div style='float:left;'>";

            echo "      <div class='admin_visitor_info_box1'>";
            echo "          <span class='admin_chat_name'>" . $result->name . "</span>";
            echo "          <span class='admin_chat_email'>" . $result->email . "</span>";
            echo "      </div>";
            echo "  </div>";

            echo "<div class='admin_visitor_advanced_info'>";
            echo "      <strong>" . __("Site Info", "wplivechat") . "</strong>";
            echo "      <hr />";
            echo "      <span class='part1'>" . __("Chat initiated on:", "wplivechat") . "</span> <span class='part2'>" . $result->url . "</span>";
            echo "</div>";

            echo "<div class='admin_visitor_advanced_info'>";
            echo "      <strong>" . __("Advanced Info", "wplivechat") . "</strong>";
            echo "      <hr />";
            echo "      <span class='part1'>" . __("Browser:", "wplivechat") . "</span><span class='part2'> $browser <img src='" . $wplc_basic_plugin_url . "/images/$browser_image' alt='$browser' title='$browser' /><br />";
            echo "      <span class='part1'>" . __("IP Address:", "wplivechat") . "</span><span class='part2'> ".$user_ip;
            echo "</div>";

            echo "  <div id=\"wplc_sound_update\"></div>";
            echo "</div>";
        }
        if ($result->status == 3) {
            echo "<div class='admin_chat_quick_controls'>";
            echo "  <p style=\"text-align:left; font-size:11px;\">Press ENTER to send your message</p>";
            echo wplc_return_macros();
            echo "  </div>";
            echo "</div>";
        }


        echo "</div>";
    }
}

function wplc_return_pro_admin_chat_javascript($cid) {
    $ajax_nonce = wp_create_nonce("wplc");
    if (function_exists("wplc_pro_get_admin_picture")) {
        $src = wplc_pro_get_admin_picture();
        if ($src) {
            $image = "<img src=" . $src . " width='20px' id='wp-live-chat-2-img'/>";
        } else {
            $image = "";
        }
    }
    $wplc_settings = get_option("WPLC_SETTINGS");

    if (isset($wplc_settings['wplc_display_name']) && $wplc_settings['wplc_display_name'] == 1) {
        $display_name = 'display';
    } else {
        $display_name = 'hide';
    }
    ?>

    <script type="text/javascript">

        var wplc_ajaxurl = '<?php echo plugins_url('/ajax-pro.php', __FILE__); ?>';
        var chat_status = 3;
        var cid = <?php echo $cid; ?>;
        var aid = <?php echo $_GET['aid'] ?>;
        var data = {
            action: 'wplc_admin_long_poll_chat',
            security: '<?php echo $ajax_nonce; ?>',
            cid: cid,
            chat_status: chat_status,
            aid: aid
        };
        var wplc_run = true;

        var wplc_display_name = '<?php echo $display_name; ?>';

        function wplc_call_to_server_admin_chat(data) {
            jQuery.ajax({
                url: wplc_ajaxurl,
                data: data,
                type: "POST",
                success: function (response) {
                    if (response) {
    //                        console.log(response);
                        response = JSON.parse(response);
                        if (response['action'] === "wplc_ma_agant_already_answered") {
                            jQuery('#wplc_admin_chat').empty().append("<h2><?php _e("This chat has already been answered. Please close the chat window", "wplivechat") ?></h2>");
                            wplc_run = false;
                            //console.log("Chat Already Answered");
                            //console.log(aid);
                        }
                        if (response['action'] === "wplc_update_chat_status") {
                            data['chat_status'] = response['chat_status'];
                            wplc_display_chat_status_update(response['chat_status'], cid);
                        }
                        if (response['action'] === "wplc_new_chat_message") {
                            current_len = jQuery("#admin_chat_box_area_" + cid).html().length;
                            jQuery("#admin_chat_box_area_" + cid).append(response['chat_message']);
                            new_length = jQuery("#admin_chat_box_area_" + cid).html().length;
                            if (current_len < new_length) {
                                document.getElementById("wplc_sound_update").innerHTML = "<embed src='<?php echo plugins_url('/ding.mp3', __FILE__); ?>' hidden=true autostart=true loop=false>";
                            }
                            var height = jQuery('#admin_chat_box_area_' + cid)[0].scrollHeight;
                            jQuery('#admin_chat_box_area_' + cid).scrollTop(height);
                        }
                        if (response['action'] === "wplc_user_open_chat") {
                            data['action_2'] = "";
    <?php $url = admin_url('admin.php?page=wplivechat-menu&action=ac&cid=' . $cid . '&aid=' . $_GET['aid']); ?>
                            window.location.replace('<?php echo $url; ?>');
                        }

                    }
                },
                error: function (jqXHR, exception) {
                    if (jqXHR.status == 404) {
                        console.log('Requested page not found. [404]');
                        wplc_run = false;
                    } else if (jqXHR.status == 500) {
                        console.log('Internal Server Error [500].');
                        wplc_run = false;
                    } else if (exception === 'parsererror') {
                        console.log('Requested JSON parse failed.');
                        wplc_run = false;
                    } else if (exception === 'abort') {
                        console.log('Ajax request aborted.');
                        wplc_run = false;
                    } else {
                        console.log('Uncaught Error.\n' + jqXHR.responseText);
                        wplc_run = false;
                    }
                },
                complete: function (response) {
                    //console.log(wplc_run);
                    if (wplc_run) {
                        setTimeout(function () {
                            wplc_call_to_server_admin_chat(data);
                        }, 1500);
                    }
                },
                timeout: 120000
            });
        }
        ;

        function wplc_display_chat_status_update(new_chat_status, cid) {
            if (new_chat_status === "0") {
            } else {
                if (chat_status !== new_chat_status) {
                    previous_chat_status = chat_status;
                    //console.log("previous chat status: "+previous_chat_status);
                    chat_status = new_chat_status;
                    //("chat status: "+chat_status);

                    if ((previous_chat_status === "2" && chat_status === "3") || (previous_chat_status === "5" && chat_status === "3")) {
                        jQuery("#admin_chat_box_area_" + cid).append("<em><?php _e("User has opened the chat window", "wplivechat"); ?></em><br />");
                        var height = jQuery('#admin_chat_box_area_' + cid)[0].scrollHeight;
                        jQuery('#admin_chat_box_area_' + cid).scrollTop(height);

                    } else if (chat_status == "10" && previous_chat_status == "3") {
                        jQuery("#admin_chat_box_area_" + cid).append("<em><?php _e("User has minimized the chat window", "wplivechat"); ?></em><br />");
                        var height = jQuery('#admin_chat_box_area_' + cid)[0].scrollHeight;
                        jQuery('#admin_chat_box_area_' + cid).scrollTop(height);
                    }
                    else if (chat_status === "3" && previous_chat_status === "10") {
                        jQuery("#admin_chat_box_area_" + cid).append("<em><?php _e("User has maximized the chat window", "wplivechat"); ?></em><br />");
                        var height = jQuery('#admin_chat_box_area_' + cid)[0].scrollHeight;
                        jQuery('#admin_chat_box_area_' + cid).scrollTop(height);
                    }
                    else if (chat_status === "1" || chat_status === "8") {
                        jQuery("#admin_chat_box_area_" + cid).append("<em><?php _e("User has closed and ended the chat", "wplivechat"); ?></em><br />");
                        var height = jQuery('#admin_chat_box_area_' + cid)[0].scrollHeight;
                        jQuery('#admin_chat_box_area_' + cid).scrollTop(height);
                        document.getElementById('wplc_admin_chatmsg').disabled = true;
                    }
                }
            }
        }

        jQuery(document).ready(function () {
            var wplc_image = "<?php echo $image ?>";

            var wplc_ajaxurl = '<?php echo plugins_url('/ajax-pro.php', __FILE__); ?>';



            jQuery("#wplc_admin_chatmsg").focus();


            jQuery(".wplc_macros_select").change(function () {

                var wplc_id = this.value;
                if (parseInt(wplc_id) === 0) {
                    return;
                }
                var data = {
                    action: 'wplc_macro',
                    dataType: "json",
                    postid: wplc_id,
                    security: '<?php echo $ajax_nonce; ?>'
                };
                jQuery.post(ajaxurl, data, function (response) {
                    var post_content = jQuery.parseJSON(response);
                    jQuery("#wplc_admin_chatmsg").val(jQuery("#wplc_admin_chatmsg").val() + post_content);

                });

            });

    //            console.log(data);
    <?php if ($_GET['action'] == 'rc') { ?>
                //this is to initiate a chat with a user from admin side
                data['action_2'] = "wplc_long_poll_check_user_opened_chat";
                wplc_call_to_server_admin_chat(data);

    <?php } else { ?>

                wplc_call_to_server_admin_chat(data);

                if (jQuery('#wplc_admin_cid').length) {
                    var wplc_cid = jQuery("#wplc_admin_cid").val();
                    var height = jQuery('#admin_chat_box_area_' + wplc_cid)[0].scrollHeight;
                    jQuery('#admin_chat_box_area_' + wplc_cid).scrollTop(height);
                }

                jQuery(".wplc_admin_accept").on("click", function () {
                    wplc_title_alerts3 = setTimeout(function () {
                        document.title = "WP Live Chat Support";
                    }, 2500);
                    var cid = jQuery(this).attr("cid");

                    var data = {
                        action: 'wplc_admin_accept_chat',
                        cid: cid,
                        security: '<?php echo $ajax_nonce; ?>'
                    };
                    jQuery.post(wplc_ajaxurl, data, function (response) {
                        //console.log("wplc_admin_accept_chat");
                        wplc_refresh_chat_boxes[cid] = setInterval(function () {
                            wpcl_admin_update_chat_box(cid);
                        }, 3000);
                        jQuery("#admin_chat_box_" + cid).show();
                    });
                });

                jQuery("#wplc_admin_chatmsg").keyup(function (event) {
                    if (event.keyCode == 13) {
                        jQuery("#wplc_admin_send_msg").click();
                    }
                });

                jQuery("#wplc_admin_close_chat").on("click", function () {
                    var wplc_cid = jQuery("#wplc_admin_cid").val();
                    var data = {
                        action: 'wplc_admin_close_chat',
                        security: '<?php echo $ajax_nonce; ?>',
                        cid: wplc_cid

                    };
                    jQuery.post(wplc_ajaxurl, data, function (response) {
                        //console.log("wplc_admin_close_chat");
        //                        console.log(response);
                        window.close();
                    });

                });
                function wplc_strip(str) {
                    str = str.replace(/<br>/gi, "\n\r");
                    str = str.replace(/<br\/>/gi, "\n\r");
                    str = str.replace(/<br \/>/gi, "\n\r");
                    str = str.replace(/<p.*>/gi, "\n\r");
                    str = str.replace(/<a.*href="(.*?)".*>(.*?)<\/a>/gi, " $2 ($1) ");
                    str = str.replace(/<(?:.|\s)*?>/g, "");
                    return str;
                }

                jQuery("#wplc_admin_send_msg").on("click", function () {
                    var wplc_cid = jQuery("#wplc_admin_cid").val();
                    var wplc_chat = wplc_strip(document.getElementById('wplc_admin_chatmsg').value);
                    var wplc_name = "<?php echo wplc_return_from_name(get_current_user_id()); ?>";
                    jQuery("#wplc_admin_chatmsg").val('');

                    if (wplc_display_name == 'display') {
                        jQuery("#admin_chat_box_area_" + wplc_cid).append("<span class='wplc-admin-message'>" + wplc_image + " <strong>" + wplc_name + "</strong>: " + wplc_chat + "</span><br /><div class='wplc-clear-float-message'></div>");
                    } else {
                        jQuery("#admin_chat_box_area_" + wplc_cid).append("<span class='wplc-admin-message'>" + wplc_chat + "</span><div class='wplc-clear-float-message'></div>");
                    }

                    var height = jQuery('#admin_chat_box_area_' + wplc_cid)[0].scrollHeight;
                    jQuery('#admin_chat_box_area_' + wplc_cid).scrollTop(height);


                    var data = {
                        action: 'wplc_admin_send_msg',
                        security: '<?php echo $ajax_nonce; ?>',
                        cid: wplc_cid,
                        msg: wplc_chat,
                        admin_name: wplc_name
                    };
                    jQuery.post(wplc_ajaxurl, data, function (response) {
                        //console.log("wplc_admin_send_msg");

                        /* do nothing
                         jQuery("#admin_chat_box_area_"+wplc_cid).html(response);
                         var height = jQuery('#admin_chat_box_area_'+wplc_cid)[0].scrollHeight;
                         jQuery('#admin_chat_box_area_'+wplc_cid).scrollTop(height);
                         */
                    });


                });






    <?php } ?>
        });
    </script>
    <?php
}

function wplc_pro_admin_javascript() {
    $ajax_nonce = wp_create_nonce("wplc");


    $agent_id = wplc_ma_check_if_user_is_agent();
    ?>
    <script type="text/javascript">


        var wplc_ajaxurl = '<?php echo plugins_url('/ajax-pro.php', __FILE__); ?>';

        var data = {
            action: 'wplc_admin_long_poll',
            security: '<?php echo $ajax_nonce; ?>',
            wplc_list_visitors_data: false,
            wplc_update_admin_chat_table: false,
            wplc_agent_id: '<?php echo $agent_id ?>'
        };
        var wplc_pending_refresh = null;

        var wplc_notification_icon_url = '<?php echo plugins_url('/images/wplc_notification_icon.png', __FILE__); ?>';

    //        function wplc_desktop_notification() {
    //            if(typeof Notification !== 'undefined'){
    //                if (!Notification) {
    //                  return;
    //                }
    //                if (Notification.permission !== "granted")
    //                  Notification.requestPermission();
    //
    //                var wplc_desktop_notification = new Notification('New chat received', {
    //                    icon: wplc_notification_icon_url,
    //                    body: "A new chat has been received. Please go the 'Live Chat' page to accept the chat"
    //                });
    //                //Notification.close()
    //            }
    //        }

        function wplc_call_to_server(data) {

            var wplc_run = true;
            jQuery.ajax({
                url: wplc_ajaxurl,
                data: data,
                type: "POST",
                success: function (response) {

                    //Update your dashboard gauge
                    if (response) {
                        //console.log(response);
                        response = JSON.parse(response);
                        data["wplc_list_visitors_data"] = response['wplc_list_visitors_data'];
                        data["wplc_update_admin_chat_table"] = response['wplc_update_admin_chat_table'];
                        //console.log(response['visitors']);
                        if (response['action'] === "wplc_list_visitors") {
                            jQuery("#wplc_admin_visitor_area").html(response['wplc_list_visitors_data']);
                            jQuery( "#wplc_visitor_accordion" ).accordion({heightStyle: "content"});
                        }
                        if (response['action'] === "wplc_update_admin_chat") {
                            jQuery("#wplc_admin_chat_area").html(response['wplc_update_admin_chat_table']);
                            if (response['pending'] === true) {

                                var orig_title = document.getElementsByTagName("title")[0].innerHTML;
                                var ringer_cnt = 0;
                                wplc_pending_refresh = setInterval(function () {
                                    //console.log("chat request");

                                    ringer_cnt++;

                                    if (ringer_cnt <= 1) {
                                        wplc_desktop_notification();
                                    }

                                    if (ringer_cnt > 1) {
                                        clearInterval(wplc_pending_refresh);
                                        wplc_title_alerts4 = setTimeout(function () {
                                            document.title = orig_title;
                                        }, 4000);
                                        return;
                                    }

                                    document.title = "** CHAT REQUEST **";
                                    wplc_title_alerts2 = setTimeout(function () {
                                        document.title = orig_title;
                                    }, 2000);
                                    wplc_title_alerts4 = setTimeout(function () {
                                        document.title = "** CHAT REQUEST **";
                                    }, 4000);

//                                    document.getElementById("wplc_sound").innerHTML = "<embed src='<?php echo plugins_url('/ring.wav', __FILE__); ?>' hidden=true autostart=true loop=false>";

                                        var wplc_notify_sound = '<?php echo plugins_url('/ring.wav', __FILE__); ?>';
                                        var wplc_notify_chat = new Audio(wplc_notify_sound);
    
                                        if(ringer_cnt < 5){
                                            wplc_notify_chat.play();
                                        }

                                }, 5000);
                            } else {
                                //console.log("end");
                                clearInterval(wplc_pending_refresh);
                            }
                        }

                    }
                },
                error: function (jqXHR, exception) {
                    if (jqXHR.status == 404) {
                        console.log('Requested page not found. [404]');
                        wplc_run = false;
                    } else if (jqXHR.status == 500) {
                        console.log('Internal Server Error [500].');
                        wplc_run = false;
                    } else if (exception === 'parsererror') {
                        console.log('Requested JSON parse failed.');
                        wplc_run = false;
                    } else if (exception === 'abort') {
                        console.log('Ajax request aborted.');
                        wplc_run = false;
                    } else {
                        console.log('Uncaught Error.\n' + jqXHR.responseText);
                        wplc_run = false;
                    }
                },
                complete: function (response) {
                    //console.log(wplc_run);
                    if (wplc_run) {
                        setTimeout(function () {
                            wplc_call_to_server(data);
                        }, 3000);
                    }
                },
                timeout: 120000
            });
        }
        ;

        jQuery(document).ready(function () {
            jQuery('body').on("click", "a", function (event) {
                if (jQuery(this).hasClass('wplc_open_chat')) {

                    if (event.preventDefault) {
                        event.preventDefault();
                    } else {
                        event.returnValue = false;
                    }
                    window.open(jQuery(this).attr("href"), jQuery(this).attr("window-title"), "width=800,height=620,scrollbars=yes", false);
                }
            });
            wplc_call_to_server(data);
        });



    </script>
    <?php
}

$wplc_api_url = 'http://ccplugins.co/apid-wplc/';
$wplc_plugin_slug = basename(dirname(__FILE__));

// Take over the update check
add_filter('pre_set_site_transient_update_plugins', 'wplc_check_for_plugin_update');

function wplc_check_for_plugin_update($checked_data) {
    global $wplc_api_url, $wplc_plugin_slug, $wp_version;

    //Comment out these two lines during testing.
    if (empty($checked_data->checked))
        return $checked_data;



    $args = array(
        'slug' => $wplc_plugin_slug,
        'version' => $checked_data->checked[$wplc_plugin_slug . '/' . $wplc_plugin_slug . '.php'],
    );
    $request_string = array(
        'body' => array(
            'action' => 'basic_check',
            'request' => serialize($args),
            'api-key' => md5(get_bloginfo('url'))
        ),
        'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
    );

    // Start checking for an update
    $raw_response = wp_remote_post($wplc_api_url, $request_string);


    if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
        $response = unserialize($raw_response['body']);

    if (is_object($response) && !empty($response)) // Feed the update data into WP updater
        $checked_data->response[$wplc_plugin_slug . '/' . $wplc_plugin_slug . '.php'] = $response;

    return $checked_data;
}

add_filter('plugins_api', 'wplc_plugin_api_call', 10, 3);

function wplc_plugin_api_call($def, $action, $args) {
    global $wplc_plugin_slug, $wplc_api_url, $wp_version;

    if (!isset($args->slug) || ($args->slug != $wplc_plugin_slug))
        return false;

    // Get the current version
    $plugin_info = get_site_transient('update_plugins');
    $current_version = $plugin_info->checked[$wplc_plugin_slug . '/' . $wplc_plugin_slug . '.php'];
    $args->version = $current_version;

    $request_string = array(
        'body' => array(
            'action' => $action,
            'request' => serialize($args),
            'api-key' => md5(get_bloginfo('url'))
        ),
        'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
    );

    $request = wp_remote_post($wplc_api_url, $request_string);

    if (is_wp_error($request)) {
        $res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
    } else {
        $res = unserialize($request['body']);

        if ($res === false)
            $res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
    }

    return $res;
}

function wplc_pro_user_top_js() {
    $ajax_nonce = wp_create_nonce("wplc");
    $wplc_settings = get_option("WPLC_SETTINGS");
    wp_register_script('wplc-user-jquery-cookie', plugins_url('/js/jquery-cookie.js', __FILE__), array('jquery-ui-draggable'));
    wp_enqueue_script('wplc-user-jquery-cookie');    
    ?>

    <script type="text/javascript">

        var wplc_delay;



        var wplc_ajaxurl = '<?php echo plugins_url('/ajax-pro.php', __FILE__); ?>';


        var wplc_nonce = '<?php echo $ajax_nonce; ?>';
        wplc_delay = '<?php echo wplc_pro_return_delay(); ?>000';
        var wplc_offline_msg = '<?php $wplc_pro_settings = get_option("WPLC_PRO_SETTINGS");
    echo stripslashes($wplc_pro_settings['wplc_pro_offline2']); ?>';
        var wplc_offline_msg3 = '<?php $wplc_pro_settings = get_option("WPLC_PRO_SETTINGS");
    echo stripslashes($wplc_pro_settings['wplc_pro_offline3']); ?>';

    <?php
    $wplc_is_admin_logged_in = wplc_ma_is_agent_online();

    if ($wplc_is_admin_logged_in == 1 or $wplc_is_admin_logged_in == true) {
        ?>
            var wplc_al = true;
    <?php } else { ?>
            var wplc_al = false;
    <?php } ?>
    </script>
    <?php
}

function wplc_pro_draw_user_box() {

    /* do not show if pro is outdated */
    global $wplc_version;
    if (isset($wplc_version)) {
        $float_version = floatval($wplc_version);
        if ($float_version < 4) {
            return;
        }
    }
    
    $wplc_settings = get_option("WPLC_SETTINGS");

    if (isset($wplc_settings['wplc_display_to_loggedin_only']) && $wplc_settings['wplc_display_to_loggedin_only'] == 1) {
        /* Only show to users that are logged in */
        if (!is_user_logged_in()) {
            return;
        }
    }

    if (isset($wplc_settings['wplc_display_name']) && $wplc_settings['wplc_display_name'] == 1) {
        $wplc_display = 'display';
    } else {
        $wplc_display = 'hide';
    }

    if ($wplc_settings["wplc_settings_enabled"] == 2) {
        return;
    }

    wp_register_script('wplc-user-script', plugins_url('/js/wplc_u_pro.js', __FILE__));
    wp_enqueue_script('wplc-user-script');
    $wplc_hide_chat = "";
    if (get_option('WPLC_HIDE_CHAT')) {
        $wplc_hide_chat = "yes";
    }

    wp_localize_script('wplc-user-script', 'wplc_2_ajax_url', array('ajaxurl' => admin_url('admin-ajax.php')));
    wp_localize_script('wplc-user-script', 'wplc_hide_chat', $wplc_hide_chat);
    wp_localize_script('wplc-user-script', 'wplc_plugin_url', plugins_url());

    wp_localize_script('wplc-user-script', 'wplc_display_name', $wplc_display);

    if (isset($_COOKIE['wplc_email']) && $_COOKIE['wplc_email'] != "") {
        $wplc_user_gravatar = md5(strtolower(trim($_COOKIE['wplc_email'])));
    } else {
        $wplc_user_gravatar = "";
    }

    if ($wplc_user_gravatar != "") {
        $wplc_grav_image = "<img src='http://www.gravatar.com/avatar/$wplc_user_gravatar?s=20' />";
    } else {
        $wplc_grav_image = "";
    }
    wp_localize_script('wplc-user-script', 'wplc_gravatar_image', $wplc_grav_image);

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-draggable');

    wplc_output_box();
}

function wplc_admin_error_layout() {
    $file = plugins_url("wp_livechat_error_log.txt", __FILE__);
    echo "<h1>Error Log</h1>";
    echo nl2br(file_get_contents($file));
}

function wplc_admin_menu_pro() {

    if (current_user_can('wplc_ma_agent')) {
        $cap = "wplc_ma_agent";
    } else {
        $cap = "manage_options";
    }

    $wplc_mainpage = add_menu_page('WP Live Chat', __('Live Chat', 'wplivechat'), $cap, 'wplivechat-menu', 'wplc_admin_menu_layout');
    add_submenu_page('wplivechat-menu', __('Settings', 'wplivechat'), __('Settings', 'wplivechat'), 'manage_options', 'wplivechat-menu-settings', 'wplc_admin_settings_layout');
    add_submenu_page('wplivechat-menu', __('Quick Responses', 'wplivechat'), __('Quick Responses', 'wplivechat'), 'manage_options', 'edit.php?post_type=wplc_quick_response');

    add_submenu_page('wplivechat-menu', __('History', 'wplivechat'), __('History', 'wplivechat'), 'manage_options', 'wplivechat-menu-history', 'wplc_admin_history_layout');
    add_submenu_page('wplivechat-menu', __('Missed Chats', 'wplivechat'), __('Missed Chats', 'wplivechat'), 'manage_options', 'wplivechat-menu-missed-chats', 'wplc_admin_missed_chats');
    add_submenu_page('wplivechat-menu', __('Offline Messages', 'wplivechat'), __('Offline Messages', 'wplivechat'), 'manage_options', 'wplivechat-menu-offline-messages', 'wplc_admin_offline_messages');
    add_submenu_page('wplivechat-menu', __('Feedback', 'wplivechat'), __('Feedback', 'wplivechat'), 'manage_options', 'wplivechat-menu-feedback-page', 'wplc_feedback_page_include');


    if (file_exists(plugin_dir_path(__FILE__) . "wp_livechat_error_log.txt")) {
        add_submenu_page('wplivechat-menu', __('Error Log', 'wplivechat'), __('Error Log', 'wplivechat'), 'manage_options', 'wplivechat-menu-error', 'wplc_admin_error_layout');
    }
    if(function_exists('wplc_support_menu')){
        add_submenu_page('wplivechat-menu', __('Support', 'wplivechat'), __('Support', 'wplivechat'), 'manage_options', 'wplivechat-menu-support-page', 'wplc_support_menu');   
    }
}

function wplc_create_macro_post_type() {
    $labels = array(
        'name' => __('Quick Responses', 'wplivechat'),
        'singular_name' => __('Quick Response', 'wplivechat'),
        'add_new' => __('New Quick Response', 'wplivechat'),
        'add_new_item' => __('Add New Quick Response', 'wplivechat'),
        'edit_item' => __('Edit Quick Response', 'wplivechat'),
        'new_item' => __('New Quick Response', 'wplivechat'),
        'all_items' => __('All Quick Responses', 'wplivechat'),
        'view_item' => __('View Quick Responses', 'wplivechat'),
        'search_items' => __('Search Quick Responses', 'wplivechat'),
        'not_found' => __('No Quick Responses found', 'wplivechat'),
        'not_found_in_trash' => __('No Quick Responses found in the Trash', 'wplivechat'),
        'menu_name' => __('Quick Responses', 'wplivechat')
    );
    $args = array(
        'labels' => $labels,
        'description' => __('Quick Responses for WP Live Chat Support Pro', 'wplivechat'),
        'public' => false,
        'menu_position' => 53,
        'show_in_nav_menus' => false,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'wplc_quick_response'),
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'revisions', 'author'),
        'has_archive' => true,
        'capabilities' => array(
            'edit_post' => 'edit_wplc_quick_response',
            'edit_posts' => 'edit_wplc_quick_response',
            'edit_others_posts' => 'edit_other_wplc_quick_response',
            'publish_posts' => 'publish_wplc_quick_response',
            'read_post' => 'read_wplc_quick_response',
            'read_private_posts' => 'read_private_wplc_quick_response',
            'delete_post' => 'delete_wplc_quick_response'
        )
    );

    register_post_type('wplc_quick_response', $args);
}

add_action('admin_menu', 'wplc_remove_menus');

function wplc_remove_menus() {
    remove_menu_page('edit.php?post_type=wplc_quick_response');
}

function wplc_return_macros($firsttd = 0) {

    $args = array(
        'posts_per_page' => -1,
        'offset' => 0,
        'category' => '',
        'orderby' => 'post_title',
        'order' => 'DESC',
        'include' => '',
        'exclude' => '',
        'meta_key' => '',
        'meta_value' => '',
        'post_type' => 'wplc_quick_response',
        'post_mime_type' => '',
        'post_parent' => '',
        'post_status' => 'publish',
        'suppress_filters' => true);

    $posts_array = get_posts($args);
    //var_dump($posts_array);

    $msg = "<table><tr>";
    if ($firsttd == 0) {
        $msg .= "  <td>" . __("Assign Quick Response", "wplivechat") . "</td>";
    }
    $msg .= "  <td>";
    if ($firsttd == 1) {
        $msg .= __("Assign Quick Response", "wplivechat");
    }
    $msg .= "      <select name='wplc_macros_select' class='wplc_macros_select'>";
    $msg .= "          <option value='0'>" . __("Select", "wplivechat") . "</option>";

    foreach ($posts_array as $post) {

        $msg .= "          <option value='" . $post->ID . "'>" . $post->post_title . "</option>";
    }
    $msg .= "      </select> <small><a href='http://wp-livechat.com/documentation/what-are-quick-responses/?utm_source=plugin&utm_medium=link&utm_campaign=what_are_quick_resposnes' title='What are quick responses?' target='_BLANK'>" . __("What is this?", "wplivechat") . "</a></small>";
    $msg .= "  </td>";
    $msg .= "</tr></table>";
    return $msg;
}
