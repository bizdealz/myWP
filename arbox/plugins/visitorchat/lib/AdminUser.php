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

/**
 * Provides access to currently logged-in users.
 */
class CEVC_AdminUser
{

    /**
     * Name of the key used for the admin-metadata (scoped by site)
     * @var string
     */
    private static $meta_online_key = 'cevc_user_lastactivity';

    /**
     * Holds the current user's id
     * @var int
     */
    private $userId = null;

    /**
     * Holds the current user's email address
     * @var string 
     */
    private $userEmail = null;

    /**
     * Holds the current user's username
     * @var string 
     */
    private $userName = null;

    public function __construct()
    {
        self::$meta_online_key = self::$meta_online_key . get_current_blog_id();
    }

    /**
     * Returns the current user's id
     * @global type $current_user
     * @return int
     */
    public function getUserId()
    {
        if ($this->userId === null)
        {
            global $current_user;
            $current_user = wp_get_current_user();

            $this->userId = $current_user->ID;
        }

        return $this->userId;
    }

    public function getUserEmail()
    {
        if ($this->userEmail === null)
        {
            global $current_user;
            $current_user = wp_get_current_user();

            $this->userEmail = $current_user->user_email;
        }

        return $this->userEmail;
    }

    public function getUserName()
    {
        if ($this->userName === null)
        {
            global $current_user;
            $current_user = wp_get_current_user();

            $this->userName = $current_user->display_name;
        }

        return $this->userName;
    }

    /**
     * Checks if the current user is online
     * @return boolean
     */
    public function getOnlineStatus()
    {
        $lastActivity = strtotime(get_user_meta($this->getUserId(), self::$meta_online_key, true));
        $checkAgainst = strtotime('-5 minutes');

        if ($lastActivity === '')
        {
            return false;
        }

        return $lastActivity >= $checkAgainst;
    }

    /**
     * Records the online-status of the respective user
     * @param boolean $record True if wanting to record the activity, or false to reset it
     * @return boolean
     */
    public function recordActivity($record = true)
    {
        if ($record == true && !is_admin() || CEVC::i()->Session->read(CEVC_SessionKeys::ModeratorOnline) !== true)
        {
            // $record = true --> so it can be cleared upon logout
            return false;
        }

        $now = null;
        if ($record === true)
        {
            $now = date('Y-m-d H:i:s');
        }

        delete_user_meta($this->getUserId(), self::$meta_online_key);
        return add_user_meta($this->getUserId(), self::$meta_online_key, $now, true);
    }

    /**
     * Finds a user by the passed metadata
     * @param string $key The metadata key
     * @param mixed $value The value to find
     * @param string $compare Any comparison operators
     * @return type
     */
    private function findUserByMetaData($key, $value, $compare = '')
    {
        $userQuery = new WP_User_Query(
                array(
            'meta_key' => $key,
            'meta_value' => $value,
            'meta_compare' => $compare,
                )
        );

        $result = $userQuery->get_results();

        return $result;
    }

    /**
     * Cheks if any moderators are currently online
     * @return bool
     */
    public function checkModeratorsOnline()
    {
        if (CEVC::i()->isDemo)
        {
            return true;
        }

        $results = $this->findUserByMetaData(self::$meta_online_key, date('Y-m-d H:i:s', strtotime('-5 minutes')), '>=');
        return !empty($results);
    }

}
