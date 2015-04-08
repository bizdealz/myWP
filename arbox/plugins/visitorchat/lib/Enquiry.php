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
 * The main model providing the data access layer to Enquiries
 */
class CEVC_Enquiry extends CEVC_AppModel
{

    public $name = 'Enquiry';

    /**
     * The table name
     * @var string
     */
    public $useTable = 'enquiries';

    /**
     * List of fields and data types
     * @var array
     */
    public $fields = array(
        'id' => '%d',
        'username' => '%s',
        'email' => '%s',
        'message' => '%s',
        'read' => '%d',
        'current_page' => '%s',
        'referer' => '%s',
        'visitor_languages' => '%s',
        'user_agent' => '%s',
        'remote_address' => '%s',
        'created' => '%s',
    );

    /**
     *
     * @var array
     */
    public $validationRules = array(
        'username' => array(
            'notempty' => array('trim' => true, 'message' => ''),
            'maxLength' => array('trim' => true, 'message' => '', 'length' => 45),
        ),
        'email' => array(
            'notempty' => array('trim' => true, 'message' => ''),
            'email' => array('trim' => true, 'message' => ''),
            'maxLength' => array('trim' => true, 'message' => '', 'length' => 85),
        ),
        'message' => array(
            'notempty' => array('trim' => true, 'message' => ''),
            'maxLength' => array('trim' => true, 'message' => '', 'length' => 1450),
        ),
    );

    public function __construct()
    {
        parent::__construct();

        $this->validationRules['username']['notempty']['message'] = CEVC::getTranslations('ValidationUsernameRequired');
        $this->validationRules['username']['maxLength']['message'] = CEVC::getTranslations('ValidationUsernameMaxLength');

        $this->validationRules['email']['notempty']['message'] = CEVC::getTranslations('ValidationEmailRequired');
        $this->validationRules['email']['email']['message'] = CEVC::getTranslations('ValidationEmailInvalid');
        $this->validationRules['email']['maxLength']['message'] = CEVC::getTranslations('ValidationEmailMaxLength');

        $this->validationRules['message']['notempty']['message'] = CEVC::getTranslations('ValidationEnquiryRequired');
        $this->validationRules['message']['maxLength']['message'] = CEVC::getTranslations('ValidationEnquiryMaxLength');
    }

    public function save($data = array(), $options = array())
    {
        if (!parent::save($data, $options))
        {
            return false;
        }

        return true;
    }

    public function afterSave($created)
    {
        if ($created === true)
        {
            if (CEVC::i()->isDemo === false)
            {
                $this->sendNotificationEmail($this->id);
            }
        }
    }

    /**
     * Sends the notification email
     * @global wpdb $wpdb
     */
    private function sendNotificationEmail($enquiry_id = null)
    {
        $enquiry = $this->findById($enquiry_id);

        $eSubject = CEVC_String::insert(__('Visitor Chat: You Received a New Enquiry from :username', CEVC::$name), array('username' => $enquiry['username']));
        $eContent = CEVC_String::insert(__("You just received a new enquiry through Visitor Chat. \nView the enquiry: :enquiry_url \n\n\nSender: :sender_name \nEmail: :sender_email \n\nMessage: \n:enquiry"), array(
                    'enquiry_url' => null,
                    'sender_name' => $enquiry['username'],
                    'sender_email' => $enquiry['email'],
                    'enquiry' => $enquiry['message'],
        ));
        $eFrom = 'From: ' . $enquiry['username'] . ' <' . $enquiry['email'] . '>' . "\r\n";
        $eTo = CEVC::i()->get_option('enquiry_recipient');

        if ($eTo !== null)
        {
            wp_mail($eTo, $eSubject, $eContent, $eFrom);
        }
    }

}
