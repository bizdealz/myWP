<?php
/*
  Plugin Name: ClientEngage VisitorChat for WordPress
  Plugin URI: http://clientengage.com
  Description: The ClientEngage VisitorChat for WordPress is a fully-featured real-time chat for your WordPress sites. And best of all: it comes with a native Windows-based client right out-of-the-box so you can start chatting from the convenience of your desktop.
  Author: ClientEngage
  Author URI: http://clientengage.com/
  Version: 1.0.5
  Text Domain: visitorchat
  Domain Path: /lang
  License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

defined('ABSPATH') or die('No direct access.');

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

/**
 * This is the main VisitorChat class
 */
class CEVC
{

    /**
     * Contains the unique name of the plugin
     * @var string
     */
    public static $name = 'visitorchat';

    /**
     * Returns the singleton instance
     * @return CEVC
     */
    public static function i()
    {
        if (null == self::$instance)
        {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * VisitorChat version information
     * @var string
     */
    public static $version = '1.0.5';

    /**
     * Indicates whether VisitorChat is in demo mode
     * @var bool
     */
    public $isDemo = false;

    /**
     * Holds the one and only instance to the VisitorChat plugin
     * @var CEVC
     */
    protected static $instance = null;

    /**
     * Holds the web-path to the VisitorChat plugin
     * @var string
     */
    public $url;

    /**
     * Holds the physical system-path to the VisitorChat plugin with a 
     * trailing slash
     * @var string
     */
    public $dir;

    /**
     * Holds the VisitorChat SessionEngine
     * @var CEVC_SessionEngine 
     */
    public $Session = null;

    /**
     * Holds the Request-Access Layer
     * @var CEVC_RequestEngine
     */
    public $Request = null;

    /**
     * Contains a range of HTML helpers to render markup
     * @var CEVC_Helper
     */
    public $Helper = null;

    /**
     * Holds the Admin-Access layer
     * @var CEVC_AdminUser
     */
    public $User = null;

    /**
     * Holds the Discussion-Access layer
     * @var CEVC_Discussion
     */
    public $Discussion = null;

    /**
     * Holds the Message-Access layer
     * @var CEVC_Message
     */
    public $Message = null;

    /**
     * Holds the Enquiry-Access layer
     * @var CEVC_Enquiry
     */
    public $Enquiry = null;

    /**
     * Used to determine if the composing state needs to be re-set after it was 
     * read by the client. Monitor and implement if necessary (currently unused)
     * @deprecated since 1.0.1
     * @var boolean
     */
    public $resetComposingState = null;

    /**
     * Constructor method for initialising the main hooks, class instances 
     * and settings
     */
    public function __construct()
    {
        $this->url = plugins_url('visitorchat');
        $this->dir = str_replace('visitorchat.php', '', __FILE__);

        /**
         * Class Initialisation
         */
        require_once $this->dir . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'php-user-agent' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpUserAgent.php';
        require_once $this->dir . 'lib' . DIRECTORY_SEPARATOR . 'Utilities.php';
        require_once $this->dir . 'lib' . DIRECTORY_SEPARATOR . 'Session.php';
        require_once $this->dir . 'lib' . DIRECTORY_SEPARATOR . 'AppModel.php';
        require_once $this->dir . 'lib' . DIRECTORY_SEPARATOR . 'AdminUser.php';
        require_once $this->dir . 'lib' . DIRECTORY_SEPARATOR . 'Discussion.php';
        require_once $this->dir . 'lib' . DIRECTORY_SEPARATOR . 'Message.php';
        require_once $this->dir . 'lib' . DIRECTORY_SEPARATOR . 'Enquiry.php';

        $this->Helper = new CEVC_Helper();
        $this->User = new CEVC_AdminUser();
        $this->Discussion = new CEVC_Discussion();
        $this->Message = new CEVC_Message();
        $this->Enquiry = new CEVC_Enquiry();
        $this->Session = new CEVC_SessionEngine();
        $this->Request = new CEVC_RequestEngine();

        /**
         * Register all hooks
         */
        $this->registerHooks();
    }

    /**
     * Registers all WordPress hooks
     */
    private function registerHooks()
    {
        add_action('init', array($this->Session, 'start'), 1);
        add_action('init', array($this, 'initialisation'));
        add_action('wp_login', array($this, 'user_login'));
        add_action('wp_logout', array($this, 'user_logout'));

        /**
         * Register initialisation hook for requirements-check
         */
        add_action('admin_init', array($this, 'checkRequiredWordPressVersion'));


        /**
         * Initialise the database
         */
        register_activation_hook(__FILE__, array($this, 'visitorChatActivate'));
        register_deactivation_hook(__FILE__, array($this, 'visitorChatDeactivate'));

        /**
         * Register remaining hooks
         */
        add_action('admin_init', array($this, 'admin_initialise'));


        /**
         * Register footer-hook
         */
        add_action('wp_footer', array($this, 'determineChatPage'));

        /**
         * Register admin-menu hook
         */
        add_action('admin_menu', array($this, 'register_visitorchat_menu'));


        /**
         * Register the JavaScript object
         */
        add_action('wp_head', array($this, 'setJSVariables'));
    }

    /**
     * Overarching initialisation method
     */
    public function initialisation()
    {
        /**
         * Set referer on first page-load
         */
        if (!$this->Session->check(CEVC_SessionKeys::VisitorReferer))
        {
            $this->Session->write(CEVC_SessionKeys::VisitorReferer, $this->Request->referer());
        }

        /**
         * Register MO-file domain
         */
        $this->load_textdomain();

        // Add capability to administrators
        $adminRole = get_role('administrator');
        $adminRole->add_cap('use_visitorchat');

        /**
         * General activity-recording depending on user & state
         */
        $this->User->recordActivity(true);

        /**
         * Register online-status icon for the user menu (back- and front-end)
         */
        if ($this->Session->read(CEVC_SessionKeys::ModeratorOnline) === true)
        {
            add_filter('gettext', array($this, 'injectUserMenuChatStatus'), 10, 3);
        }

        add_shortcode('visitorchat', array($this, 'visitorChatShortCode'));

        // Map of AJAX actions
        $methodMap = array(
            'css_style' => array(
                'public' => true,
                'admin' => true),
            'js' => array(
                'public' => true,
                'admin' => true),
            'status' => array(
                'public' => true,
                'admin' => true),
            'signup' => array(
                'public' => true,
                'admin' => true),
            'signout' => array(
                'public' => true,
                'admin' => true),
            'read' => array(
                'public' => true,
                'admin' => true),
            'composing' => array(
                'public' => true,
                'admin' => true),
            'send' => array(
                'public' => true,
                'admin' => true),
            'enquiry_submit' => array(
                'public' => true,
                'admin' => true),
            'admin_read' => array(
                'public' => false,
                'admin' => true),
            'admin_read_more' => array(
                'public' => false,
                'admin' => true),
            'admin_send' => array(
                'public' => false,
                'admin' => true),
            'admin_composing' => array(
                'public' => false,
                'admin' => true),
            'admin_discussion_close' => array(
                'public' => false,
                'admin' => true),
            'admin_login' => array(
                'public' => true,
                'admin' => true),
            'admin_logout' => array(
                'public' => true,
                'admin' => true),
            'admin_go_online' => array(
                'public' => false,
                'admin' => true),
            'admin_get_profile' => array(
                'public' => false,
                'admin' => true),
        );

        foreach ($methodMap as $method => $settings)
        {
            if ($settings['public'] === true)
            {
                add_action('wp_ajax_nopriv_cevc_' . $method, array($this, 'cevc_' . $method)); // visitor
            }

            if ($settings['admin'] === true && current_user_can('use_visitorchat'))
            {
                add_action('wp_ajax_cevc_' . $method, array($this, 'cevc_' . $method)); // user
            }
        }
    }

    /**
     * Admin-session clear-up after login
     * TODO: clearing necessary?
     */
    public function user_login()
    {
        //$this->Session->clear();
    }

    /**
     * Admin-session clear-up after logout
     */
    public function user_logout()
    {
        //$this->Session->clear();
        $this->User->recordActivity(false);
    }

// <editor-fold desc="AJAX chat methods">

    /**
     * Ajax-method for the public status action
     */
    public function cevc_status()
    {
        header('Content-Type: application/json');

        if ($this->Request->query('data.open_state') !== null)
        {
            if ($this->Request->query('data.open_state') == "true")
            {
                $this->Session->write(CEVC_SessionKeys::VisitorOpenState, true);
            }
            else
            {
                $this->Session->write(CEVC_SessionKeys::VisitorOpenState, false);
            }
        }

        $return = array(
            'success' => true,
            'signed_up' => $this->Session->check(CEVC_SessionKeys::VisitorDiscussionId),
            'username' => $this->Session->check(CEVC_SessionKeys::VisitorUsername) ? $this->Session->read(CEVC_SessionKeys::VisitorUsername) : '',
            'avatar' => $this->Session->check(CEVC_SessionKeys::VisitorEmail) ? md5(strtolower(trim($this->Session->read(CEVC_SessionKeys::VisitorEmail)))) : '',
            'open_state' => $this->Session->check(CEVC_SessionKeys::VisitorOpenState) ? $this->Session->read(CEVC_SessionKeys::VisitorOpenState) : false,
            'online' => $this->User->checkModeratorsOnline()
        );

        die(json_encode($return));
    }

    /**
     * Ajax-method for the public signup action
     */
    public function cevc_signup()
    {
        header('Content-Type: application/json');

        if ($this->Session->check(CEVC_SessionKeys::VisitorDiscussionId))
        {
            die(json_encode(array(
                'success' => false,
                'errors' => array('server' => __('It appears that a discussion is already in progress.', 'visitorchat'))
            )));
        }

        if (!$this->User->checkModeratorsOnline())
        {
            die(json_encode(array(
                'success' => false,
                'errors' => array('server' => array(self::getTranslations('OperatorOfflineMessage')))
            )));
        }

        $discussion = CEVC_Utilities::clean($this->Request->data('data'));
        $discussion['Discussion']['username'] = trim($discussion['Discussion']['username']);
        if (isset($discussion['Discussion']['email']))
            $discussion['Discussion']['email'] = trim(strtolower($discussion['Discussion']['email']));
        $discussion['Discussion']['remote_address'] = $this->Request->remoteAddress();
        $discussion['Discussion']['user_agent'] = $this->Request->userAgent();
        $discussion['Discussion']['visitor_languages'] = implode(',', $this->Request->acceptLanguage());
        $discussion['Discussion']['referer'] = urldecode(urldecode($this->Session->read(CEVC_SessionKeys::VisitorReferer)));
        $discussion['Discussion']['uid'] = CEVC_String::uuid();

        if ($this->Discussion->save($discussion['Discussion']))
        {
            die(json_encode(array(
                'success' => true,
                'errors' => array()
            )));
        }

        die(json_encode(array(
            'success' => false,
            'errors' => !empty($this->Discussion->validationErrors) ? $this->Discussion->validationErrors : array('server' => array('generalerror' => __('A server error occured.', 'visitorchat')))
        )));
    }

    /**
     * Public-facing signout ajax-method
     */
    public function cevc_signout()
    {
        header('Content-Type: application/json');

        if (!$this->Session->check(CEVC_SessionKeys::VisitorDiscussionId))
        {
            die(json_encode(array(
                'success' => false,
                'errors' => array('nodiscussioninprogress' => __('You cannot sign-out since no discussion is in progress.', 'visitorchat'))
            )));
        }

        if ($this->Request->data('data.sign_out') == "true")
        {
            $referer = $this->Session->read(CEVC_SessionKeys::VisitorReferer);

            $discussion_id = $this->Session->read(CEVC_SessionKeys::VisitorDiscussionId);
            $this->Session->delete(CEVC_SessionKeys::Visitor);
            $this->Session->write(CEVC_SessionKeys::VisitorReferer, $referer);

            if ($this->Discussion->exists($discussion_id))
            {
                $this->Discussion->id = $discussion_id;
                $this->Discussion->save(array('id' => $discussion_id, 'visitor_exited' => true), array('novalidate' => true, 'fieldList' => array('id', 'visitor_exited')));
            }

            die(json_encode(array('success' => true, 'errors' => array())));
        }

        die(json_encode(array('success' => false, 'errors' => array('server' => __('Sign-out command not sent.', 'visitorchat')))));
    }

    /**
     * Ajax-method for the public read action
     */
    public function cevc_read()
    {
        header('Content-Type: application/json');

        if ($this->Session->check(CEVC_SessionKeys::VisitorDiscussionId) !== true)
        {
            die(json_encode(array('success' => false, 'errors' => array('server' => __('There is no active discussion in progress.', 'visitorchat')))));
        }

        if (!$this->Request->is('post'))
        {
            die(json_encode(array('success' => false, 'errors' => array('server' => __('Incorrect request type.', 'visitorchat')))));
        }

        $discussion = $this->Discussion->getDiscussionForVisitorRead($this->Session->read(CEVC_SessionKeys::VisitorDiscussionId));

        $composing = array();
        $composing['composing'] = time() - (3) < strtotime($discussion['composing_user_date']) ? true : false;
        $composing['composing_username'] = $composing['composing'] ? ( isset($discussion['display_name']) ? $discussion['display_name'] : null ) : null;

        if ($composing['composing']) // no need to reset if not composing
        {
            $this->resetComposingState = true;
        }

        die(json_encode(array(
            'success' => true,
            'messages' => $this->__getMessagesSinceLastRead(),
            'composing' => $composing['composing'],
            'composing_username' => $composing['composing_username'],
        )));
    }

    /**
     * Ajax-method for the public send action
     */
    public function cevc_send()
    {
        header('Content-Type: application/json');

        if ($this->Session->check(CEVC_SessionKeys::VisitorDiscussionId) !== true)
        {
            die(json_encode(array('success' => false, 'errors' => array('server' => __('There is no active discussion in progress.', 'visitorchat')))));
        }

        if (!$this->Request->is('post'))
        {
            die(json_encode(array('success' => false, 'errors' => array('server' => __('Incorrect request type.', 'visitorchat')))));
        }

        if (!$this->User->checkModeratorsOnline())
        {
            die(json_encode(array('success' => false, 'errors' => array('server' => __('Moderator offline.', 'visitorchat')))));
        }

        $message = CEVC_Utilities::clean($this->Request->data('data'));
        $message['Message']['message'] = trim($message['Message']['message']);
        $message['Message']['current_page'] = urldecode(urldecode($message['Message']['current_page']));
        $message['Message']['discussion_id'] = $this->Session->read(CEVC_SessionKeys::VisitorDiscussionId);
        $message['Message']['user_id'] = null; // visitors do not have an id

        if ($this->Message->save($message['Message']))
        {
            die(json_encode(array(
                'data' => array(
                    'User' => array('username' => $this->Session->read(CEVC_SessionKeys::VisitorUsername), 'avatar' => md5($this->Session->read(CEVC_SessionKeys::VisitorEmail)), 'is_admin' => false),
                    'Message' => array_merge($message['Message'], array(
                        'id' => $this->Message->id,
                        'created' => date('m/d/Y H:i:s e', strtotime($this->Message->field('created')))
                    ))),
                'messages' => $this->__getMessagesSinceLastRead(),
                'success' => true, 'errors' => array())));
        }

        die(json_encode(array(
            'success' => false,
            'errors' => empty($this->Message->validationErrors) ? $this->Message->validationErrors : array('server' => array(__('A server error occured.', 'visitorchat')))
        )));
    }

    public function cevc_enquiry_submit()
    {
        header('Content-Type: application/json');

        if (!$this->Request->is('post'))
        {
            die(json_encode(array('success' => false, 'errors' => array('server' => __('Incorrect request type.', 'visitorchat')))));
        }

        $enquiry = CEVC_Utilities::clean($this->Request->data('data'));
        $enquiry['Enquiry']['email'] = trim(strtolower($enquiry['Enquiry']['email']));
        $enquiry['Enquiry']['remote_address'] = $this->Request->remoteAddress();
        $enquiry['Enquiry']['user_agent'] = $this->Request->userAgent();
        $enquiry['Enquiry']['visitor_languages'] = implode(',', $this->Request->acceptLanguage());
        $enquiry['Enquiry']['referer'] = urldecode(urldecode($this->Session->read(CEVC_SessionKeys::VisitorReferer)));
        $enquiry['Enquiry']['current_page'] = urldecode(urldecode($enquiry['Enquiry']['current_page']));

        if ($this->Enquiry->save($enquiry['Enquiry']))
        {
            die(json_encode(array(
                'success' => true,
                'errors' => array()
            )));
        }
        else
        {
            die(json_encode(array(
                'success' => false,
                'errors' => !empty($this->Enquiry->validationErrors) ? $this->Enquiry->validationErrors : array('server' => array('generalerror' => __('A server error occured.', 'visitorchat')))
            )));
        }
    }

    /**
     * Private method for finding all new messages matching the criteria
     * @return array Messages
     */
    private function __getMessagesSinceLastRead()
    {
        $params = array_merge(array('last_id' => 0, 'is_new_page' => 'false', 'first_id' => 0, 'load_more' => "false"), $this->Request->data('data'));

        $messages = $this->Message->getSinceLastRead($this->Session->read(CEVC_SessionKeys::VisitorDiscussionId), $params);

        foreach ($messages as &$message)
        {
            $newM = array();
            $newM['Message'] = array(
                'id' => $message['id'],
                'message' => $message['message'],
                'created' => $message['created'],
            );

            if (isset($message['ID']))
            {
                $newM['User'] = array(
                    'id' => $message['ID'],
                    'username' => $message['display_name'],
                    'email' => $message['user_email'],
                );
            }

            $message = $newM;
        }

        foreach ($messages as &$message)
        {
            if (!isset($message['User']['username']))
            {
                $message['User']['username'] = $this->Session->read(CEVC_SessionKeys::VisitorUsername);
                $message['User']['avatar'] = md5(strtolower(trim($this->Session->read(CEVC_SessionKeys::VisitorEmail))));
                $message['User']['is_admin'] = false;
            }
            else
            {
                $message['User']['avatar'] = md5(strtolower(trim($message['User']['email'])));
                $message['User']['is_admin'] = true;
            }
            unset($message['User']['id'], $message['User']['email']);

            $message['Message']['created'] = date('m/d/Y H:i:s e', strtotime($message['Message']['created']));
        }

        return $messages;
    }

    /**
     * Ajax-method for the admin_send action
     */
    public function cevc_admin_send()
    {
        header('Content-Type: application/json');

        if (!$this->Request->is('post'))
        {
            die(json_encode(array('success' => false, 'errors' => array('server' => __('Incorrect request type.', 'visitorchat')))));
        }

        $message = CEVC_Utilities::clean($this->Request->data('data'));
        if ($this->Message->save($message['Message']))
        {
            $message['Message']['id'] = $this->Message->id;
            $message['User']['id'] = $this->User->getUserId();
            $message['User']['username'] = $this->User->getUserName();

            $message['Message']['created'] = date('m/d/Y H:i:s e');
            $message['User']['avatar'] = md5($this->User->getUserEmail());
            unset($message['User']['email']);

            die(json_encode(array(
                'success' => true,
                'data' => $message
            )));
        }
        else
        {
            json_encode(array('success' => false, 'errors' => array('server' => __('Message could not be saved.', 'visitorchat'))));
        }
    }

    /**
     * Ajax-method for the admin_composing action
     */
    public function cevc_admin_composing()
    {
        header('Content-Type: application/json');

        if (!$this->Request->data('data.discussion_id'))
        {
            die(json_encode(array('success' => false, 'errors' => array('server' => __('Discussion id not passed.', 'visitorchat')))));
        }

        if (!$this->Request->is('post'))
        {
            die(json_encode(array('success' => false, 'errors' => array('server' => __('Incorrect request type.', 'visitorchat')))));
        }

        $this->Discussion->save(array(
            'id' => $this->Request->data('data.discussion_id'),
            'composing_user_date' => date('Y-m-d H:i:s'),
            'composing_user_id' => $this->User->getUserId()
                ), array('novalidate' => true, 'fieldList' => array('id', 'composing_user_date', 'composing_user_id')));

        die(json_encode(array('success' => true, 'errors' => array())));
    }

    /**
     * Ajax-method for the public composing action
     */
    public function cevc_composing()
    {
        header('Content-Type: application/json');

        if ($this->Session->check(CEVC_SessionKeys::VisitorDiscussionId) !== true)
        {
            die(json_encode(array('success' => false, 'errors' => array())));
        }

        if (!$this->Request->is('post'))
        {
            die(json_encode(array('success' => false, 'errors' => array('server' => __('Incorrect request type.', 'visitorchat')))));
        }

        $this->Discussion->save(array(
            'id' => $this->Session->read(CEVC_SessionKeys::VisitorDiscussionId),
            'composing_visitor_date' => date('Y-m-d H:i:s'),
                ), array('novalidate' => true, 'fieldList' => array('id', 'composing_visitor_date')));

        die(json_encode(array('success' => true, 'errors' => array())));
    }

    /**
     * Ajax-method for the admin_read action
     */
    public function cevc_admin_read()
    {
        header('Content-Type: application/json');

        $since = date("Y-m-d H:i:s", strtotime('-10 minutes', time()));
        $activeIds = $this->__getActiveDiscussions();

        $discussions = $this->Discussion->adminReadDiscussions($since, $activeIds);

        die(json_encode(array('success' => true, 'discussions' => $discussions)));
    }

    /**
     * Ajax-method for the admin_read_more action
     */
    public function cevc_admin_read_more()
    {
        header('Content-Type: application/json');

        $discussion_id = $this->Request->data('data.discussion_id');
        $first_id = $this->Request->data('data.first_id');

        $messages = $this->Message->adminReadMore($discussion_id, $first_id);

        die(json_encode(array('success' => true, 'messages' => $messages)));
    }

    /**
     * Adds a given ID to the list of active discussions
     * @param int $id
     * @return void
     */
    public function __addActiveDiscussion($id)
    {
        if ($id == null)
        {
            return;
        }

        $active = $this->__getActiveDiscussions();
        $active[] = $id;

        $this->Session->write('User.active_discussions', array_unique($active));
    }

    /**
     * Removes a given ID from the list of active discussions
     * @param int $id
     * @return void
     */
    private function __removeActiveDiscussion($id)
    {
        if ($id == null)
        {
            return;
        }

        $active = $this->__getActiveDiscussions();

        if (($key = array_search($id, $active)) !== false)
        {
            unset($active[$key]);
        }

        $this->Session->write('User.active_discussions', array_unique($active));
    }

    /**
     * Returns a list of all active discussions
     * @return array list of active discussions
     */
    private function __getActiveDiscussions()
    {
        if (!$this->Session->check('User.active_discussions'))
        {
            $this->Session->write('User.active_discussions', array());
        }

        return $this->Session->read('User.active_discussions');
    }

    /**
     * Ajax-method to log-in the respective user
     */
    public function cevc_admin_login()
    {
        header('Content-Type: application/json');

        if (false && !$this->Request->is('post'))
        {
            die(json_encode(array('success' => false, 'message' => __('Incorrect request type.', 'visitorchat'))));
        }

        if ($this->Request->query('api_key') === null || $this->get_option('api_key') !== $this->Request->query('api_key'))
        {
            die(json_encode(array('message' => 'Incorrect API-Key passed.', 'success' => false)));
        }

        if (!is_user_logged_in())
        {
            $user = wp_authenticate($this->Request->data('username'), $this->Request->data('password'));

            if ($user instanceof WP_User)
            {
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);

                if (current_user_can('use_visitorchat') !== true)
                {
                    wp_logout();
                    die(json_encode(array('message' => 'Your user profile does not have sufficient rights to use VisitorChat.', 'success' => false)));
                }

                die(json_encode(array('message' => 'Sucessfully logged in.', 'success' => true)));
            }
            else
            {
                die(json_encode(array('message' => 'The details you provided were incorrect.', 'success' => false)));
            }
        }
        else
        {
            die(json_encode(array('message' => 'Already logged in.', 'success' => true)));
        }
    }

    /**
     * Ajax-method to go offline and logout the current user
     */
    public function cevc_admin_logout()
    {
        header('Content-Type: application/json');

        $this->User->recordActivity(false);
        $this->Session->write(CEVC_SessionKeys::ModeratorOnline, false);

        wp_logout();
        die(json_encode(array('data' => '', 'success' => true)));
    }

    /**
     * Allows the closing of active discussions
     */
    public function cevc_admin_discussion_close()
    {
        header('Content-Type: application/json');

        $this->__removeActiveDiscussion($this->Request->data('data.discussion_id'));

        die(json_encode(array('success' => true)));
    }

    /**
     * Ajax-method to render the chat's style
     */
    public function cevc_css_style()
    {
        header('Content-Type: text/css');
        header('Cache-control: public');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + WEEK_IN_SECONDS) . ' GMT');
        header('Pragma: cache');

