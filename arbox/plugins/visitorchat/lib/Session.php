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
 * Session Management Class
 */
class CEVC_SessionEngine
{

    private static $session_prefix = 'cevc_session_';

    /**
     * Ensures sessions are scoped according to the current blog
     */
    public function __construct()
    {
        self::$session_prefix = self::$session_prefix . get_current_blog_id();
    }

    /**
     * Starts a session if one does not already exists and clears the 
     * VisitorChat session-key after 30 minutes of inactivity.
     */
    public function start()
    {
        if (!session_id())
        {
            session_start();
        }

        if (CEVC::i()->Session->check('LASTACTIVITY') && (time() - CEVC::i()->Session->read('LASTACTIVITY') > 1800))
        {
            CEVC::i()->Session->clear();
        }

        CEVC::i()->Session->write('LASTACTIVITY', time());
    }

    /**
     * Clears the entire VisitorChat session (or the entire session)
     * @param boolean $entireSession True if the entire session should be destroyed
     */
    public function clear($entireSession = false)
    {
        if ($entireSession === true)
        {
            session_destroy();
        }
        else
        {
            $_SESSION[self::$session_prefix] = array();
        }
    }

    /**
     * Checks a given key for its existence in the session
     * @param string $key
     * @return boolean
     */
    public function check($key)
    {
        return CEVC_Utilities::check($_SESSION, self::$session_prefix . '.' . $key);
    }

    /**
     * Deletes the respective key from the session
     * @param string $key
     */
    public function delete($key)
    {
        CEVC_Utilities::del($_SESSION, self::$session_prefix . '.' . $key);
    }

    /**
     * Returns the respective key from the session if it exists.
     * Otherwise, it returns null.
     * @param string $key
     * @return mixed
     */
    public function read($key = null)
    {
        if ($key === null)
        {
            return $_SESSION;
        }

        return CEVC_Utilities::get($_SESSION, self::$session_prefix . '.' . $key);
    }

    /**
     * Writes to the specified session key
     * @param string $key
     * @param mixed $value
     */
    public function write($key, $value)
    {
        CEVC_Utilities::set($_SESSION, self::$session_prefix . '.' . $key, $value);
    }

    /**
     * Sets a flash message
     * @param string $message
     * @return void
     */
    public function setFlash($message = '')
    {
        if ($message === '')
        {
            return;
        }

        $this->write(CEVC_SessionKeys::FlashMessage, $message);
    }

    /**
     * Gets a flash message and deletes it
     * @return string/null
     */
    public function getFlash()
    {
        $fMessage = $this->read(CEVC_SessionKeys::FlashMessage);
        $this->delete(CEVC_SessionKeys::FlashMessage);

        return $fMessage;
    }

}

/**
 * Holds the names of all session keys that Visitorchat uses.
 */
class CEVC_SessionKeys
{

    const ModeratorOnline = 'ModeratorOnline';
    const Visitor = 'Visitor';
    const VisitorDiscussionId = 'Visitor.VisitorDiscussionId';
    const VisitorUsername = 'Visitor.VisitorUsername';
    const VisitorEmail = 'Visitor.VisitorEmail';
    const VisitorOpenState = 'Visitor.VisitorOpenState';
    const VisitorReferer = 'Visitor.VisitorReferer';
    const FlashMessage = 'Miscellaneous.FlashMessage';

}
