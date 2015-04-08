<?php

error_reporting(0);

require_once('../../../../wp-config.php');
require_once(ABSPATH . 'wp-settings.php');

if ( !is_user_logged_in() )
{
	exit;
}
if (!(isset($_GET['id'])))
{
	exit;
}

$id = addslashes($_GET['id']);
$table_chats = $wpdb->prefix . "nchat_chats";

global $wpdb;
$chat = $wpdb->get_row( "SELECT chat FROM $table_chats WHERE id = '$id'", 'ARRAY_A' );

foreach ( json_decode($chat['chat'], 1) as $key => $value)
{
	$time = date("F j, Y, g:i:s a", substr($key, 0, -4));
	$message = str_replace(array("\r\n", "\n", "\r"), '', $value['message']);
	$transcript .= "[$time] $value[by]: $message\n\r";
}

header("Content-type: application/download");
header("Content-Disposition: attachment; filename=chat_".$_GET[id].".txt");
header("Pragma: no-cache");
header("Expires: 0");
print $transcript;

?>