        die($this->renderCSS());
    }

    /**
     * Renders the chat's CSS styles
     * @return string
     */
    private function renderCSS()
    {
        $css = str_replace(
                array(
            '[BaseURL]',
            '[LayoutPosition]',
            '[PrimaryColour]',
            '[LightPrimary]',
            '[BackgroundImage]',
                ), array(
            $this->url . '/',
            $this->get_option('layout_position'),
            $this->get_option('layout_primarycolour'),
            $this->Helper->adaptColourBrightness($this->get_option('layout_primarycolour'), 70),
            $this->get_option('layout_backgroundimage')
                ), file_get_contents($this->dir . 'css' . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . 'visitorchat_style.css')
        );

        $replacements = array(
            '; ' => ';',
            ': ' => ':',
            ' {' => '{',
            '{ ' => '{',
            ', ' => ',',
            '} ' => '}',
            ';} ' => '}'
        );

        $css = preg_replace('#/\*.*?\*/#s', '', preg_replace('#\s+#', ' ', $css));

        foreach ($replacements as $search => $replace)
        {
            $css = str_replace($search, $replace, $css);
        }

        $copyRight = <<<COPYRIGHT
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
 * @license         CodeCanyon Regular License
 * 
 */

COPYRIGHT;

        return $copyRight . trim($css);
    }

    /**
     * Ajax-method to render the chat's JavaScript code
     */
    public function cevc_js()
    {
        header('Content-Type: text/javascript');
        header('Cache-control: public');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + WEEK_IN_SECONDS) . ' GMT');
        header('Pragma: cache');

        die($this->renderJavaScript());
    }

    /**
     * Renders the chat's JavaScript code
     * @return string
     */
    private function renderJavaScript()
    {
        require_once $this->dir . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'jsmin' . DIRECTORY_SEPARATOR . 'jsmin.php';
        $copyright = <<<COPYRIGHT
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
 * @license         CodeCanyon Regular License
 * 
 */
COPYRIGHT;

        $js = str_replace('console.log', '//R', file_get_contents($this->dir . 'js' . DIRECTORY_SEPARATOR . 'visitorchat.js'));
        return $copyright . JSMin::minify($js);
    }

    /**
     * Ajax-method to handle going online/offline
     */
    public function cevc_admin_go_online()
    {
        header('Content-Type: application/json');

        die(json_encode(array('success' => true, 'status' => $this->adminToggleOnline() ? 'online' : 'offline')));
    }

    /**
     * Ajax-method to handle going online/offline
     */
    public function cevc_admin_get_profile()
    {
        header('Content-Type: application/json');

        $current_user = wp_get_current_user();
        die(json_encode(array(
            'id' => $current_user->ID,
            'username' => $current_user->display_name,
            'email' => $current_user->user_email,
            'active' => true,
            'gravatarDefault' => $this->get_option('gravatar_default'),
        )));
    }

