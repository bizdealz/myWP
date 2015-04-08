<?php
function wqo_admin_menue(){
  $wqo_icon_url= WQO_BASE_URL . '/images/logo.jpg';
	add_object_page('Quick Shop', 'Quick Shop', 8, __FILE__, 'wqo_setting',$wqo_icon_url);
  add_submenu_page( __FILE__, 'Quick Order','Quick Order', 8, __FILE__,'wqo_setting');  
}

function wqo_install(){
  wqo_setting_reset();
}
function wqo_uninstall(){}

add_action('admin_menu', 'wqo_admin_menue');

?>