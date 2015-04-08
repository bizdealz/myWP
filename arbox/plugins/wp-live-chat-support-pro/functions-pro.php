<?php


$wplc_basic_plugin_url = get_option('siteurl')."/wp-content/plugins/wp-live-chat-support/";
function wplc_list_visitors($agent_id) {

    global $wpdb;
    global $wplc_tblname_chats;
    
    $wplc_c = 0;
    
    $results = $wpdb->get_results(
        "
	SELECT *
	FROM $wplc_tblname_chats
        WHERE `status` = 5 or `status` = 8 or `status` = 9
        ORDER BY `timestamp` DESC
	"
    );    
    /* come here */
        
    if($results ){
        $table = "<h2>".__('Visitors on site', 'wplivechat')."</h2>";
        $table .= "<div id=\"wplc_visitor_accordion\">";        
        foreach ($results as $result) {
            unset($trstyle);
            unset($actions);
            $wplc_c++;
            $aid = "";
            
            if(is_numeric($agent_id)){
                $aid = "&aid=".$agent_id;
            }

            $url = admin_url( 'admin.php?page=wplivechat-menu&action=rc&cid='.$result->id.$aid);
//            $agent_id = 'x';
            
            if($result->status == 5 ){
                if(is_numeric($agent_id) or $agent_id === true){
                    $actions = "<a href=\"".$url."\" class=\"wplc_open_chat button\" window-title=\"WP_Live_Chat_".$result->id."\">".__("Initiate Chat","wplivechat")."</a>";
                } else {
                    $actions = "<a  class=\"wplc_open_chat\" window-title=\"WP_Live_Chat_".$result->id."\">".__("You must be a chat agent to initiate chats","wplivechat")."</a>";
                }
            } else {
                $actions = "";
            }
           
            $trstyle = "style='background-color:#FFFBE4; height:30px;'";
            $user_data = maybe_unserialize($result->ip);
            $user_ip = $user_data['ip'];
            
            if($user_ip == ""){
                $user_ip = __('IP Address not recorded', 'wplivechat');
            } else {
                $user_ip = "<a href='http://www.ip-adress.com/ip_tracer/" . $user_ip . "' title='".__('Whois for' ,'wplivechat')." ".$user_ip."'>".$user_ip."</a>";
            }                

            if (function_exists("wplc_return_browser_string")) { $browser = wplc_return_browser_string($user_data['user_agent']); } else { $browser = ""; $user_ip = "<em>Please <a href='./update-core.php'>udpate your pro version</a></em>"; }
            if (function_exists("wplc_return_browser_image")) { $browser_image = wplc_return_browser_image($browser,"16"); } else { $browser_image = ""; }
            
            global $wplc_basic_plugin_url;
            
            $trstyle = "";
            
            
            $table .= "
                <h3><i class=\"fa fa-user wplc_visitor_icon wplc_active\"></i>".$result->name."</h3>
                <div class='wplc_single_visitor' id='record_".$result->id."' $trstyle> 
                    <div class='wplc_chat_section'>
                        <div class='wplc_user_image' id='chat_image_".$result->id."'>
                            <img src=\"//www.gravatar.com/avatar/".md5($result->email)."?s=100&d=mm\" />
                        </div>
                        <div class='wplc_user_meta_data'>
                            <div class='wplc_user_name' id='chat_name_".$result->id."'>
                                <h3>".$result->name."</h3>
                                ".$result->email."
                                <div class='wplc_chat_section'>
                                    <div class='wplc_agent_actions'>
                                        $actions
                                    </div>
                                </div>
                            </div>                                
                        </div>    
                    </div>
                    <div class='wplc_chat_section'>
                        <div class='admin_visitor_advanced_info'>
                            <strong>" . __("Site Info", "wplivechat") . "</strong>
                            <hr />
                            <span class='part1'>" . __("Chat initiated on:", "wplivechat") . "</span> <span class='part2'>" . $result->url . "</span>
                        </div>

                        <div class='admin_visitor_advanced_info'>
                            <strong>" . __("Advanced Info", "wplivechat") . "</strong>
                            <hr />
                            <span class='part1'>" . __("Browser:", "wplivechat") . "</span><span class='part2'> $browser <img src='" . $wplc_basic_plugin_url . "/images/$browser_image' alt='$browser' title='$browser' /><br />
                            <span class='part1'>" . __("IP Address:", "wplivechat") . "</span><span class='part2'> ".$user_ip." 
                        </div>
                    </div>                    
                </div>
                    ";
            
            
//            $table .= "
//           
//            <div class='wplc_single_visitor' id='record_'".$result->id." $trstyle>                    
//                <div class='wplc_user_image' id='chat_image_".$result->id."'>
//                    <img src=\"http://www.gravatar.com/avatar/".md5($result->email)."?s=120&d=mm\" />
//                </div>
//                <div class='wplc_user_meta_data'>
//                    <div class='wplc_user_name' id='chat_name_".$result->id."'>
//                        <h3>".$result->name."</h3>
//                    </div>
//                    <div class='wplc_user_email' id='chat_email_".$result->id."'>
//                        <p><a href='mailto:".$result->email."'>".$result->email."</a></p>
//                    </div>                                       
//                    <div class='wplc_actions' id='chat_action_".$result->id."'>
//                        $actions
//                    </div>
//                </div>    
//                <hr>
//                <div class='wplc_user_meta'>
//                    <h4>".__('Visitor Details:', 'wplivechat')."</h4>
//                    <div class='wplc_user_ip'>
//                        <img src='".$wplc_basic_plugin_url."/images/$browser_image' alt='$browser' title='$browser' /><a href='http://www.ip-adress.com/ip_tracer/".$user_ip."' target='_BLANK'>".$user_ip."</a>
//                    </div> 
//                </div>
//                <hr>
//                <div class='wplc_page_meta'>
//                    <h4>".__('Browsing the following page:', 'wplivechat')."</h4>
//                    <div class='wplc_current_page' id='chat_url_".$result->id."'>
//                        <a href='".$result->url."' target='_BLANK'>".$result->url."</a>
//                    </div>
//                </div>
//            </div>";

        }
        $table.= "</div>";
    } else {
        $table= "<p>".__("No visitors on-line at the moment","wplivechat")."</p>";
        
    }
    
    
    return $table;

}




