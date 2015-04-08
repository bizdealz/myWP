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
 * The main model providing the data access layer to Discussions
 */
class CEVC_Discussion extends CEVC_AppModel
{

    public $name = 'Discussion';

    /**
     * The table name
     * @var string
     */
    public $useTable = 'discussions';

    /**
     * List of fields and data types
     * @var array
     */
    public $fields = array(
        'id' => '%d',
        'uid' => '%s',
        'username' => '%s',
        'email' => '%s',
        'referer' => '%s',
        'visitor_time' => '%s',
        'visitor_languages' => '%s',
        'user_agent' => '%s',
        'remote_address' => '%s',
        'visitor_exited' => '%d',
        'composing_visitor_date' => '%s',
        'composing_user_date' => '%s',
        'composing_user_id' => '%d',
        'created' => '%s',
        'modified' => '%s',
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
    );

    public function __construct()
    {
        parent::__construct();

        $this->validationRules['username']['notempty']['message'] = CEVC::getTranslations('ValidationUsernameRequired');
        $this->validationRules['username']['maxLength']['message'] = CEVC::getTranslations('ValidationUsernameMaxLength');

        $this->validationRules['email']['notempty']['message'] = CEVC::getTranslations('ValidationEmailRequired');
        $this->validationRules['email']['email']['message'] = CEVC::getTranslations('ValidationEmailInvalid');
        $this->validationRules['email']['maxLength']['message'] = CEVC::getTranslations('ValidationEmailMaxLength');
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
            CEVC::i()->Session->write(CEVC_SessionKeys::VisitorDiscussionId, $this->id);
            CEVC::i()->Session->write(CEVC_SessionKeys::VisitorEmail, $this->data['email']);
            CEVC::i()->Session->write(CEVC_SessionKeys::VisitorUsername, $this->data['username']);

            if (false && trim(CEVC::i()->get_option('first_message')) !== '')
            {
                // TODO: include new functionality once the sender can be selected
                $firstMessage = str_replace('{Username}', $this->field('username'), CEVC::i()->get_option('first_message'));
                CEVC::i()->Message->save(array(
                    'message' => $firstMessage,
                    'discussion_id' => $this->id
                ));
            }

            if (CEVC::i()->isDemo)
            {
                $firstMessage = str_replace('{Username}', $this->field('username'), 'Hello {Username}, thanks for giving VisitorChat a try. Don\'t forget to give the admin-dashboard a try by opening the following page in a new tab: http://visitorchat-wordpress.clientengage.com/wp-admin/ ');
                CEVC::i()->Message->save(array(
                    'message' => $firstMessage,
                    'user_id' => 1,
                    'discussion_id' => $this->id
                ));
            }
        }
    }

    /**
     * 
     * @global wpdb $wpdb
     * @param type $discussion_id
     * @return type
     */
    public function getDiscussionForVisitorRead($discussion_id)
    {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("
            SELECT 
                `Discussion`.`composing_user_date`, 
                `User`.`display_name`, 
                `User`.`ID` 
            FROM $this->fullTableName AS `Discussion` 
            LEFT JOIN $wpdb->users AS `User` 
            ON (`Discussion`.`composing_user_id` = `User`.`ID`)  
            WHERE `Discussion`.`id` = %s    LIMIT 1", array($discussion_id)), ARRAY_A);
    }

    /**
     * 
     * @global wpdb $wpdb
     * @param type $since
     * @param type $activeIds
     * @return type
     */
    public function adminReadDiscussions($since = '', $activeIds = array())
    {
        global $wpdb;

        $discussions = array();
        if (!empty($activeIds))
        {
            $activeIdsCond = implode(',', $activeIds);
            $discussions = $wpdb->get_results($wpdb->prepare("
                SELECT 
                        `Discussion`.`id`, 
                        `Discussion`.`uid`, 
                        `Discussion`.`username`, 
                        `Discussion`.`email`, 
                        `Discussion`.`referer`, 
                        `Discussion`.`visitor_time`, 
                        `Discussion`.`visitor_languages`, 
                        `Discussion`.`user_agent`, 
                        `Discussion`.`remote_address`, 
                        `Discussion`.`visitor_exited`, 
                        `Discussion`.`composing_visitor_date`, 
                        `Discussion`.`composing_user_date`, 
                        `Discussion`.`composing_user_id`, 
                        `Discussion`.`modified`, 
                        `Discussion`.`created` 
                FROM $this->fullTableName AS `Discussion`   
                WHERE (
                        (((`Discussion`.`modified` > %s)  
                        AND  (`Discussion`.`visitor_exited` = '0'))) 
                        OR (`Discussion`.`id` IN ($activeIdsCond))
                )", array($since)), ARRAY_A);
        }
        else
        {
            $discussions = $wpdb->get_results($wpdb->prepare("
                SELECT 
                        `Discussion`.`id`, 
                        `Discussion`.`uid`, 
                        `Discussion`.`username`, 
                        `Discussion`.`email`, 
                        `Discussion`.`referer`, 
                        `Discussion`.`visitor_time`, 
                        `Discussion`.`visitor_languages`, 
                        `Discussion`.`user_agent`, 
                        `Discussion`.`remote_address`, 
                        `Discussion`.`visitor_exited`, 
                        `Discussion`.`composing_visitor_date`, 
                        `Discussion`.`composing_user_date`, 
                        `Discussion`.`composing_user_id`, 
                        `Discussion`.`modified`, 
                        `Discussion`.`created` 
                FROM $this->fullTableName 
                AS `Discussion`   
                WHERE ((`Discussion`.`modified` > %s)  
                AND  (`Discussion`.`visitor_exited` = '0'))", array($since)), ARRAY_A);
        }


        foreach ($discussions as &$discussion)
        {
            $discussion['visitor_exited'] = $discussion['visitor_exited'] == "1" ? true : false;

            $tmpDisc = $discussion;
            $discussion = array();
            $discussion['Discussion'] = $tmpDisc;

            $modified = strtotime($discussion['Discussion']['modified']);
            $diff = strtotime('-10 minutes', time()) - $modified;
            $minutes = $diff / 60;
            $discussion['Discussion']['has_expired'] = ($minutes >= 0) || $discussion['Discussion']['visitor_exited'];

            $discussion['Discussion']['composing'] = time() - (3) < strtotime($discussion['Discussion']['composing_visitor_date']) ? true : false;

            CEVC::i()->__addActiveDiscussion($discussion['Discussion']['id']);

            $lastId = 0;
            $reqData = CEVC::i()->Request->data('data');
            if (isset($reqData['Discussion']))
            {
                foreach ($reqData['Discussion'] as $rD)
                {
                    if ($rD['discussion_id'] == $discussion['Discussion']['id'])
                    {
                        $lastId = $rD['last_id'];
                        break;
                    }
                }
            }

            $discId = $discussion['Discussion']['id'];
            $messageTable = CEVC::i()->Message->fullTableName;
            $messages = array();
            if (CEVC::i()->Request->data('data.is_new_page') == 'true')
            {
                $messages = $wpdb->get_results($wpdb->prepare("
                    SELECT 
                        `Message`.`id`, 
                        `Message`.`discussion_id`, 
                        `Message`.`user_id`, 
                        `Message`.`message`, 
                        `Message`.`current_page`, 
                        `Message`.`created`, 
                        `User`.`display_name`, 
                        `User`.`user_email` 
                     FROM 
                        $messageTable
                        AS `Message` 
                     LEFT JOIN 
                        $wpdb->users 
                     AS `User` ON (`Message`.`user_id` = `User`.`ID`)  
                     WHERE `Message`.`discussion_id` = %d AND `Message`.`id` > %d   
                     ORDER BY `Message`.`id` DESC  LIMIT 20", array($discId, $lastId)), ARRAY_A);
            }
            else // get all newest
            {
                $messages = $wpdb->get_results($wpdb->prepare("
                    SELECT 
                        `Message`.`id`, 
                        `Message`.`discussion_id`, 
                        `Message`.`user_id`, 
                        `Message`.`message`, 
                        `Message`.`current_page`, 
                        `Message`.`created`, 
                        `User`.`display_name`, 
                        `User`.`user_email` 
                     FROM 
                        $messageTable
                     AS `Message` 
                     LEFT JOIN 
                        $wpdb->users 
                     AS `User` ON (`Message`.`user_id` = `User`.`ID`)  
                     WHERE `Message`.`discussion_id` = %d AND `Message`.`id` > %d", array($discId, $lastId)), ARRAY_A);
            }

            foreach ($messages as &$message)
            {
                $message = array('Message' => $message);
                if (isset($message['Message']['display_name']))
                {
                    $message['User'] = array(
                        'username' => $message['Message']['display_name'],
                        'email' => $message['Message']['user_email'],
                    );
                    unset($message['display_name'], $message['user_email']);
                }
            }

            $discussion['Message'] = $messages;
        }

        foreach ($discussions as &$disc)
        {
            foreach ($disc['Message'] as &$message)
            {
                if (!isset($message['User']['username']))
                {
                    $message['User']['username'] = $disc['Discussion']['username'];
                    $message['User']['avatar'] = md5(strtolower(trim($disc['Discussion']['email'])));
                }
                else
                {
                    $message['User']['avatar'] = md5(strtolower(trim($message['User']['email'])));
                }
                unset($message['User']['email']);

                $message['Message']['created'] = date('m/d/Y H:i:s e', strtotime($message['Message']['created']));
            }

            $disc['Discussion']['created'] = date('m/d/Y H:i:s e', strtotime($disc['Discussion']['created']));
            $disc['Discussion']['modified'] = date('m/d/Y H:i:s e', strtotime($disc['Discussion']['modified']));

            $phpUserAgent = new phpUserAgent($disc['Discussion']['user_agent']);
            $browserData = $phpUserAgent->toArray();
            $browserData['raw_user_agent'] = $disc['Discussion']['user_agent'];
            $browserData['browser_name'] = ucfirst($browserData['browser_name']);
            $browserData['operating_system'] = ucfirst($browserData['operating_system']);
            $disc['Discussion']['user_agent'] = $browserData;

            $disc['Discussion']['visitor_languages'] = CEVC_Arrays::getLanguageCodeNameArray(explode(',', $disc['Discussion']['visitor_languages']));
        }

        return $discussions;
    }

    public function findAllMessagesById($discussion_id = null)
    {
        global $wpdb;
        $messagesTable = CEVC::i()->Message->fullTableName;
        $messages = $wpdb->get_results($wpdb->prepare("
            SELECT Message.*, user_email, display_name FROM $messagesTable AS Message 
            LEFT JOIN $wpdb->users 
            AS `User` ON (`Message`.`user_id` = `User`.`ID`)              
            WHERE Message.discussion_id = %d
", array($discussion_id)), ARRAY_A);

        foreach ($messages as &$message)
        {
            if ($message['display_name'] !== null)
            {
                $message['User'] = array(
                    'username' => $message['display_name'],
                    'email' => $message['user_email'],
                );
                unset($message['display_name'], $message['user_email']);
            }
        }

        return $messages;
    }

}
