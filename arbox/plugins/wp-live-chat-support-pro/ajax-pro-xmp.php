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
 
$iterations = 20; 
/* time in microseconds between updating the user on the page within the DB  (lower number = higher resource usage) */
define('WPLC_DELAY_BETWEEN_UPDATES',500000);
/* time in microseconds between long poll loop (lower number = higher resource usage) */
define('WPLC_DELAY_BETWEEN_LOOPS',500000);
/* this needs to take into account the previous constants so that we dont run out of time, which in turn returns a 503 error */
define('WPLC_TIMEOUT',((WPLC_DELAY_BETWEEN_UPDATES + WPLC_DELAY_BETWEEN_LOOPS))*$iterations);

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

$sessiont = time();

// Admin Ajx
wplc_error_log("[".__LINE__."] FILE INITIATED");
$check = 1;
if ($check == 1) {
    
    
    if($_POST['action'] == 'wplc_call_to_xmp_server_visitor'){
        wplc_error_log("[".__LINE__."] NEW XMP REQUEST");
        if (defined('WPLC_TIMEOUT')) { set_time_limit(WPLC_TIMEOUT); } else { set_time_limit(120); }
        
        $i = 1;
        $array = array("check" => false);
        
        
        /* must record the session ID */
        include '/includes/XMPPHP/XMPP.php';
        #Use XMPPHP_Log::LEVEL_VERBOSE to get more logging for error reports
        #If this doesn't work, are you running 64-bit PHP with < 5.2.6?
        $conn = new XMPPHP_XMPP('server', 5222, 'user', 'pass', 'xmpphp', 'gmail.com', $printlog=false, $loglevel=XMPPHP_Log::LEVEL_INFO);        
        wplc_error_log("[".__LINE__."] Connecting to XMP server");
        $conn->connect();
        $conn->processUntil('session_start');
        $conn->presence($status='Controller available.');
        // Enter the chatroom
        $conn->presence(NULL, "available", "chatroom@server/NickName");

        //$conn->message("chatroom@server", "Test!", "groupchat");
        
        
        while($i < $iterations){
            session_write_close();
            
           

            try {
                wplc_error_log($sessiont." - Waiting for events");
                $payloads = $conn->processUntil(array('message', 'presence', 'end_stream', 'session_start'),5);
                foreach($payloads as $event) {
                    $pl = $event[1];
                    wplc_error_log($sessiont." - "."Event: {$event[0]}");
                    switch($event[0]) {
                            case 'message': 
                                    if(!isset($_SESSION['messages'])) $_SESSION['message'] = Array();
                                    $msg = "---------------------------------------------------------------------------------\n{$pl['from']}: {$pl['body']}\n";
                                    wplc_error_log($sessiont." - ".$msg);
                                    $_SESSION['messages'][] = $msg;
                                    flush();
                                    wplc_error_log($sessiont." - "."Message - From: {$pl['from']}, Text: {$pl['body']}");
                                    $conn->message($pl['from'], $body="Thanks for sending me \"{$pl['body']}\".", $type=$pl['type']);
                                    if($pl['body'] == 'quit') $conn->disconnect();
                                    if($pl['body'] == 'break') $conn->send("</end>");
                            break;
                            case 'presence':
                                    wplc_error_log($sessiont." - "."Presence: {$pl['from']} [{$pl['show']}] {$pl['status']}\n");
                            break;
                            case 'session_start':
                                    wplc_error_log($sessiont." - "."Session Start\n");
                                    $conn->getRoster();
                                    $conn->presence($status="Cheese!");
                            break;
                    }
                    
                    
                }
                
            } catch(XMPPHP_Exception $e) {
                wplc_error_log($e->getMessage());
                die($e->getMessage());
            }
            
            if (defined('WPLC_DELAY_BETWEEN_LOOPS')) { usleep(WPLC_DELAY_BETWEEN_LOOPS); } else { usleep(500000); }
            $i++;
        }
        wplc_error_log($sessiont." - "."Closing loop\n");
    }
    

    

}
session_write_close();

die();