// pro

function wplc_list_chats_pro($agent_id) {

    global $wpdb;
    global $wplc_tblname_chats;
    $wplc_c = 0;
    $results = $wpdb->get_results(
        "
	SELECT *
	FROM $wplc_tblname_chats
        WHERE `status` = 3 OR `status` = 2
        ORDER BY `timestamp` ASC

	"
    );
    
//    $results = $wpdb->get_results(
//        "
//	SELECT *
//	FROM $wplc_tblname_chats
//        WHERE `status` = 7
//        ORDER BY `timestamp` ASC
//
//	"
//    );
    
    
    $table = "<div class='wplc_chats_container'>";    
            
    if (!$results) {
        $table.= "<p>".__("No chat sessions available at the moment","wplivechat")."</p>";
    } else {
        $table .= "<h2>".__('Active Chats', 'wplivechat')."</h2>";
        foreach ($results as $result) {
            // youre working here
            unset($trstyle);
            unset($actions);
            unset($icon);
            
            global $wplc_basic_plugin_url;
            $user_data = maybe_unserialize($result->ip);
            $user_ip = $user_data['ip'];
            if (function_exists("wplc_return_browser_string")) { $browser = wplc_return_browser_string($user_data['user_agent']); } else { $browser = ""; $user_ip = "<em>Please <a href='./update-core.php'>udpate your pro version</a></em>"; }
            if (function_exists("wplc_return_browser_image")) { $browser_image = wplc_return_browser_image($browser,"16"); } else { $browser_image = ""; }
            
            $wplc_c++;
            $aid = "";
            
            if(is_numeric($agent_id)){
                $aid = "&aid=".$agent_id;
            }
            
            if($user_ip == ""){
                $user_ip = __('IP Address not recorded', 'wplivechat');
            } else {
                $user_ip = "<a href='http://www.ip-adress.com/ip_tracer/" . $user_ip . "' title='".__('Whois for' ,'wplivechat')." ".$user_ip."'>".$user_ip."</a>";
            }
            
            if ($result->status == 2) {
                if(is_numeric($agent_id) or $agent_id === true){
                    $url = admin_url( 'admin.php?page=wplivechat-menu&action=ac&cid='.$result->id.$aid);
                    $actions = "<p><a href=\"".$url."\" class=\"wplc_open_chat button button-primary\" window-title=\"WP_Live_Chat_".$result->id."\">".__("Accept Chat", "wplivechat")."</a></p>";
                    $trstyle = "style='background-color:#FFFBE4; height:30px;'";
                    $icon = "<i class=\"fa fa-phone wplc_pending\" title='".__('Incoming Chat', 'wplivechat')."' alt='".__('Incoming Chat', 'wplivechat')."'></i><div class='wplc_icon_message'>".__('You have an incoming chat.', 'wplivechat')."</div>";
                } else {
                    $actions = "<p></p>";
                    $trstyle = "style='background-color:#FFFBE4; height:30px;'";
                    $icon = "<i class=\"fa fa-times-circle wplc_closed\" title='".__('You must be a chat agent to answer chats', 'wplivechat')."' alt='".__('You must be a chat agent to answer chats', 'wplivechat')."'></i><div class='wplc_icon_message'>".__('You must be a chat agent to answer chats', 'wplivechat')."</div>";
                }
            }
            if ($result->status == 3) {
                if(is_numeric($agent_id) or $agent_id === true){
                    $url = admin_url( 'admin.php?page=wplivechat-menu&action=ac&cid='.$result->id.$aid);
                    $actions = "<p><a href=\"".$url."\" class=\"wplc_open_chat button button-primary\" window-title=\"WP_Live_Chat_".$result->id."\">".__("Open Chat Window", "wplivechat")."</a></p>";
                    $trstyle = "style='background-color:#F7FCFE; height:30px;'";
                    $icon = "<i class=\"fa fa-check-circle wplc_active\" title='".__('Chat Active', 'wplivechat')."' alt='".__('Chat Active', 'wplivechat')."'></i><div class='wplc_icon_message'>".__('This chat is active', 'wplivechat')."</div>";                        
                    if(isset($result->agent_id) && is_numeric($agent_id) && $result->agent_id != $agent_id && $result->agent_id != 0){
                        $actions = "<p>".__("Chat has been answered by another agent", "wplivechat")."</p>";
                        $icon = "<i class=\"fa fa-times-circle wplc_closed\" title='".__('Chat answered by another agent', 'wplivechat')."' alt='".__('Chat answered by another agent', 'wplivechat')."'></i>";                        
                    }
                } else {
//                    $actions = "<a class=\"wplc_open_chat\" window-title=\"WP_Live_Chat_".$result->id."\">".__("Chat has been Accepted By Chat Agent ", "wplivechat")."</a>";
                    $actions = "";
                    $agent_details = "There is a chat agent (Another guy)";
                    $trstyle = "style='background-color:#F7FCFE; height:30px;'";     
                }
            }
            
            $trstyle = "";
            
            $table .= "
                <div class='wplc_single_chat' id='record_'".$result->id." $trstyle> 
                    <div class='wplc_chat_section'>
                        <div class='wplc_user_image' id='chat_image_".$result->id."'>
                            <img src=\"//www.gravatar.com/avatar/".md5($result->email)."?s=100&d=mm\" />
                        </div>
                        <div class='wplc_user_meta_data'>
                            <div class='wplc_user_name' id='chat_name_".$result->id."'>
                                <h3>".$result->name.$icon."</h3>
                                ".$result->email."
                            </div>                                
                        </div>    
                    </div>
                    <div class='wplc_chat_section'>
                        <div class='admin_visitor_advanced_info'>
                            <strong>" . __("Site Info", "wplivechat") . "</strong>
                            <hr />
                            <span class='part1'>" . __("Chat initiated on:", "wplivechat") . "</span> <span class='part2'>" . $result->url . "</span>
                        </div>

                        <div class='admin_visitor_advanced_info'>
                            <strong>" . __("Advanced Info", "wplivechat") . "</strong>
                            <hr />
                            <span class='part1'>" . __("Browser:", "wplivechat") . "</span><span class='part2'> $browser <img src='" . $wplc_basic_plugin_url . "/images/$browser_image' alt='$browser' title='$browser' /><br />
                            <span class='part1'>" . __("IP Address:", "wplivechat") . "</span><span class='part2'> ".$user_ip." 
                        </div>
                    </div>
                    <div class='wplc_chat_section'>
                        <div class='wplc_agent_actions'>
                            $actions
                        </div>
                    </div>
                </div>
                    ";
        }
    }
    $table .= "</div>";

    return $table;
}

