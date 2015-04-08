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
if (!defined('WP_UNINSTALL_PLUGIN'))
    exit();

require_once(str_replace('uninstall.php', 'visitorchat.php', __FILE__));

if (!is_multisite())
{
    uninstallForSite();
}
else
{
    global $wpdb;
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    $original_blog_id = get_current_blog_id();

    foreach ($blog_ids as $blog_id)
    {
        switch_to_blog($blog_id);
        uninstallForSite();
    }
    switch_to_blog($original_blog_id);
}

/**
 * Drops all of VisitorChat's custom database tables and removes its metadata
 * @global wpdb $wpdb
 */
function uninstallForSite()
{
    global $wpdb;
    $tblPrefix = $wpdb->prefix . 'cevc_';
    $sql = array();

    $sql[] = "
DROP  TABLE IF EXISTS `" . $tblPrefix . "messages`;";
    $sql[] = "
DROP  TABLE IF EXISTS `" . $tblPrefix . "discussions`;";
    $sql[] = "
DROP  TABLE IF EXISTS `" . $tblPrefix . "enquiries`;";

    CEVC::i()->queryWithoutForeignKeyChecks($sql);

    CEVC::i()->delete_plugin_options();
}