// </editor-fold>

    /**
     * Toggles the admin's online status based on his/her current status 
     * and returns true (online) or false (offline)
     * @return boolean
     */
    private function adminToggleOnline()
    {
        if ($this->Session->read(CEVC_SessionKeys::ModeratorOnline) === true)
        {
            $this->User->recordActivity(false);
            $this->Session->write(CEVC_SessionKeys::ModeratorOnline, false);
            return false;
        }
        else
        {
            $this->Session->write(CEVC_SessionKeys::ModeratorOnline, true);
            $this->User->recordActivity(true);
            return true;
        }
    }

    /**
     * Returns all translations for use in the global JavaScript object, or the
     * one matching the passed translation key
     * @param string $key
     * @return mixed
     */
    public static function getTranslations($key = null)
    {
        $lang = array(
            'HeaderCurrentlyChatting' => __('Currently chatting...', 'visitorchat'),
            'HeaderOnline' => __('Start chat - we\'re here', 'visitorchat'),
            'HeaderOffline' => __('Contact us', 'visitorchat'),
            'OperatorOfflineMessage' => __('It appears as if all operators are currently offline.', 'visitorchat'),
            'OfflineMessage' => __('<strong>We\'re not online right now.</strong><br />Our butts may not be in our seats, but that doesn\'t mean we\'re not there to help - leave your details below and we\'ll be in touch as soon as we can.', 'visitorchat'),
            'OnlineMessage' => __('<strong>Questions?</strong><br />Good, because we\'re available right now - simply type your name and email address to start a live-chat with our support team.', 'visitorchat'),
            'UsernamePlaceholder' => __('Your name', 'visitorchat'),
            'EmailPlaceholder' => __('Your e-mail address', 'visitorchat'),
            'EnquiryMessagePlaceholder' => __('Your enquiry...', 'visitorchat'),
            'EnquiryButtonText' => __('Send Enquiry', 'visitorchat'),
            'EnquirySubmitSuccess' => __('Your enquiry was sucessfully submitted - we will contact you as soon as possible.', 'visitorchat'),
            'StartChatButtonText' => __('Start Chat', 'visitorchat'),
            'OperatorComposing' => __('{username} is typing...', 'visitorchat'),
            'FirstMessageText' => __('You can write your message now...', 'visitorchat'),
            'ExitChatButtonText' => __('Exit Chat', 'visitorchat'),
            'ExitChatQuestionText' => __('Are you sure?', 'visitorchat'),
            'ExitChatButtonConfirmText' => __('Yes, exit', 'visitorchat'),
            'ExitChatButtonCancelText' => __('No, do not exit', 'visitorchat'),
            'MessagePlaceholderText' => __('Your message...', 'visitorchat'),
            'MessageSendButtonText' => __('Send', 'visitorchat'),
            'ValidationEmailRequired' => __('Please enter your email address', 'visitorchat'),
            'ValidationEmailInvalid' => __('Please enter a valid email address', 'visitorchat'),
            'ValidationEmailMaxLength' => __('The email address may not exceed 85 characters', 'visitorchat'),
            'ValidationUsernameRequired' => __('Please enter your name', 'visitorchat'),
            'ValidationUsernameMaxLength' => __('The name may not exceed 45 characters', 'visitorchat'),
            'ValidationEnquiryRequired' => __('Please enter your enquiry', 'visitorchat'),
            'ValidationEnquiryMaxLength' => __('The enquiry may not exceed 1500 characters', 'visitorchat'),
            'ValidationMessageRequired' => __('Please enter your message', 'visitorchat'),
            'ValidationMessageMaxLength' => __('The message may not exceed 750 characters', 'visitorchat'),
            'Yes' => __('Yes', 'visitorchat'),
            'No' => __('No', 'visitorchat'),
            'MetaVisitorData' => __('Visitor Data', 'visitorchat'),
            'MetaUsername' => __('Username', 'visitorchat'),
            'MetaEmail' => __('E-Mail', 'visitorchat'),
            'MetaStarted' => __('Started', 'visitorchat'),
            'MetaLastActivity' => __('Last Activity', 'visitorchat'),
            'MetaVisitorExited' => __('Visitor Exited', 'visitorchat'),
            'MetaReferer' => __('Referer', 'visitorchat'),
            'MetaVisitorLanguages' => __('Languages', 'visitorchat'),
            'MetaVisitorTime' => __('Visitor Time', 'visitorchat'),
            'MetaBrowser' => __('Browser', 'visitorchat'),
            'MetaOS' => __('OS', 'visitorchat'),
            'MetaCurrentPage' => __('Current Page', 'visitorchat'),
        );

        if ($key === null)
        {
            return $lang;
        }

        if (isset($lang[$key]))
        {
            return $lang[$key];
        }

        return null;
    }

    /**
     * Sets globally accessible JavaScript variables
     */
    public function setJSVariables()
    {
        $lang = self::getTranslations();
        $globalJSVars = array(
            'version' => self::$version,
            'isDemo' => $this->isDemo,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'animateHover' => $this->get_option('layout_animatehover') == '1' ? true : false,
            'hideMobile' => $this->get_option('layout_hidemobile') == '1' ? true : false,
            'baseUrl' => $this->url,
            'gravatarDefault' => $this->get_option('gravatar_default'),
            'Lang' => $lang
        );

        $onLoad = '';
        $current_user = wp_get_current_user();
        if (($current_user instanceof WP_User))
        {
            $display_name = $current_user->display_name;
            $user_email = $current_user->user_email;

            if ($this->isDemo)
            {
                $first = array('Anton', 'Aaron', 'Albert', 'Ben', 'Bonni', 'Cynthia', 'Charlie', 'Charles', 'Dennis', 'Darren', 'Frank', 'Garry', 'Hollie', 'Ingrid', 'Julia', 'Jules', 'Jeanine', 'Jane', 'Laura', 'Michaela', 'Nora', 'Nina', 'Olaf', 'Patrick', 'Paul', 'Paula', 'Richard', 'Ricky', 'Rafaela', 'Renate', 'Sandra', 'Sandy', 'Tim', 'Tom', 'Ursula', 'Victoria', 'Zara',);
                $last = array('Doe', 'Examplesen', 'Testersen', 'Testing', 'Tested',);

                shuffle($first);
                shuffle($last);

                $display_name = $first[0] . ' ' . $last[0];
                $user_email = strtolower($first[0] . '.' . $last[0] . '@example.com');
            }

            $onLoad = '
    jQuery(".vc_input_username, .vc_input_enquiry_username").val("' . $display_name . '").focus().blur();
    jQuery(".vc_input_email, .vc_input_enquiry_email").val("' . $user_email . '").focus().blur();
';
        }

        echo '<script type="text/javascript">
var ceVC_JS = ' . json_encode($globalJSVars) . ';
    
var CEVC_Onload_Internal = function() {
' . $onLoad . '
};
</script>';
    }

    /**
     * Sets globally accessible JavaScript variables for the admin section
     */
    public function setJSVariablesAdmin()
    {
        global $current_user;
        get_currentuserinfo();

        $lang = self::getTranslations();
        $globalJSVars = array(
            'version' => self::$version,
            'isDemo' => $this->isDemo,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'baseUrl' => $this->url,
            'gravatarDefault' => $this->get_option('gravatar_default'),
            'user' => array(
                'username' => $current_user->display_name,
                'avatar' => md5($current_user->user_email)
            ),
            'Lang' => $lang,
        );

        echo '<script type="text/javascript">
var ceVC_JS = ' . json_encode($globalJSVars) . ';
</script>';
        wp_enqueue_script('visitorchat-script_admin', $this->url . '/js/visitorchat_admin.js', array('jquery', 'md5-script'), self::$version, true);
        wp_enqueue_script('md5-script', $this->url . '/vendor/js/crypto-js/md5.js', array(), self::$version, true);
    }

    /* /Chat Methods */

    /**
     * Check the WordPress version to ensure compatibility
     * @global type $wp_version
     */
    public function checkRequiredWordPressVersion()
    {
        global $wp_version;
        $plugin = plugin_basename(__FILE__);

        if (version_compare($wp_version, '3.3', '<'))
        {
            if (is_plugin_active($plugin))
            {
                deactivate_plugins($plugin);
                wp_die(CEVC_String::insert(__('For VisitorChat for WordPress to function, you need at least WordPress 3.3. Therefore, VisitorChat for WordPress has been deactivated. Please upgrade WordPress and install this plugin again. Click <a href=":dashboard_url">here</a> to go back to the WordPress dashboard.', 'visitorchat'), array('dashboard_url' => admin_url())));
            }
        }
    }

    /**
     * Initialise i18n
     */
    public function load_textdomain()
    {
        $domain = CEVC::$name;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, FALSE, dirname(plugin_basename(__FILE__)) . '/lang/');
    }

    // <editor-fold desc="Installation & Uninstallation">

    function _VC_Network_Bubble($pfunction, $networkwide)
    {
        global $wpdb;
        if (function_exists('is_multisite') && is_multisite())
        {
            if ($networkwide)
            {
                $old_blog = $wpdb->blogid;
                $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
                foreach ($blogids as $blog_id)
                {
                    switch_to_blog($blog_id);
                    call_user_method($pfunction, $this, $networkwide);
                }
                switch_to_blog($old_blog);
                return;
            }
        }
        call_user_method($pfunction, $this, $networkwide);
    }

    function visitorChatActivate($networkwide)
    {
        $this->_VC_Network_Bubble('_VC_Activate', $networkwide);
    }

    function visitorChatDeactivate($networkwide)
    {
        $this->_VC_Network_Bubble('_VC_Deactivate', $networkwide);
    }

    public function _VC_Activate($networkwide)
    {
        $this->install_db();
        $this->add_default_options();
    }

    public function _VC_Deactivate($networkwide)
    {
        return;
    }

    /**
     * Set-up the initial DB
     * @global wpdb $wpdb
     */
    public function install_db()
    {
        global $wpdb;
        $supported = $wpdb->get_results('SHOW ENGINES;', ARRAY_A);

        $useEngine = 'MyISAM';
        foreach ($supported as $engine)
        {
            if ($engine['Engine'] == 'InnoDB')
            {
                $useEngine = 'InnoDB';
            }
        }

        $tblPrefix = $wpdb->prefix . 'cevc_';

        $sql = array();
        $sql[] = "
CREATE  TABLE IF NOT EXISTS `" . $tblPrefix . "messages` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `discussion_id` INT NOT NULL ,
  `user_id` INT NULL ,
  `message` TEXT NOT NULL ,
  `current_page` TEXT NULL ,
  `created` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `" . $tblPrefix . "message_to_discussion_idx` (`discussion_id` ASC) ,
  CONSTRAINT `" . $tblPrefix . "message_to_discussion`
    FOREIGN KEY (`discussion_id` )
    REFERENCES `" . $tblPrefix . "discussions` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = $useEngine   DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";
        $sql[] = "
CREATE  TABLE IF NOT EXISTS `" . $tblPrefix . "discussions` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` CHAR(36) NOT NULL ,
  `username` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(85) NOT NULL ,
  `referer` TEXT NULL ,
  `visitor_time` DATETIME NULL ,
  `visitor_languages` VARCHAR(45) NULL ,
  `user_agent` TEXT NULL ,
  `remote_address` VARCHAR(45) NULL ,
  `visitor_exited` TINYINT(1) NOT NULL DEFAULT 0 ,
  `composing_visitor_date` DATETIME NULL ,
  `composing_user_date` DATETIME NULL ,
  `composing_user_id` INT NULL ,
  `modified` DATETIME NOT NULL ,
  `created` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `uid_UNIQUE` (`uid` ASC) )
ENGINE = $useEngine   DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";
        $sql[] = "
CREATE  TABLE IF NOT EXISTS `" . $tblPrefix . "enquiries` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(85) NOT NULL ,
  `message` TEXT NOT NULL ,
  `read` TINYINT(1) NOT NULL DEFAULT 0 ,
  `current_page` TEXT NULL ,
  `referer` TEXT NULL ,
  `visitor_languages` VARCHAR(45) NULL ,
  `user_agent` TEXT NULL ,
  `remote_address` VARCHAR(45) NULL ,
  `created` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = $useEngine   DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";

        $this->queryWithoutForeignKeyChecks($sql);

        update_option('cevc_db_version', self::$version);
    }

    /**
     * Switches off foreign key checks for installation/update purposes
     * @global wpdb $wpdb
     * @param array $queries
     */
    public function queryWithoutForeignKeyChecks(array $queries = array())
    {
        global $wpdb;

        $wpdb->query("SET FOREIGN_KEY_CHECKS=0;");

        foreach ($queries as $query)
        {
            $wpdb->query($query);
        }

        $wpdb->query("SET FOREIGN_KEY_CHECKS=1;");
    }

    /**
     * Holds all migration scripts necessary to upgrade from any VisitorChat 
     * version. Migration scripts must be "dynamic" and contain the table-prefix 
     * according to the current blog.
     * @return array The array of all existing migration scripts
     */
    private function dbMigrationList()
    {
        return array(
                /*  array(
                  'from' => '1.0.4',
                  'to' => '1.0.5',
                  'migration' => "",
                  ), */
        );
    }

    /**
     * Checks for any necessary database upgrades and runs all migration scripts
     * @global wpdb $wpdb
     * @return void
     */
    public function checkAndRunUpdate()
    {
        if (get_option('cevc_db_version') == CEVC::$version)
        {
            return;
        }

        $migrations = $this->dbMigrationList();

        foreach ($migrations as $migration)
        {
            if (version_compare(get_option('cevc_db_version'), $migration['to']) == -1)
            {
                global $wpdb;
                if (function_exists('is_multisite') && is_multisite())
                {
                    if (is_plugin_active_for_network(CEVC::$name))
                    {
                        $old_blog = $wpdb->blogid;
                        $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
                        foreach ($blogids as $blog_id)
                        {
                            switch_to_blog($blog_id);
                            $this->queryWithoutForeignKeyChecks(array($migration['migration']));
                        }
                        switch_to_blog($old_blog);
                        update_option('cevc_db_version', self::$version);
                        return;
                    }
                }
                $this->queryWithoutForeignKeyChecks(array($migration['migration']));
                update_option('cevc_db_version', self::$version);
            }
        }
    }

    /**
     * Removes all plugin options
     */
    public function delete_plugin_options()
    {
        delete_option('cevc_options');
        delete_option('cevc_db_version');
        delete_post_meta_by_key('visitorchat_post_meta_key_optin');
        delete_post_meta_by_key('visitorchat_post_meta_key_optout');
    }

    /**
     * Initialise default settings upon activation
     */
    public function add_default_options()
    {
        $tmp = get_option('cevc_options');
        if (($tmp['visitorchat_reset_default_options'] == '1') || (!is_array($tmp)))
        {
            delete_option('cevc_options');

            $arr = array(
                'show_front' => '1',
                'show_home' => '1',
                'show_comments_popup' => '0',
                'show_category' => '1',
                'show_tag' => '1',
                'show_tax' => '1',
                'show_author' => '1',
                'show_date' => '1',
                'show_search' => '1',
                'show_404' => '1',
                'show_attachment' => '1',
                'visitorchat_reset_default_options' => '0',
                'visitorchat_mode' => 'optout',
                'layout_position' => 'right',
                'layout_distance' => '60',
                'layout_animatehover' => '1',
                'layout_hidemobile' => '0',
                'layout_primarycolour' => '#af2c17',
                'layout_backgroundimage' => 'vc_background_red.png',
                'enquiry_recipient' => get_option('admin_email'),
                'gravatar_default' => 'identicon',
                'first_message' => '',
                'api_key' => CEVC_String::random(33),
                'save_count' => 1,
            );
            update_option('cevc_options', $arr);

            delete_post_meta_by_key('visitorchat_post_meta_key_optin');
            delete_post_meta_by_key('visitorchat_post_meta_key_optout');
        }
    }

    // </editor-fold>

    /**
     * Adds the Google Analytics code during demo-deployment
     * TODO: remove before release
     */
    public function addGA()
    {
        echo "<script type=\"text/javascript\">
                var _gaq = _gaq || [];
                _gaq.push(['_setAccount', 'UA-4514355-15']);
                _gaq.push(['_setDomainName', 'clientengage.com']);
                _gaq.push(['_trackPageview']);

                (function() {
                    var ga = document.createElement('script');
                    ga.type = 'text/javascript';
                    ga.async = true;
                    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                    var s = document.getElementsByTagName('script')[0];
                    s.parentNode.insertBefore(ga, s);
                })();
            </script>";
    }

    /**
     * Initialise plugin
     */
    public function admin_initialise()
    {
        if ($this->isDemo)
        {
            add_action('admin_head', array($this, 'addGA'));
        }

        if (!current_user_can('use_visitorchat'))
        {
            return;
        }

        register_setting('cevc_options', 'cevc_options', array($this, 'validate_options'));
        wp_register_style('cevcBootstrap', plugins_url('vendor/css/bootstrap/css/bootstrap.min.css', __FILE__));
        wp_register_style('cevcAdminStylesheet', plugins_url('css/stylesheet.css', __FILE__));

        add_meta_box('cevc_meta_box_id', __('VisitorChat Integration', 'visitorchat'), array($this, 'addMetaBox'), 'post', 'side', 'high');
        add_meta_box('cevc_meta_box_id', __('VisitorChat Integration', 'visitorchat'), array($this, 'addMetaBox'), 'page', 'side', 'high');
        add_action('save_post', array($this, 'saveMetaBox'));

        add_action('edit_user_profile', array($this, 'capabilityProfileFieldRender'));
        add_action('personal_options_update', array($this, 'capabilityProfileFieldSave'));
        add_action('edit_user_profile_update', array($this, 'capabilityProfileFieldSave'));

        // TODO: add admin-function to clear meta across all pages? --> delete_post_meta_by_key()
        $adminActions = array(
            'cevc_wpadmin_go_online',
            'cevc_wpadmin_enquiry_mark_unread',
            'cevc_wpadmin_enquiry_delete',
            'cevc_wpadmin_discussion_delete',
        );

        if ($this->Request->query('action') !== null)
        {
            if (in_array($this->Request->query('action'), $adminActions))
            {
                call_user_method($this->Request->query('action'), $this);
            }
        }

        $this->checkAndRunUpdate();
    }

    /**
     * Renders the VisitorChat-setting in user profiles
     * @param WP_User $user
     */
    function capabilityProfileFieldRender($user)
    {
        if ($user->has_cap('manage_options'))
        {
            return;
        }
        ?>
        <h3><?php echo __('VisitorChat Settings', 'visitorchat'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="vc_capability"><?php echo __('Can use VisitorChat', 'visitorchat'); ?></label></th>
                <td>
                    <input type="hidden" name="visitorchat_capability" id="visitorchat_capability" value="0" />
                    <input type="checkbox" id="vc_capability" name="visitorchat_capability" id="visitorchat_capability" value="1" <?php echo $user->has_cap('use_visitorchat') ? 'checked="true"' : ''; ?> /><br />
                    <span class="description"><?php echo __('If you select this option, this user can access the VisitorChat interface.', 'visitorchat'); ?></span>
                </td>
            </tr>
        </table><br />
        <?php
    }

    /**
     * Saves the VisitorChat-capability when editing a user
     * @param type $user_id
     * @return boolean
     */
    function capabilityProfileFieldSave($user_id)
    {
        if (!current_user_can('manage_options'))
        {
            return false;
        }

        if (isset($_POST['visitorchat_capability']))
        {
            $user = new WP_User($user_id);
            if ($_POST['visitorchat_capability'] == '1')
            {
                $user->add_cap('use_visitorchat', true);
            }
            else
            {
                $user->remove_cap('use_visitorchat');
            }
        }
    }

    /**
     * GET action for the admin area to toggle the admin's online-status
     */
    public function cevc_wpadmin_go_online()
    {
        $this->adminToggleOnline();
        $this->Request->redirect($this->Request->referer());
    }

    /**
     * Marks the given enquiry as read or unread
     */
    public function cevc_wpadmin_enquiry_mark_unread()
    {
        $enquiry_id = $this->Request->query('enquiry_id');
        $status = $this->Request->query('enquiry_status') == 'read' ? 1 : 0;

        if ($enquiry_id !== null)
        {
            $this->Enquiry->save(array('id' => $enquiry_id, 'read' => $status), array('novalidate' => true, 'fieldList' => array('id', 'read')));
            $this->Session->setFlash(__('Enquiry read-status changed.', 'visitorchat'));
        }

        $this->Request->redirect($this->Request->referer());
    }

    /**
     * Adds the VisitorChat metabox to posts and pages
     * @global type $post
     * @return void
     */
    public function addMetaBox()
    {
        $metaKey = '';
        $metaLabel = '';

        $mode = $this->get_option('visitorchat_mode');
        if ($mode == 'optout')
        {
            $metaLabel = __('Hide VisitorChat on this page', 'visitorchat');
            $metaKey = 'visitorchat_post_meta_key_optout';
        }
        else if ($mode == 'optin')
        {
            $metaLabel = __('Show VisitorChat on this page', 'visitorchat');
            $metaKey = 'visitorchat_post_meta_key_optin';
        }
        else
        {
            return;
        }

        global $post;
        $metaCheck = get_post_meta($post->ID, $metaKey, true);
        $checked = !empty($metaCheck) ? esc_attr($metaCheck) : '';

        wp_nonce_field('visitorchat_post_meta_box_nonce_action', 'visitorchat_post_meta_box_nonce');
        echo '<input type="checkbox" id="visitorchat_post_meta_key" name="' . $metaKey . '" ' . checked($checked, 'on', false) . ' />';
        echo ' <label for="visitorchat_post_meta_key">' . $metaLabel . '</label>';
    }

    /**
     * Saves the VisitorChat metabox when saving posts or pages
     * @param int $post_id
     * @return void
     */
    public function saveMetaBox($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        {
            return;
        }

        if (!isset($_POST['visitorchat_post_meta_box_nonce']) || !wp_verify_nonce($_POST['visitorchat_post_meta_box_nonce'], 'visitorchat_post_meta_box_nonce_action'))
        {
            return;
        }

        if (!current_user_can('edit_post'))
        {
            return;
        }

        $metaKey = '';

        $mode = $this->get_option('visitorchat_mode');
        if ($mode == 'optout')
        {
            $metaKey = 'visitorchat_post_meta_key_optout';
        }
        else if ($mode == 'optin')
        {
            $metaKey = 'visitorchat_post_meta_key_optin';
        }
        else
        {
            return;
        }

        $check = isset($_POST[$metaKey]) && $_POST[$metaKey] ? 'on' : 'off';
        update_post_meta($post_id, $metaKey, $check);
    }

    /**
     * Checks whether the respective VisitorChat setting exists
     * @param string $key
     * @return string
     */
    private function has_option($key)
    {
        $options = get_option('cevc_options');
        return isset($options[$key]);
    }

    /**
     * Returns the respective VisitorChat setting
     * @param string $key
     * @return string
     */
    public function get_option($key = null)
    {
        $options = get_option('cevc_options');

        if ($key === null)
        {
            return $options;
        }

        return isset($options[$key]) ? $options[$key] : null;
    }

    /**
     * Determines whether the chat JavaScript should be included in the current page
     */
    public function determineChatPage()
    {
        $funcMap = array(
            'is_front_page' => 'show_front',
            'is_home' => 'show_home',
            'is_comments_popup' => 'show_comments_popup',
            'is_category' => 'show_category',
            'is_tag' => 'show_tag',
            'is_tax' => 'show_tax',
            'is_author' => 'show_author',
            'is_date' => 'show_date',
            'is_search' => 'show_search',
            'is_404' => 'show_404',
            'is_attachment' => 'show_attachment',
        );

        foreach ($funcMap as $func => $fOption)
        {
            if (function_exists($func) && call_user_func($func))
            {
                if ($this->get_option($fOption) == '1')
                {
                    $this->addJavaScript();
                }

                return;
            }
        }

        if ($this->checkChatActiveOnPage() === true)
        {
            $this->addJavaScript();
            return;
        }
    }

    /**
     * Adds the actual JavaScript to the page's footer
     */
    private function addJavaScript()
    {
        $tmpDir = CEVC::i()->dir . 'public_tmp' . DIRECTORY_SEPARATOR;
        $jsUrl = file_exists($tmpDir . 'visitorchat.js') ? plugins_url('public_tmp/visitorchat.js', __FILE__) : admin_url('admin-ajax.php?action=cevc_js');
        $cssUrl = file_exists($tmpDir . 'visitorchat.css') ? plugins_url('public_tmp/visitorchat.css', __FILE__) : admin_url('admin-ajax.php?action=cevc_css_style');

        wp_enqueue_script('visitorchat-script', $jsUrl, array('jquery', 'md5-script'), self::$version . '_' . $this->get_option('save_count'), true);
        wp_enqueue_script('md5-script', $this->url . '/vendor/js/crypto-js/md5.js', array(), self::$version, true);

        wp_register_style('visitorchat-style', $cssUrl, array(), self::$version . '_' . $this->get_option('save_count'), 'all');
        wp_enqueue_style('visitorchat-style');
    }

    /**
     * Checks whether the chat should be included in the current page 
     * @param string $shortcode
     * @return boolean
     */
    private function checkChatActiveOnPage()
    {
        $mode = $this->get_option('visitorchat_mode');
        if ($mode == 'optout')
        {
            $metaKey = 'visitorchat_post_meta_key_optout';
        }
        else if ($mode == 'optin')
        {
            $metaKey = 'visitorchat_post_meta_key_optin';
        }
        else
        {
            return false;
        }

        $post_id = get_the_ID();

        $metaCheck = get_post_meta($post_id, $metaKey, true);
        $checked = !empty($metaCheck) ? esc_attr($metaCheck) : '';

        if ($mode === 'optin')
        {
            if ($checked === 'on')
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            if ($checked === 'on')
            {
                return false;
            }
            else
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Register the administration menus
     */
    public function register_visitorchat_menu()
    {
        $page = add_menu_page('VisitorChat Dashboard', 'VisitorChat', 'use_visitorchat', 'visitorchat-dashboard', array($this, 'visitorchat_dashboard'), plugins_url('visitorchat/img/admin/visitorchat-icon.png'), 163);
        $subPageDiscussions = add_submenu_page('visitorchat-dashboard', __('VisitorChat Past Discussions', 'visitorchat'), __('Past Discussions', 'visitorchat'), 'use_visitorchat', 'visitorchat-discussions', array($this, 'visitorchat_discussions'));
        $subPageEnquiries = add_submenu_page('visitorchat-dashboard', __('VisitorChat Enquiries', 'visitorchat'), __('Enquiries', 'visitorchat'), 'use_visitorchat', 'visitorchat-enquiries', array($this, 'visitorchat_enquiries'));
        $subPageSettings = add_submenu_page('visitorchat-dashboard', __('VisitorChat Settings', 'visitorchat'), __('Settings', 'visitorchat'), $this->isDemo ? 'use_visitorchat' : 'manage_options', 'visitorchat-settings', array($this, 'visitorchat_settings'));
        $subPageHelp = add_submenu_page('visitorchat-dashboard', __('VisitorChat Help & About', 'visitorchat'), __('Help & About', 'visitorchat'), 'use_visitorchat', 'visitorchat-help', array($this, 'visitorchat_help'));

        add_action('admin_print_styles-' . $page, array($this, 'load_admin_css'));
        add_action('admin_print_styles-' . $subPageDiscussions, array($this, 'load_admin_css'));
        add_action('admin_print_styles-' . $subPageEnquiries, array($this, 'load_admin_css'));
        add_action('admin_print_styles-' . $subPageSettings, array($this, 'load_admin_css'));
        add_action('admin_print_styles-' . $subPageHelp, array($this, 'load_admin_css'));
    }

    /**
     * Injects the online-status icon into the user menu
     * @param string $translation
     * @param type $text
     * @param type $domain
     * @return string
     */
    public function injectUserMenuChatStatus($translation, $text, $domain)
    {
        if ($text == 'Howdy, %1$s')
        {
            $title = __('Your chat-status is online', 'visitorchat');
            $translation = '<img src="' . plugins_url('visitorchat/img/admin/visitorchat-icon.png') . '" width="16" height="16" style="border:0;background:transparent" alt="' . $title . '" title="' . $title . '" /> ' . $translation;
        }

        return $translation;
    }

    /**
     * Load the admin stylesheet
     */
    public function load_admin_css()
    {
        wp_enqueue_style('cevcBootstrap');
        wp_enqueue_style('cevcAdminStylesheet');
    }

    /**
     * Handles the VisitorChat shortcode
     * @param type $attributes
     * @param type $content
     * @return string
     */
    function visitorChatShortCode($attributes, $content = null)
    {
        extract(shortcode_atts(array('status' => 'online'), $attributes));

        if ($status == 'online')
        {
            if ($this->User->checkModeratorsOnline())
            {
                return $content;
            }
            else
            {
                return '';
            }
        }
        else
        {
            if (!$this->User->checkModeratorsOnline())
            {
                return $content;
            }
            else
            {
                return '';
            }
        }
    }

    /**
     * Render the chat's dashboard
     */
    public function visitorchat_dashboard()
    {
        require_once $this->dir . 'pages' . DIRECTORY_SEPARATOR . 'admin_dashboard.php';
    }

    /**
     * Render the settings page
     */
    public function visitorchat_settings()
    {
        require_once $this->dir . 'pages' . DIRECTORY_SEPARATOR . 'admin_settings.php';
    }

    /**
     * Render the settings page
     */
    public function visitorchat_enquiries()
    {
        if ($this->Request->query('view') != '')
        {
            require_once $this->dir . 'pages' . DIRECTORY_SEPARATOR . 'admin_enquiries_view.php';
        }
        else
        {
            require_once $this->dir . 'pages' . DIRECTORY_SEPARATOR . 'admin_enquiries.php';
        }
    }

    /**
     * Render the settings page
     */
    public function visitorchat_discussions()
    {
        if ($this->Request->query('view') != '')
        {
            require_once $this->dir . 'pages' . DIRECTORY_SEPARATOR . 'admin_discussions_view.php';
        }
        else
        {
            require_once $this->dir . 'pages' . DIRECTORY_SEPARATOR . 'admin_discussions.php';
        }
    }

    /**
     * Render the Help & About page
     */
    public function visitorchat_help()
    {
        require_once $this->dir . 'pages' . DIRECTORY_SEPARATOR . 'admin_help.php';
    }

    /**
     * Admin action handling enquiry deletion
     */
    public function cevc_wpadmin_enquiry_delete()
    {
        if ($this->Request->is('post') && $this->Request->data('delete_id') !== null)
        {
            $deleteIds = array();
            if (!is_array($this->Request->data('delete_id')))
            {
                $deleteIds[] = $this->Request->data('delete_id');
            }
            else
            {
                $deleteIds = $this->Request->data('delete_id');
            }

            $success = true;
            foreach ($deleteIds as $deleteId)
            {
                if (!$this->Enquiry->deleteByCondition('id', $deleteId))
                {
                    $success = false;
                }
            }

            if ($success === true)
            {
                $this->Session->setFlash(__('The enquiry was deleted.', 'visitorchat'));
                $this->Request->redirect(admin_url('admin.php?page=visitorchat-enquiries'));
            }
        }

        $this->Session->setFlash(__('The enquiry could not be deleted.', 'visitorchat'));
        $this->Request->redirect($this->Request->referer());
    }

    /**
     * Admin action handling discussion deletion
     */
    public function cevc_wpadmin_discussion_delete()
    {
        if ($this->Request->is('post') && $this->Request->data('delete_id') !== null)
        {
            $deleteIds = array();
            if (!is_array($this->Request->data('delete_id')))
            {
                $deleteIds[] = $this->Request->data('delete_id');
            }
            else
            {
                $deleteIds = $this->Request->data('delete_id');
            }

            $success = true;
            foreach ($deleteIds as $deleteId)
            {
                if (!$this->Discussion->deleteByCondition('id', $deleteId))
                {
                    $success = false;
                }
                if (!$this->Message->deleteByCondition('discussion_id', $deleteId))
                {
                    $success = false;
                }
            }

            if ($success === true)
            {
                $this->Session->setFlash(__('The discussion was deleted.', 'visitorchat'));
                $this->Request->redirect(admin_url('admin.php?page=visitorchat-discussions'));
            }
        }

        $this->Session->setFlash(__('The discussion could not be deleted.', 'visitorchat'));
        $this->Request->redirect($this->Request->referer());
    }

    /**
     * Sanitises all settings' input-data
     * @param array $input The unsanitised data
     * @return array The sanitised data
     */
    public function validate_options($input)
    {
        if (isset($input['layout_position']) && !in_array($input['layout_position'], array('left', 'right')))
        {
            $input['layout_position'] = 'right';
        }

        if (isset($input['gravatar_default']) && !in_array($input['gravatar_default'], array('identicon', 'mm')))
        {
            if (strpos($input['gravatar_default'], 'http') === false)
            {
                $input['gravatar_default'] = 'identicon';
            }
        }

        if (isset($input['first_message']))
        {
            $input['first_message'] = trim($input['first_message']);
        }

        $input['save_count'] = intval($this->get_option('save_count')) + 1;

        if (!$this->writeCacheFiles())
        {
            $this->Session->setFlash(__('The cache files could not be saved. Please make sure the temporary directory is writable.', 'visitorchat'));
        }

        return $input;
    }

    private function writeCacheFiles()
    {
        $tmpDir = CEVC::i()->dir . 'public_tmp' . DIRECTORY_SEPARATOR;

        $writtenSuccess = array();
        if (is_writable($tmpDir))
        {
            if (!file_exists($tmpDir . 'visitorchat.js') || is_writable($tmpDir . 'visitorchat.js'))
            {
                if (file_put_contents($tmpDir . 'visitorchat.js', $this->renderJavaScript()) !== false)
                {
                    $writtenSuccess[] = true;
                }
            }

            if (!file_exists($tmpDir . 'visitorchat.css') || is_writable($tmpDir . 'visitorchat.css'))
            {
                if (file_put_contents($tmpDir . 'visitorchat.css', $this->renderCSS()) !== false)
                {
                    $writtenSuccess[] = true;
                }
            }
        }

        return count($writtenSuccess) == 2;
    }

}

CEVC::i();