function wplc_send_offline_message($name,$email,$msg,$cid) {
//    $wplc_pro_settings = get_option("WPLC_PRO_SETTINGS");
//    $email_address = $wplc_pro_settings['wplc_pro_chat_email_address'];
//    if (!$email_address || $email_address == "") { $email_address = get_option('admin_email'); }

    $subject = __("WP Live Chat Support - Offline Message from ", "wplivechat")."$name";
    $msg = __("Name", "wplivechat").": $name \n".__("Email", "wplivechat").": $email\n".__("Message", "wplivechat").": $msg\n\n".__("Via WP Live Chat Support", "wplivechat");

    wplc_mail($email,$name, $subject, $msg);    
    //mail($email_address, $subject, $msg);

}

function wplc_record_chat_msg_pro($from,$cid,$msg) {
    global $wpdb;
    global $wplc_tblname_msgs;

    if ($from == "1") {
        $fromname = wplc_return_chat_name($cid);
        $orig = '2';
    }
    else {
        $fromname = $_POST['admin_name'];
        $orig = '1';
    }

    $ins_array = array(
        'chat_sess_id' => $cid,
        'timestamp' => date("Y-m-d H:i:s"),
        'from' => $fromname,
        'msg' => $msg,
        'status' => 0,
        'originates' => $orig
    );
    $rows_affected = $wpdb->insert( $wplc_tblname_msgs, $ins_array );

    wplc_update_active_timestamp($cid);
    wplc_change_chat_status($cid,3);
    return true;
}


function wplc_return_from_name($user_id) {
        $user = get_user_by('id', $user_id);
        return $user->display_name;
}


