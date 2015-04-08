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
 * The main AppModel which is inherited by the actual VisitorChat entities
 */
class CEVC_AppModel
{

    /**
     * Holds the model's name for aliasing-purposes
     * @var string
     */
    public $name = null;

    /**
     * Holds the last insert id
     * @var int 
     */
    public $id = null;

    /**
     * Holds the global table prefix
     * @var string 
     */
    public $tablePrefix = 'cevc_';

    /**
     * Holds the database table for the model (prefixed with )
     * @var string 
     */
    public $useTable = null;

    /**
     * Holds the data currenty being saved
     * @var array
     */
    public $data = array();

    /**
     * Holds the table's columns and data types
     * @var array
     */
    public $fields = array();

    /**
     * Contains the full table name with all prefixes
     * @var string
     */
    public $fullTableName = null;

    /**
     * Constructor
     * @global wpdb $wpdb
     */
    public function __construct()
    {
        global $wpdb;
        $wpdb->{$wpdb->prefix . $this->tablePrefix . $this->useTable} = $this->fullTableName = $wpdb->prefix . $this->tablePrefix . $this->useTable;
    }

    /**
     * Checks whether the specified primary key exists
     * @param int $id
     * @return boolean
     */
    public function exists($id)
    {
        global $wpdb;
        $count = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) 
            FROM $this->fullTableName
            AS `$this->name`   
            WHERE `$this->name`.`id` = %d", $id));

