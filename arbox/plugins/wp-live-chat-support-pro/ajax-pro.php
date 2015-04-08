<?php
@session_start();
@ob_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 604800');
header('Access-Control-Allow-Headers: x-requested-with');
ini_set('html_errors', 0);
define('SHORTINIT', true);


require_once( '../../../wp-load.php' );

$iterations = 55; 
/* time in microseconds between updating the user on the page within the DB  (lower number = higher resource usage) */
define('WPLC_DELAY_BETWEEN_UPDATES',500000);
/* time in microseconds between long poll loop (lower number = higher resource usage) */
define('WPLC_DELAY_BETWEEN_LOOPS',500000);
/* this needs to take into account the previous constants so that we dont run out of time, which in turn returns a 503 error */
define('WPLC_TIMEOUT',(((WPLC_DELAY_BETWEEN_UPDATES + WPLC_DELAY_BETWEEN_LOOPS))*$iterations)/1000000);

define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' ); // full path, no trailing slash


require_once( ABSPATH . WPINC . '/l10n.php' );
require_once( ABSPATH . WPINC . '/formatting.php' );
require_once( ABSPATH . WPINC . '/kses.php' );
require_once( ABSPATH . WPINC . '/default-constants.php' );
require_once( ABSPATH . WPINC . '/link-template.php' );

$plugin_dir = "wp-live-chat-support/languages/";
load_plugin_textdomain( 'wplivechat', false, $plugin_dir );

global $wpdb;
global $wplc_tblname_chats;
global $wplc_tblname_msgs;
$wplc_tblname_chats = $wpdb->prefix . "wplc_chat_sessions";
$wplc_tblname_msgs = $wpdb->prefix . "wplc_chat_msgs";

require_once("functions-pro.php");
require_once("../wp-live-chat-support/functions.php");

/* we're using PHP 'sleep' which may lock other requests until our script wakes up. Call this function to ensure that other requests can run without waiting for us to finish */
session_write_close();


// Admin Ajx