function wplc_pro_notify_via_email() {
    $wplc_pro_settings = get_option("WPLC_PRO_SETTINGS");
    if (isset($wplc_pro_settings['wplc_pro_chat_email_address'])) { $email_address = $wplc_pro_settings['wplc_pro_chat_email_address']; } else { $email_address = ""; }
    if (!$email_address || $email_address == "") { $email_address = get_option('admin_email'); }
//    $chat_noti = $wplc_pro_settings['wplc_pro_chat_notification'];
    if (isset($wplc_pro_settings['wplc_pro_chat_notification']) && $wplc_pro_settings['wplc_pro_chat_notification'] == "yes") {
        $subject = __("Alert: Someone wants to chat with you on ", "wplivechat").get_option('blogname')."";
        $msg = __("Someone wants to chat with you on your website", "wplivechat")." ".get_option('blogname').") \n\n".__("Log in", "wplivechat").": ".get_option('home')."/wp-login.php";
        //$headers = 'From: '.$email_address.' <'.$email_address.'>';
        //mail($email_address, $subject, $msg);
        wplc_mail($email_address,"WP Live Chat Support", $subject, $msg);
    }
    return true;
}
function wplc_mail_body($msg){
    $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $body .= '<html xmlns="http://www.w3.org/1999/xhtml">';
    $body .= '<head>';
    $body .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
    $body .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $body .= '</head>';
    $body .= '<body>';
    $body .= '<p>';
    $body .= $msg;
    $body .= '</p>';
    $body .= '</body>';
    $body .= '</html>';
    return $body;
}
function wplc_mail($reply_to,$reply_to_name,$subject,$msg) {
    
    $wplc_pro_settings = get_option("WPLC_PRO_SETTINGS");
    
    if(isset($wplc_pro_settings['wplc_pro_chat_email_address'])){
        $email_address = $wplc_pro_settings['wplc_pro_chat_email_address'];
    } else {
        $email_address = get_option('admin_email');
    }
    
    $email_address = explode(',', $email_address);  
    
    if(get_option("wplc_mail_type") == "wp_mail"){
        $headers[] = 'Content-type: text/html';
        $headers[] = 'Reply-To: '.$reply_to_name.'<'.$reply_to.'>';
        if($email_address){
            foreach($email_address as $email){
                /* Send offline message to each email address */
                if (!wp_mail($email, $subject, $msg, $headers)) {
                    $handle = fopen("wp_livechat_error_log.txt", 'a');
                    $error = date("Y-m-d H:i:s") . " WP-Mail Failed to send \n";
                    @fwrite($handle, $error);
                }
            }
        }
//        $to = $wplc_pro_settings['wplc_pro_chat_email_address'];
        return;
    } else {
    
        require 'phpmailer/PHPMailerAutoload.php';
        $wplc_pro_settings = get_option("WPLC_PRO_SETTINGS");
        $host = get_option('wplc_mail_host');
        $port = get_option('wplc_mail_port');
        $username = get_option("wplc_mail_username");
        $password = get_option("wplc_mail_password");
        if($host && $port && $username && $password){
            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = $host;
            //Set the SMTP port number - likely to be 25, 26, 465 or 587
            $mail->Port = $port;
            //Set the encryption system to use - ssl (deprecated) or tls
            if($port == "587"){
                $mail->SMTPSecure = 'tls';
            } else if($port == "465"){
                $mail->SMTPSecure = 'ssl';
            }
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = $username;
            //Password to use for SMTP authentication
            $mail->Password = $password;
            //Set who the message is to be sent from
            $mail->setFrom($reply_to, $reply_to_name);
            //Set who the message is to be sent to
            $mail->addAddress($wplc_pro_settings['wplc_pro_chat_email_address']);
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $body = wplc_mail_body($msg);
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            $mail->AltBody = $msg;


            //send the message, check for errors
            if (!$mail->send()) {
                $handle = fopen("wp_livechat_error_log.txt", 'a');
                $error = date("Y-m-d H:i:s")." ".$mail->ErrorInfo." \n";
                @fwrite($handle, $error); 
            }
            return;
        }
    }
}
function wplc_delete_history(){
    global $wpdb;
    global $wplc_tblname_chats;
    $wpdb->query("TRUNCATE TABLE $wplc_tblname_chats");
}
function wplc_pro_get_admin_picture(){
    $pro_settings = get_option("WPLC_PRO_SETTINGS");
    if($pro_settings['wplc_chat_pic']){
        return urldecode($pro_settings['wplc_chat_pic']);
    }
}
function wplc_get_visitors_ids(){
    global $wpdb;
    global $wplc_tblname_chats;
    $sql = "SELECT `id` FROM `$wplc_tblname_chats` WHERE `status` = 5 ORDER BY `timestamp` DESC";
    $results = $wpdb->get_results($sql);
    if($results){
        return $results;
    } else {
        return false;
    }
}


///// Multi Agents functions