        return $count > 0;
    }

    /**
     * Returns a single record by its id
     * @global wpdb $wpdb
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        global $wpdb;
        $result = $wpdb->get_row($wpdb->prepare("
            SELECT * 
            FROM $this->fullTableName
            AS `$this->name`   
            WHERE `$this->name`.`id` = %d", $id), ARRAY_A);

        return $result;
    }

    /**
     * Returns all records
     * @global wpdb $wpdb
     * @return array
     */
    public function findAll()
    {
        global $wpdb;
        $result = $wpdb->get_results("
            SELECT * 
            FROM $this->fullTableName
            AS `$this->name`   
            WHERE 1 = 1", ARRAY_A);

        return $result;
    }

    /**
     * Deletes all records matching the simple condition
     * @global wpdb $wpdb
     * @param type $field
     * @param type $value
     */
    public function deleteByCondition($field, $value, $format = '%d')
    {
        global $wpdb;

        if ($wpdb->delete($this->fullTableName, array($field => $value), array($format)) === false)
        {
            return false;
        }

        return true;
    }

    /**
     * Returns the respective field for the currently set id
     * @global wpdb $wpdb
     * @param string $field
     * @return string/null
     */
    public function field($field)
    {
        global $wpdb;
        $result = $wpdb->get_var($wpdb->prepare("
            SELECT
            `$this->name`.`$field`
            FROM $this->fullTableName
            AS `$this->name`   
            WHERE `$this->name`.`id` = %d", $this->id));

        return $result;
    }

    /**
     * Checks whether the model has this column
     * @param string $field
     * @return type
     */
    public function hasField($field)
    {
        return array_key_exists($field, $this->fields);
    }

    /**
     * Sets the data and removes indices that are not in the fieldList
     * @param type $data
     * @param type $options
     */
    public function setData($data = array(), $options = array())
    {
        if (isset($options['fieldList']) && is_array($options['fieldList']))
        {
            foreach ($data as $key => $val)
            {
                if (!in_array($key, $options['fieldList']))
                {
                    unset($data[$key]);
                }
            }
        }

        $this->data = $data;
    }

    /**
     * Saves the data
     * @global wpdb $wpdb
     * @param type $data
     * @param type $options
     * @return boolean
     */
    public function save($data = array(), $options = array())
    {
        $this->setData($data, $options);


        if ($this->hasField('user_id') && !isset($this->data['id']) && !array_key_exists('user_id', $this->data))
        {
            $this->data['user_id'] = CEVC::i()->User->getUserId();
        }


        if (!isset($options['novalidate']) && !$this->validates())
        {
            return false;
        }

        global $wpdb;

        if (isset($this->data['id'])) // UPDATE
        {
            if (array_key_exists('modified', $this->fields))
            {
                $this->data['modified'] = date('Y-m-d H:i:s');
            }

            if ($wpdb->update($wpdb->{$this->fullTableName}, $this->data, array('id' => $this->data['id']), $this->getFieldTypes($this->data), array('id' => '%d')) === false)
            {
                return false;
            }

            $this->id = $this->data['id'];

            $this->afterSave(false);

            return true;
        }
        else // CREATE
        {
            if (array_key_exists('modified', $this->fields))
            {
                $this->data['modified'] = date('Y-m-d H:i:s');
            }
            if (array_key_exists('created', $this->fields))
            {
                $this->data['created'] = date('Y-m-d H:i:s');
            }

            if ($wpdb->insert($wpdb->{$this->fullTableName}, $this->data, $this->getFieldTypes($this->data)) === false)
            {
                return false;
            }

            $this->id = $wpdb->insert_id;

            $this->afterSave(true);

            return true;
        }
    }

    /**
     * Called after saving operations
     * @param boolean $created
     */
    public function afterSave($created)
    {
        
    }

    /**
     * Returns the correct data types for the data array (%d, %s and %f)
     * @param type $data
     * @return type
     */
    public function getFieldTypes($data = array())
    {
        $types = array();

        foreach ($data as $key => $val)
        {
            $types[] = $this->fields[$key];
        }

        return $types;
    }

    /**
     * Contains the model's validation rules
     * @var array Array of validation rules
     */
    public $validationRules = array();

    /**
     * Validates the currently set data
     * @param type $fields
     * @return type
     */
    public function validates($fields = array())
    {
        $errors = array();

        if (empty($fields)) // validate all
        {
            $fields = array_keys($this->validationRules);
        }

        foreach ($fields as $field)
        {
            if (isset($this->validationRules[$field]))
                foreach ($this->validationRules[$field] as $rule => $options)
                {
                    $valid = $this->{'val_' . $rule}($field, $rule, $options);
                    if ($valid === false)
                    {
                        if (!isset($errors[$field]))
                        {
                            $errors[$field] = array();
                        }

                        if (isset($options['message']))
                        {
                            $errors[$field][] = $options['message'];
                        }
                        else
                        {
                            $errors[$field][] = $rule;
                        }
                    }
                }
        }
        $this->validationErrors = $errors;
        return empty($this->validationErrors);
    }

    /**
     * Holds the current save-operation's validation errors
     * @var array
     */
    public $validationErrors = array();

    /**
     * Validates that the given field is not empty
     * @param type $field
     * @param type $rule
     * @param type $options
     * @return boolean
     */
    public function val_notempty($field = null, $rule = null, $options = array())
    {
        $isValid = true;

        if (!isset($this->data[$field]) || is_array($this->data[$field]))
        {
            return false;
        }

        $value = (isset($options['trim']) && $options['trim'] === true) ? trim($this->data[$field]) : $this->data[$field];

        if ($value == '')
        {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * Validates that the given field is a valid email address
     * @param type $field
     * @param type $rule
     * @param type $options
     * @return boolean
     */
    public function val_email($field = null, $rule = null, $options = array())
    {
        $value = (isset($options['trim']) && $options['trim'] === true) ? trim($this->data[$field]) : $this->data[$field];

        if (is_email($value) === false)
        {
            return false;
        }

        return true;
    }

    /**
     * Validates the passed value's length
     * @param type $field
     * @param type $rule
     * @param type $options
     * @return boolean
     */
    public function val_maxLength($field = null, $rule = null, $options = array())
    {
        $value = (isset($options['trim']) && $options['trim'] === true) ? trim($this->data[$field]) : $this->data[$field];

        if (mb_strlen($value) <= $options['length'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Validates the passed value's length
     * @param type $field
     * @param type $rule
     * @param type $options
     * @return boolean
     */
    public function val_minLength($field = null, $rule = null, $options = array())
    {
        $value = (isset($options['trim']) && $options['trim'] === true) ? trim($this->data[$field]) : $this->data[$field];

        if (mb_strlen($value) >= $options['length'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}