$check = 1;
if ($check == 1) {
    
    
    if($_POST['action'] == 'wplc_admin_long_poll'){
        //wplc_error_log("[".__LINE__."] NEW ADMIN LONG POLL LOOP");

        if (defined('WPLC_TIMEOUT')) { set_time_limit(WPLC_TIMEOUT); } else { set_time_limit(120); }

        
        
        $i = 1;
        while($i <= $iterations){
            ////wplc_error_log("[".__LINE__."] ADMIN LOOP $i");
            session_write_close();

            
            // update chats if they have timed out every x iterations
            if($i %15 == 0) {
                //wplc_error_log("[".__LINE__."] Updating chat statuses $i");
                wplc_update_chat_statuses();
            }
            
            $new_visitor_data = wplc_list_visitors($_POST['wplc_agent_id']);

 
            if($_POST['wplc_list_visitors_data'] == 'false'){
                $old_visitors = false;
            } else {
                $old_visitors = stripslashes($_POST['wplc_list_visitors_data']);
            }
            if($_POST['wplc_update_admin_chat_table'] == 'false'){
                $old_chat_data = false;
            } else {
                $old_chat_data = stripslashes($_POST['wplc_update_admin_chat_table']);
            }
            
            
            if(stripslashes($old_visitors) !== stripslashes($new_visitor_data)){
                //wplc_error_log("[".__LINE__."] Visitor data updated $i");
                //$visitor_table = wplc_list_visitors($_POST['wplc_agent_id']);
                $visitor_table = $new_visitor_data;
                $array = array( "action" => "wplc_list_visitors",
                    "wplc_list_visitors_data" => $visitor_table,
                    "chat_data" => $old_chat_data,
                    "wplc_update_admin_chat_table" => $old_chat_data); 
            } 
            
            $pending = wplc_check_pending_chats();
            $new_chat_data = wplc_list_chats_pro($_POST['wplc_agent_id']);
            
            
            if(stripslashes($new_chat_data) !== stripslashes($old_chat_data)){
                //wplc_error_log("[".__LINE__."] Chat  data updated $i");
                $array['wplc_update_admin_chat_table'] = $new_chat_data;
                $array['pending'] = $pending;
                $array['action'] = "wplc_update_admin_chat";
                $array['wplc_list_visitors_data'] = $old_visitors;
            }
            
            if(isset($array)){
                echo json_encode($array);
                break;
            }
            @ob_end_flush();
            if (defined('WPLC_DELAY_BETWEEN_LOOPS')) { usleep(WPLC_DELAY_BETWEEN_LOOPS); } else { usleep(500000); }
            $i++;
        }
        die();
         
         
    }
         
         
    if($_POST['action'] == "wplc_admin_long_poll_chat"){
        if (defined('WPLC_TIMEOUT')) { set_time_limit(WPLC_TIMEOUT); } else { set_time_limit(120); }
        //wplc_error_log("[".__LINE__."] NEW ADMIN CHAT LOOP");

        $i = 1;
        $array = array();
        while($i <= $iterations){
            session_write_close();
            if(isset($_POST['action_2']) && $_POST['action_2'] == "wplc_long_poll_check_user_opened_chat"){
                $chat_status = wplc_return_chat_status(sanitize_text_field($_POST['cid']));
                if($chat_status == 3){
                    $array['action'] = "wplc_user_open_chat";
                }
            } else {
                $new_chat_status = wplc_return_chat_status(sanitize_text_field($_POST['cid']));
                if($new_chat_status != $_POST['chat_status']){
                    $array['chat_status'] = $new_chat_status;
                    $array['action'] = "wplc_update_chat_status";
                }
                $new_chat_message = wplc_return_admin_chat_messages($_POST['cid']);
                if($new_chat_message){
                    
                    $array['chat_message'] = $new_chat_message;
                    $array['action'] = "wplc_new_chat_message";
                }
            }
            if(wplc_ma_check_if_chat_answered_by_other_agent($_POST['cid'], $_POST['aid']) === true){
                $array['action'] = "wplc_ma_agant_already_answered";
            }
            if($array){
                echo json_encode($array);
                break;
            }
            @ob_end_flush();
            if (defined('WPLC_DELAY_BETWEEN_LOOPS')) { usleep(WPLC_DELAY_BETWEEN_LOOPS); } else { usleep(500000); }
            $i++;

        }
    }
    
    if ($_POST['action'] == "wplc_admin_accept_chat") {
        wplc_admin_accept_chat(sanitize_text_field($_POST['cid']));
    }
    
    if ($_POST['action'] == "wplc_admin_close_chat") {
        $chat_id = sanitize_text_field($_POST['cid']);
        wplc_change_chat_status($chat_id,1);
        echo 'done';                
    }
    
    if ($_POST['action'] == "wplc_admin_send_msg") {
        $chat_id = sanitize_text_field($_POST['cid']);
        $chat_msg = sanitize_text_field($_POST['msg']);
        $wplc_rec_msg = wplc_record_chat_msg_pro("2",$chat_id,$chat_msg);
        if ($wplc_rec_msg) {
            echo 'sent';
        } else {
            echo "There was an error sending your chat message. Please contact support";
        }
    }
    
    
    
    
    //User Ajax
    if($_POST['action'] == 'wplc_call_to_server_visitor'){
        //wplc_error_log("[".__LINE__."] NEW REQUEST");
        if (defined('WPLC_TIMEOUT')) { set_time_limit(WPLC_TIMEOUT); } else { set_time_limit(120); }

        $i = 1;
        $array = array("check" => false);
        
        
        /* must record the session ID */
        
        while($i <= $iterations){
            session_write_close();

            
            if($_POST['cid'] == null || $_POST['cid'] == "null" || $_POST['cid'] == ""){
//                var_dump($_POST);
                $user = "user".time();
                $email = "no email set";
                $cid = wplc_log_user_on_page($user,$email,$_POST['wplcsession']);
                $array['cid'] = $cid;
                $array['status'] = wplc_return_chat_status($cid);
                $array['wplc_name'] = $user;
                $array['wplc_email'] = $email;                
                $array['check'] = true;
                
            } else {
                $new_status = wplc_return_chat_status($_POST['cid']);
                $array['wplc_name'] = sanitize_text_field($_POST['wplc_name']);
                $array['wplc_email'] = sanitize_email($_POST['wplc_email']);
                $array['cid'] = sanitize_text_field($_POST['cid']);
                if($new_status == $_POST['status']){ // if status matches do the following
                    if($_POST['status'] != 2){
                        
                        /* check if session_variable is different? if yes then stop this script completely. */
                        if (isset($_POST['wplcsession']) && $_POST['wplcsession'] != '' && $i > 1) {
                            $wplc_session_variable = $_POST['wplcsession'];
                            $current_session_variable = wplc_return_chat_session_variable($_POST['cid']);
                            ////wplc_error_log("[".$_POST['wplcsession']."] [".__LINE__."] [*$i] Checking against session variable ".$current_session_variable);
                            if ($current_session_variable != "" && $current_session_variable != $wplc_session_variable) {
                                /* stop this script */
                                 //wplc_error_log("[".$_POST['wplcsession']."] [".__LINE__."] [*$i] TERMINATING");
                                $array['status'] = 11;
                                echo json_encode($array);
                                die();
                            }
                        }
                        
                        if ($i == 1) {
                            //wplc_error_log("[".$_POST['wplcsession']."] [".__LINE__."] [*$i] Updating user on page ".$_SERVER['HTTP_REFERER']);
                            wplc_update_user_on_page(sanitize_text_field($_POST['cid']), sanitize_text_field($_POST['status']),$_POST['wplcsession']);
                        }
                        //if (defined('WPLC_DELAY_BETWEEN_UPDATES')) { sleep(WPLC_DELAY_BETWEEN_UPDATES); } else { sleep(3); }
                    }
                    if ($_POST['status'] == 0){ // browsing - user tried to chat but admin didn't answer so turn back to browsing
                        //wplc_error_log("[".__LINE__."] Status 0 - Updating user on page");
                        wplc_update_user_on_page(sanitize_text_field($_POST['cid']), 5,$_POST['wplcsession']);
                        $array['status'] = 5;
                        $array['check'] = true;
                    } else if($_POST['status'] == 3){
                        //wplc_update_user_on_page($_POST['cid'], 3);
                        $messages = wplc_return_user_chat_messages(sanitize_text_field($_POST['cid']));
                        if ($messages){
                            wplc_mark_as_read_user_chat_messages(sanitize_text_field($_POST['cid']));
                            $array['status'] = 3;
                            $array['data'] = $messages;
                            $array['check'] = true;
                        }
                    } 
                    
                } else { // statuses do not match
                    $array['status'] = $new_status;
                    if($new_status == 1){ // completed
                        //wplc_error_log("[".__LINE__."] Status 1 - Updating user on page");
                        wplc_update_user_on_page(sanitize_text_field($_POST['cid']), 8,$_POST['wplcsession']);
                        $array['check'] = true;
                        $array['status'] = 8;
                        $array['data'] =  __("Admin has closed and ended the chat","wplivechat");
                    }
                    else if($new_status == 2){ // pending
                        $array['check'] = true;
                        $array['wplc_name'] = wplc_return_chat_name(sanitize_text_field($_POST['cid']));
                        $array['wplc_email'] = wplc_return_chat_email(sanitize_text_field($_POST['cid']));
                    }
                    else if($new_status == 3){ // active
                        $array['data'] = null;
                        $array['check'] = true;
                        if($_POST['status'] == 5){
                            $messages = wplc_return_chat_messages(sanitize_text_field($_POST['cid']));
                            if ($messages){
                                $array['data'] = $messages;
                            }
                        }
                    }
                    else if($new_status == 6){ // admin requests chat
                        //wplc_error_log("[".__LINE__."] Status6 - Updating user on page");
                        wplc_update_user_on_page(sanitize_text_field($_POST['cid']), 3, $_POST['wplcsession'] );
                        $array['check'] = true;
                        $array['status'] = 3;
                        $array['wplc_name'] = "You";
                    }
                    else if($new_status == 7){ // timed out
                        //wplc_error_log("[".__LINE__."] Status 7 - Updating user on page");
                        wplc_update_user_on_page(sanitize_text_field($_POST['cid']), 5,$_POST['wplcsession']);
                    }
//                    else if($new_status == 8){ // completed but still browsing
//
//                    }
                    else if($new_status == 9){ // user closed chat without inputting or starting a chat
                        $array['check'] = true;
                    } 
                    else if($new_status == 0){ // no answer from admin
                        $array['data'] = __('There is No Answer. Please Try Again Later', 'wplivechat');
                        $array['check'] = true;
                    } 
                    else if($new_status == 10){ // minimized active chat
                        $array['check'] = true;
                        if($_POST['status'] == 5){
                            $messages = wplc_return_chat_messages(sanitize_text_field($_POST['cid']));
                            if ($messages){
                                $array['data'] = $messages;
                            }
                        }
                    }
                }
                
            }
            if($array['check'] == true){
                echo json_encode($array);
                break;
            }
            @ob_end_flush();
            if (defined('WPLC_DELAY_BETWEEN_LOOPS')) { usleep(WPLC_DELAY_BETWEEN_LOOPS); } else { usleep(500000); }
            $i++;
        }
    }
    

    if ($_POST['action'] == "wplc_user_close_chat") {
        if($_POST['status'] == 5){
            wplc_change_chat_status(sanitize_text_field($_POST['cid']),9);
        } else if($_POST['status'] == 3){
            wplc_change_chat_status(sanitize_text_field($_POST['cid']),8);
        }
        die();
    }

    if ($_POST['action'] == "wplc_user_minimize_chat") {
        $chat_id = $_POST['cid'];
        wplc_change_chat_status(sanitize_text_field($_POST['cid']),10);
        die();
    }
    if ($_POST['action'] == "wplc_user_maximize_chat") {
        $chat_id = $_POST['cid'];
        wplc_change_chat_status(sanitize_text_field($_POST['cid']),3);
        die();
    }

    if ($_POST['action'] == "wplc_user_send_msg") {
        $chat_id = sanitize_text_field($_POST['cid']);
        $chat_msg = sanitize_text_field($_POST['msg']);
        //wplc_error_log("[".__LINE__."] SENDING CHAT MSG FROM USER : $chat_id : $chat_msg");
        $wplc_rec_msg = wplc_record_chat_msg("1",$chat_id,$chat_msg);
        if ($wplc_rec_msg) {
            echo 'sent';
            die();
        } else {
            echo "There was an error sending your chat message. Please contact support";
            die();
        }
    }

}
session_write_close();

die();