function wplc_ma_action_callback(){
    //var_dump($_POST);
    $check = check_ajax_referer( 'wplc', 'security' );
    
    if($check == 1){
        
    }
    die();
}

function wplc_ma_set_user_as_agent( $user_id ) {
    
    if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
    if(isset($_POST['wplc_ma_agent'])){
        update_user_meta( $user_id, 'wplc_ma_agent', $_POST['wplc_ma_agent']);
    } else {
        delete_user_meta( $user_id, 'wplc_ma_agent');
    }
    
    if ($_POST['wplc_ma_agent'] == '1') {
        $wplc_ma_user = new WP_User( $user_id );
        $wplc_ma_user->add_cap( 'wplc_ma_agent' );
        update_user_meta($user_id, "wplc_chat_agent_online", time());
    } else {
        $wplc_ma_user = new WP_User( $user_id );
        $wplc_ma_user->remove_cap( 'wplc_ma_agent' );
        delete_user_meta($user_id, "wplc_ma_agent");
        delete_user_meta($user_id, "wplc_chat_agent_online");
    }
}


function wplc_ma_custom_user_profile_fields($user) {
    $wplc_pro_settings = get_option('WPLC_PRO_SETTINGS');
    if(isset($wplc_pro_settings['wplc_make_agent']) && $wplc_pro_settings['wplc_make_agent'] == 1){
        ?>
        <table class="form-table">
            <tr>
                <th>
                    <label for="wplc_ma_agent"><?php _e('Chat Agent', 'wplivechat'); ?></label>
                </th>
                <td>
                    <label for="wplc_ma_agent">
                    <input name="wplc_ma_agent" type="checkbox" id="wplc_ma_agent" value="1" <?php if (esc_attr( get_the_author_meta( 'wplc_ma_agent', $user->ID ) ) == "1") { echo "checked=\"checked\""; } ?>>
                    <?php _e("Make this user a chat agent","wplivechat"); ?></label>
                </td>
            </tr>
        </table>
        <?php
    } else {
        if(current_user_can('manage_options', array(null))){
        ?>
        <table class="form-table">
            <tr>
                <th>
                    <label for="wplc_ma_agent"><?php _e('Chat Agent', 'wplivechat'); ?></label>
                </th>
                <td>
                    <label for="wplc_ma_agent">
                    <input name="wplc_ma_agent" type="checkbox" id="wplc_ma_agent" value="1" <?php if (esc_attr( get_the_author_meta( 'wplc_ma_agent', $user->ID ) ) == "1") { echo "checked=\"checked\""; } ?>>
                    <?php _e("Make this user a chat agent","wplivechat"); ?></label>
                </td>
            </tr>
        </table>
        <?php
        } else {
            ?>
            <table class="form-table">
                <tr>
                    <th>
                        <label for="wplc_ma_agent"><?php _e('Chat Agent', 'wplivechat'); ?></label>
                    </th>
                    <td>
                        <?php 
                            echo "<p>".__("Your user role does not allow you to make yourself a chat agent.","wplivechat")."</p>"; 
                            echo "<p>".__("Please contact the administrator of this website to change this.", "wplivechat")."</p>";
                        ?>                    
                    </td>
                </tr>
            </table>
            <?php
        }
    }
}


function wplc_set_admin_to_access_chat() {

    
    $admins = get_role( 'administrator' );
    //$admins->add_cap( 'wplc_chat_agent' ); 

}
function wplc_ma_set_agents_online($user_id){
    
    if (esc_attr( get_the_author_meta( 'wplc_ma_agent', $user_id ) ) == "1"){
        
        update_user_meta($user_id, "wplc_chat_agent_online", time());
    }
    $users = get_users(array(
        'meta_key'=> 'wplc_chat_agent_online',
    ));
    foreach($users as $user){
        $time = get_user_meta($user->ID, "wplc_chat_agent_online", true);
        $diff = time() - $time;
        if($diff > 65){
            delete_user_meta($user->ID, "wplc_chat_agent_online");
        }
    }
}

function wplc_ma_remove_agents_online($user_id){
    delete_user_meta($user_id, "wplc_chat_agent_online");
}

