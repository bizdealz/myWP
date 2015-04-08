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
 * The main model providing the data access layer to Messages
 */
class CEVC_Message extends CEVC_AppModel
{

    public $name = 'Message';

    /**
     * The table name
     * @var string
     */
    public $useTable = 'messages';

    /**
     * List of fields and data types
     * @var array
     */
    public $fields = array(
        'id' => '%d',
        'discussion_id' => '%d',
        'user_id' => '%d',
        'message' => '%s',
        'current_page' => '%s',
        'created' => '%s',
    );

    /**
     *
     * @var array
     */
    public $validationRules = array(
        'discussion_id' => array(
            'notempty' => array('trim' => true, 'message' => 'Discussion Id not passed.'),
        ),
        'message' => array(
            'notempty' => array('trim' => true, 'message' => ''),
            'maxLength' => array('trim' => true, 'message' => '', 'length' => 1500),
        ),
    );

    public function __construct()
    {
        parent::__construct();

        $this->validationRules['message']['notempty']['message'] = CEVC::getTranslations('ValidationMessageRequired');
        $this->validationRules['message']['maxLength']['message'] = CEVC::getTranslations('ValidationMessageMaxLength');
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
            if (CEVC::i()->Discussion->exists(CEVC::i()->Session->check(CEVC_SessionKeys::VisitorDiscussionId)))
            {
                CEVC::i()->Discussion->save(array(
                    'id' => CEVC::i()->Session->read(CEVC_SessionKeys::VisitorDiscussionId),
                    'modified' => date('Y-m-d H:i:s')
                        ), array('novalidate' => true, 'fieldList' => array('id', 'modified')));
            }
        }
    }

    /**
     * 
     * @global wpdb $wpdb
     * @param type $discussion_id
     * @param type $params
     * @return type
     */
    public function getSinceLastRead($discussion_id, $params)
    {
        $lastId = $params['last_id'];
        $firstId = $params['first_id'];

        global $wpdb;
        if ($params['is_new_page'] == "true") // page reload: get the last 20 only...
        {
            $messages = $wpdb->get_results($wpdb->prepare("
                SELECT 
                    `Message`.`id`, 
                    `Message`.`message`, 
                    `Message`.`created`, 
                    `User`.`display_name`,
                    `User`.`user_email`,
                    `User`.`ID`
                FROM $this->fullTableName AS `Message` 
                LEFT JOIN $wpdb->users
                AS `User` ON (`Message`.`user_id` = `User`.`ID`)  
                WHERE 
                    `Message`.`discussion_id` = %d 
                AND 
                    `Message`.`id` > %d   
                ORDER BY `Message`.`id` 
                DESC  LIMIT 20                
                ", array($discussion_id, $lastId)), ARRAY_A);
        }
        else if ($params['load_more'] == "true") // loadMore: get the 20 before the lowest ID
        {
            $messages = $wpdb->get_results($wpdb->prepare("
                SELECT 
                    `Message`.`id`, 
                    `Message`.`message`, 
                    `Message`.`created`, 
                    `User`.`display_name`,
                    `User`.`user_email`,
                    `User`.`ID`
                FROM $this->fullTableName AS `Message` 
                LEFT JOIN $wpdb->users
                AS `User` ON (`Message`.`user_id` = `User`.`ID`)  
                WHERE 
                    `Message`.`discussion_id` = %d 
                AND 
                    `Message`.`id` < %d   
                ORDER BY `Message`.`id` 
                DESC  LIMIT 20                
                ", array($discussion_id, $firstId)), ARRAY_A);
        }
        else
        {
            $messages = $wpdb->get_results($wpdb->prepare("
                SELECT 
                    `Message`.`id`, 
                    `Message`.`message`, 
                    `Message`.`created`, 
                    `User`.`display_name`,
                    `User`.`user_email`,
                    `User`.`ID`
                FROM $this->fullTableName AS `Message` 
                LEFT JOIN $wpdb->users
                AS `User` ON (`Message`.`user_id` = `User`.`ID`)  
                WHERE 
                    `Message`.`discussion_id` = %d 
                AND 
                    `Message`.`id` > %d            
                ", array($discussion_id, $lastId)), ARRAY_A);
        }

        return $messages;
    }

    /**
     * 
     * @global wpdb $wpdb
     * @param type $discussion_id
     * @param type $first_id
     * @return type
     */
    public function adminReadMore($discussion_id, $first_id)
    {
        global $wpdb;
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
                        $this->fullTableName
                        AS `Message` 
                     LEFT JOIN 
                        $wpdb->users 
                     AS `User` ON (`Message`.`user_id` = `User`.`ID`)  
                     WHERE `Message`.`discussion_id` = %d AND `Message`.`id` < %d   
                     ORDER BY `Message`.`id` DESC  LIMIT 20", array($discussion_id, $first_id)), ARRAY_A);


        foreach ($messages as &$message)
        {
            if (isset($message['display_name']))
            {
                $message['User'] = array(
                    'username' => $message['display_name'],
                    'avatar' => md5($message['user_email']),
                );
                unset($message['display_name'], $message['user_email']);
            }
            $nMessage = array('Message' => $message);
            $message = $nMessage;
        }

        CEVC::i()->Discussion->id = $discussion_id;
        $username = CEVC::i()->Discussion->field('username');
        $email = CEVC::i()->Discussion->field('email');

        foreach ($messages as &$message)
        {
            if (!isset($message['User']['username']))
            {
                $message['User']['username'] = $username;
                $message['User']['avatar'] = md5($email);
            }

            $message['Message']['created'] = date('m/d/Y H:i:s e', strtotime($message['Message']['created']));
        }

        return $messages;
    }

}