function wplc_ma_agent_logout(){
    $user_id = get_current_user_id();
//    $user_array = unserialize(get_transient('wplc_online_agents'));
//    $key = array_search($user_id, $user_array);
//    unset($user_array[$key]);
//    set_transient('wplc_online_agents', serialize($user_array), 20);
    delete_user_meta($user_id, "wplc_chat_agent_online");
}
function wplc_ma_is_agent_online(){
    $check = get_users(array(
        'meta_key'=> 'wplc_chat_agent_online',
    ));
    if($check){
        return true;
    } else {
        return false;
    }
}
function wplc_ma_total_agents_online(){
    $users = get_users(array(
        'meta_key'=> 'wplc_chat_agent_online',
    ));
    return count($users);
}
//echo wplc_ma_total_agents_online();
function wplc_ma_online_agents(){ ?>
    <style >
        .wplc_circle{
            width: 10px !important;
            height: 10px !important;
            display: inline-block !important;
            border-radius: 100% !important;
            margin-right: 5px !important;
        }
        .wplc_red_circle{
            background: red;
        }
        .wplc_green_circle{
            background:rgb(103, 213, 82);
        }
    </style>
    <?php 
    if(wplc_ma_is_agent_online()){
        $online_now = wplc_ma_total_agents_online();
        $circle_class = "wplc_green_circle";
        if($online_now == 1){
            $chat_agents = __('Chat Agent Online', 'wplivechat');
        } else {
            $chat_agents = __('Chat Agents Online', 'wplivechat');
        }
    } else {
        $online_now = 0;
        $circle_class = "wplc_red_circle";
        $chat_agents = __('Chat Agents Online', 'wplivechat');
    }

    global $wp_admin_bar;
    $wp_admin_bar->add_menu( array(
        'id' => 'wplc_ma_online_agents',
        'title' => '<span class="wplc_circle '.$circle_class.'"></span>'.$online_now.' '.$chat_agents,
        'href' => false
    ) );
    if($online_now > 0){
        $user_array =  get_users(array(
            'meta_key'=> 'wplc_chat_agent_online',
        ));
        foreach($user_array as $user){
            
            $wp_admin_bar->add_menu( array(
                'id' => 'wplc_user_online_'.$user->ID,
                'parent' => 'wplc_ma_online_agents',
                'title' => $user->display_name,
                'href' => false,
            ) );
        }
    }
}
function wplc_ma_head(){
    $wplc_pro_settings = get_option("WPLC_PRO_SETTINGS");

    if(!isset($wplc_pro_settings['wplc_auto_online'])) {
    ?>
    <script>
        jQuery(document).ready(function() {
            /* Going online functionality used to be here */
            var wplc_ma_set_transient = null;

            wplc_ma_set_transient = setInterval(function (){wplc_ma_update_agent_transient();}, 60000);
            wplc_ma_update_agent_transient();

            function wplc_ma_update_agent_transient() {
                var data = {
                    action: 'wplc_ma_set_transient',
                    security: wplc_admin_strings.nonce,
                    user_id:  wplc_admin_strings.user_id
                };
                jQuery.post(ajaxurl, data, function(response) {
                });
            }
        });
    </script>
    <?php
    }
}
function wplc_ma_update_agent_id($cid, $aid){
    global $wpdb;
    global $wplc_tblname_chats;
    $sql = "SELECT * FROM `$wplc_tblname_chats` WHERE `id` = '$cid'";
    $result = $wpdb->get_row($sql); 
    if($result->status != 3){
        $update = "UPDATE `$wplc_tblname_chats` SET `agent_id` = '$aid' WHERE `id` = '$cid'";
        $wpdb->query($update);
    }
}

function wplc_ma_check_if_user_is_agent(){
    $user_id = get_current_user_id();
    if (esc_attr(get_the_author_meta('wplc_ma_agent', $user_id ) ) == "1"){
        return $user_id;
    } else {
        return "not_user_agent";
    }
}
function wplc_ma_check_if_chat_answered_by_other_agent($cid, $aid){
    global $wpdb;
    global $wplc_tblname_chats;
    $sql = "SELECT * FROM `$wplc_tblname_chats` WHERE `id` = '$cid'";
    $result = $wpdb->get_row($sql); 
    if(intval($result->agent_id) == intval($aid)){
        return false;
    } else {
        return true;
    }
}

function wplc_pro_admin_display_offline_messages() {

    global $wpdb;
    global $wplc_tblname_offline_msgs;

    echo "<form method='POST'>
        <table class=\"wp-list-table widefat fixed \" cellspacing=\"0\">
            <thead>
                <tr>
                    <th class='manage-column column-id'><span>" . __("Date", "wplivechat") . "</span></th>
                    <th scope='col' id='wplc_name_colum' class='manage-column column-id'><span>" . __("Name", "wplivechat") . "</span></th>
                    <th scope='col' id='wplc_email_colum' class='manage-column column-id'>" . __("Email", "wplivechat") . "</th>
                    <th scope='col' id='wplc_message_colum' class='manage-column column-id'>" . __("Message", "wplivechat") . "</th>
                </tr>
            </thead>
            <tbody id=\"the-list\" class='list:wp_list_text_link'>";

    $sql = "
            SELECT *
            FROM $wplc_tblname_offline_msgs                    
            ORDER BY `timestamp` DESC
                ";

    $results = $wpdb->get_results($sql);

    if (!$results) {
        echo "<tr><td></td><td>" . __("You have not received any offline messages.", "wplivechat") . "</td></tr>";
    } else {
        foreach ($results as $result) {
            echo "<tr id=\"record_" . $result->id . "\">";
            echo "<td class='chat_id column-chat_d'>" . $result->timestamp . "</td>";
            echo "<td class='chat_name column_chat_name' id='chat_name_" . $result->id . "'><img src=\"//www.gravatar.com/avatar/" . md5($result->email) . "?s=30\" /> " . $result->name . "</td>";
            echo "<td class='chat_email column_chat_email' id='chat_email_" . $result->id . "'><a href='mailto:" . $result->email . "' title='Email " . ".$result->email." . "'>" . $result->email . "</a></td>";
            echo "<td class='chat_name column_chat_url' id='chat_url_" . $result->id . "'>" . $result->message . "</td>";
            echo "</tr>";
        }
    }

    echo "
            </tbody>
        </table></form>";
}

function wplc_pro_store_offline_message($name, $email, $message){
    global $wpdb;
    global $wplc_tblname_offline_msgs;
    
    $wplc_settings = get_option('WPLC_SETTINGS');
        
    if(isset($wplc_settings['wplc_record_ip_address']) && $wplc_settings['wplc_record_ip_address'] == 1){
        $offline_ip_address = $_SERVER['REMOTE_ADDR'];
    } else {
        $offline_ip_address = "";
    }

    $ins_array = array(
        'timestamp' => current_time('mysql'),
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'ip' => $offline_ip_address,
        'user_agent' => $_SERVER['HTTP_USER_AGENT']
    );
    
    $rows_affected = $wpdb->insert( $wplc_tblname_offline_msgs, $ins_array );
}

function wplc_return_animations(){
    
    $wplc_pro_options = get_option('WPLC_PRO_SETTINGS');
    
    $wplc_settings = get_option("WPLC_SETTINGS");
    
    if ($wplc_settings["wplc_settings_align"] == 1) {
        $original_pos = "bottom_left";
        //$wplc_box_align = "left:100px; bottom:0px;";
        $wplc_box_align = "bottom:0px;";
    } else if ($wplc_settings["wplc_settings_align"] == 2) {
        $original_pos = "bottom_right";
        //$wplc_box_align = "right:100px; bottom:0px;";
        $wplc_box_align = "bottom:0px;";
    } else if ($wplc_settings["wplc_settings_align"] == 3) {
        $original_pos = "left";
//        $wplc_box_align = "left:0; bottom:100px;";
        $wplc_box_align = " bottom:100px;";
        $wplc_class = "wplc_left";
    } else if ($wplc_settings["wplc_settings_align"] == 4) {
        $original_pos = "right";
//        $wplc_box_align = "right:0; bottom:100px;";
        $wplc_box_align = "bottom:100px;";
        $wplc_class = "wplc_right";
    }

    $animation_data = array();

    if(isset($wplc_pro_options['wplc_animation']) && $wplc_pro_options['wplc_animation'] == 'animation-1'){

        if($original_pos == 'bottom_right'){
            $wplc_starting_point = 'margin-bottom: -350px; right: 100px;';
            $wplc_animation = 'animation-1';
        } else if ($original_pos == 'bottom_left'){
            $wplc_starting_point = 'margin-bottom: -350px; left: 100px;';
            $wplc_animation = 'animation-1';
        } else if ($original_pos == 'left'){
            $wplc_starting_point = 'margin-bottom: -350px; left: 0px;';
            $wplc_box_align = "left:0; bottom:100px;";
            $wplc_animation = 'animation-1';
        } else if ($original_pos == 'right'){
            $wplc_starting_point = 'margin-bottom: -350px; right: 0px;';
            $wplc_animation = 'animation-1';
            $wplc_box_align = "right:0; bottom:100px;";
        }

        $animation_data['animation'] = $wplc_animation;
        $animation_data['starting_point'] = $wplc_starting_point;
        $animation_data['box_align'] =  $wplc_box_align;

    } else if (isset($wplc_pro_options['wplc_animation']) && $wplc_pro_options['wplc_animation'] == 'animation-2'){

        if($original_pos == 'bottom_right'){
            $wplc_starting_point = 'margin-bottom: 0px; right: -300px;';
            $wplc_animation = 'animation-2-br';
        } else if ($original_pos == 'bottom_left'){
            $wplc_starting_point = 'margin-bottom: 0px; left: -300px;';
            $wplc_animation = 'animation-2-bl';
        } else if ($original_pos == 'left'){
            $wplc_starting_point = 'margin-bottom: 0px; left: -999px;';
            $wplc_animation = 'animation-2-l';
        } else if ($original_pos == 'right'){
            $wplc_starting_point = 'margin-bottom: 0px; right: -999px;';               
            $wplc_animation = 'animation-2-r';
        }

        $animation_data['animation'] = $wplc_animation;
        $animation_data['starting_point'] = $wplc_starting_point;
        $animation_data['box_align'] =  $wplc_box_align;

    } else if (isset($wplc_pro_options['wplc_animation']) && $wplc_pro_options['wplc_animation'] == 'animation-3'){

        $wplc_animation = 'animation-3';

        if($original_pos == 'bottom_right'){
            $wplc_starting_point = 'margin-bottom: 0; right: 100px; display: none;';
        } else if ($original_pos == 'bottom_left'){
            $wplc_starting_point = 'margin-bottom: 0px; left: 100px; display: none;';
        } else if ($original_pos == 'left'){
            $wplc_starting_point = 'margin-bottom: 100px; left: 0px; display: none;';
        } else if ($original_pos == 'right'){
            $wplc_starting_point = 'margin-bottom: 100px; right: 0px; display: none;';
        }

        $animation_data['animation'] = $wplc_animation;
        $animation_data['starting_point'] = $wplc_starting_point;
        $animation_data['box_align'] =  $wplc_box_align;

    } else if (isset($wplc_pro_options['wplc_animation']) && $wplc_pro_options['wplc_animation'] == 'animation-4'){
        // Dont use an animation

        $wplc_animation = "animation-4";

        if($original_pos == 'bottom_right'){
            $wplc_starting_point = 'margin-bottom: 0; right: 100px; display: none;';
        } else if ($original_pos == 'bottom_left'){
            $wplc_starting_point = 'margin-bottom: 0px; left: 100px; display: none;';
        } else if ($original_pos == 'left'){
            $wplc_starting_point = 'margin-bottom: 100px; left: 0px; display: none;';
        } else if ($original_pos == 'right'){
            $wplc_starting_point = 'margin-bottom: 100px; right: 0px; display: none;';
        }

        $animation_data['animation'] = $wplc_animation;
        $animation_data['starting_point'] = $wplc_starting_point;
        $animation_data['box_align'] =  $wplc_box_align;

    } else {
        
        if($original_pos == 'bottom_right'){
            $wplc_starting_point = 'margin-bottom: 0; right: 100px; display: none;';
        } else if ($original_pos == 'bottom_left'){
            $wplc_starting_point = 'margin-bottom: 0px; left: 100px; display: none;';
        } else if ($original_pos == 'left'){
            $wplc_starting_point = 'margin-bottom: 100px; left: 0px; display: none;';
        } else if ($original_pos == 'right'){
            $wplc_starting_point = 'margin-bottom: 100px; right: 0px; display: none;';
        }
        
        $wplc_animation = 'none';
        
        $animation_data['animation'] = $wplc_animation;
        $animation_data['starting_point'] = $wplc_starting_point;
        $animation_data['box_align'] =  $wplc_box_align;
    }

    return $animation_data;
}

function wplc_display_chat_contents(){
    
    $post_id = get_the_ID();

    $wplc_pro_settings = get_option("WPLC_PRO_SETTINGS");

    $show_chat_contents = false;
    
    if (isset($wplc_pro_settings['wplc_include_on_pages'])) {
        $include_on_pages = $wplc_pro_settings['wplc_include_on_pages'];
    } else {
        $include_on_pages = "";
    }
    if (isset($wplc_pro_settings['wplc_exclude_from_pages'])) {
        $exclude_from_pages = $wplc_pro_settings['wplc_exclude_from_pages'];
    } else {
        $exclude_from_pages = "";
    }
    if($include_on_pages == "" && $exclude_from_pages == ""){
        $show_chat_contents = true;
    } else {
        if($include_on_array == null){
            $include_on_array = explode(',', $include_on_pages);
            foreach($include_on_array as $include){   
                if(intval(trim($include)) === $post_id){
                    $show_chat_contents = true;
                } 
            }
        } else {
            $exclude_on_array = explode(',', $exclude_from_pages);
            foreach($exclude_on_array as $exclude){
                if(intval($exclude) != $post_id){
                    $show_chat_contents = true;
                } 
            }
        }
        
    }
    return $show_chat_contents;
}

function wplc_is_user_banned(){
    $banned_ip = get_option('WPLC_BANNED_IP_ADDRESSES');
    if($banned_ip){
        $banned_ip = maybe_unserialize($banned_ip);
        $banned = 0;
        foreach($banned_ip as $ip){
            if(isset($_SERVER['REMOTE_ADDR'])){
                if($ip == $_SERVER['REMOTE_ADDR']){
                    $banned++;
                }
            } else {
                $banned = 0;
            }
        }
    } else {
        $banned = 0;
    }
    return $banned;
}

function wplc_hide_chat_when_offline(){
    $wplc_settings = get_option("WPLC_SETTINGS");

    $hide_chat = 0;
    if (isset($wplc_settings['wplc_hide_when_offline']) && $wplc_settings['wplc_hide_when_offline'] == 1) {
        /* Hide the window when its offline */
        $wplc_is_chat_online = wplc_ma_is_agent_online();
        if (!$wplc_is_chat_online) {
            $hide_chat++;
        }
    } else {
        $hide_chat = 0;
    }
    return $hide_chat;
}