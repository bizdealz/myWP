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
 * Manages information related to user's requests.
 */
class CEVC_RequestEngine
{

    /**
     * Accesses query-string parameters
     * @param string $key
     * @return mixed
     */
    public function query($key = null)
    {
        if ($key === null)
        {
            return $_GET;
        }

        return CEVC_Utilities::get($_GET, $key);
    }

    /**
     * Accesses POST data
     * @param string $key
     * @return mixed
     */
    public function data($key = null)
    {
        if ($key === null)
        {
            return $_POST;
        }

        return CEVC_Utilities::get($_POST, $key);
    }

    /**
     * Returns the client's remote address (IP)
     * @return type
     */
    public function remoteAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Returns the client's referer
     * @return string
     */
    public function referer()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    /**
     * Returns the client's user agent string
     * @return string
     */
    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Returns the remote client's accepted language codes
     * @return array
     */
    public function acceptLanguage()
    {
        $headerValues = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

        $acceptedLanguages = $matched = array();

        foreach ($headerValues as $lang)
        {
            $cnt = preg_match('/([-a-zA-Z]+)\s*;\s*q=([0-9\.]+)/', $lang, $matched);
            if ($cnt === 0)
            {
                $acceptedLanguages[$lang] = 1;
            }
            else
            {
                $acceptedLanguages[$matched[1]] = $matched[2];
            }
        }

        $langOnly = array();
        foreach (array_keys($acceptedLanguages) as $lang)
        {
            $expl = explode('-', $lang);
            $langOnly[] = $expl[0];
        }

        return array_unique($langOnly);
    }

    /**
     * Checks teh request type
     * @param string $type
     */
    public function is($type = '')
    {
        return strtolower($_SERVER['REQUEST_METHOD']) === strtolower($type);
    }

    /**
     * Redirects the client's browser to the given location
     * @param type $destination
     */
    public function redirect($destination)
    {
        wp_redirect($destination);
        exit();
    }

}

/**
 * Handles the export & import of VisitorChat settings and data
 */
class CEVC_Exporter
{

    /**
     * Imports all VisitorChat data & settings from a file
     */
    public function import()
    {
        // TODO: finish-off implementation
        // TODO: automatically get all last_insert_ids and increment everything accordingly to avoid conflicts
    }

    /**
     * Handles the export of all VisitorChat data
     */
    public function export()
    {
        if (current_user_can('manage_options'))
        {
            $this->generateXml(CEVC::i()->get_option(null), CEVC::i()->Discussion->findAll(), CEVC::i()->Message->findAll(), CEVC::i()->Enquiry->findAll());
        }
    }

    /**
     * Creates the actual XML document
     * @param array $settings
     * @param array $discussions
     * @param array $messages
     * @param array $enquiries
     */
    public function generateXml($settings = array(), $discussions = array(), $messages = array(), $enquiries = array())
    {
        $fileName = 'VisitorChat Backup (' . date('Y-m-d') . ')';
        header("Content-Disposition: attachment; filename=\"$fileName.xml\"");

        $xDoc = new DOMDocument('1.0');

        $root = $xDoc->createElement('data');
        $root->setAttribute('product', 'VisitorChat');
        $root->setAttribute('version', CEVC::$version);
        $xDoc->appendChild($root);

        $toExport = array(
            'settings' => $settings,
            'discussions' => $discussions,
            'messages' => $messages,
            'enquiries' => $enquiries
        );

        foreach ($toExport as $xName => $data)
        {
            $exportNote = $xDoc->createElement($xName);
            $nodeContent = $xDoc->createTextNode(json_encode($data));
            $exportNote->appendChild($nodeContent);
            $root->appendChild($exportNote);
        }

        $xDoc->formatOutput = true;

        die($xDoc->saveXML());
    }

}

/**
 * Contains a variety of useful functionality that other classes
 * make use of.
 */
class CEVC_Utilities
{

    /**
     * returns the given index if found; otherwise returns null
     * @param array $data
     * @param type $path
     * @return null
     */
    public static function get(array &$data, $path)
    {
        $resultArray = $data;

        $keyChain = explode('.', $path);
        foreach ($keyChain as $key)
        {
            if (isset($resultArray[$key]))
            {
                $resultArray = $resultArray[$key];
            }
            else
            {
                return null;
            }
        }

        return $resultArray;
    }

    /**
     * Sets data to teh given index
     * @param array $data
     * @param type $path
     * @param type $value
     */
    public static function set(array &$data, $path, $value)
    {
        $keyChain = explode('.', $path);
        $lastMatch = array_pop($keyChain);
        foreach ($keyChain as $key)
        {
            if (isset($data[$key]) && is_array($data[$key]))
            {
                $data = & $data[$key];
            }
            else
            {
                $data[$key] = array();
                $data = & $data[$key];
            }
        }
        $data[$lastMatch] = $value;
    }

    /**
     * Checks whether the given index exists
     * @param array $data
     * @param type $path
     * @return boolean
     */
    public static function check(array &$data, $path)
    {
        $keyChain = explode('.', $path);
        $resultArray = $data;
        foreach ($keyChain as $key)
        {
            if (isset($resultArray[$key]))
            {
                $resultArray = $resultArray[$key];
            }
            else
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Deletes the given index
     * @param array $data
     * @param type $path
     * @return type
     */
    public static function del(array &$data, $path)
    {
        $keyChain = explode('.', $path);
        $lastMatch = array_pop($keyChain);
        foreach ($keyChain as $key)
        {
            if (isset($data[$key]) && is_array($data[$key]))
            {
                $data = & $data[$key];
            }
            else
            {
                return;
            }
        }

        unset($data[$lastMatch]);
    }

    /**
     * Recursively removes all HTML and strips slashes
     * @param string $toClean
     * @return mixed
     */
    public static function clean($toClean)
    {
        if ($toClean === null)
        {
            return null;
        }

        if (is_string($toClean))
        {
            return stripslashes_deep(strip_tags($toClean));
        }

        if (is_array($toClean))
        {
            return stripslashes_deep(self::array_map_r('strip_tags', $toClean));
        }

        return stripslashes_deep($toClean);
    }

    /**
     * Recursively maps a given function to the passed array
     * @param type $func
     * @param type $arr
     * @return type
     */
    public static function array_map_r($func, $arr)
    {
        $newArr = array();
        foreach ($arr as $key => $value)
        {
            $newArr[$key] = ( is_array($value) ? self::array_map_r($func, $value) : ( is_array($func) ? call_user_func_array($func, $value) : $func($value) ) );
        }

        return $newArr;
    }

}

/**
 * Utility class for working with strings
 */
class CEVC_String
{

    /**
     * Inserts dynamic content into the passed string using ":replace" syntax
     * @param string $string
     * @param array $inserts
     * @return string
     */
    public static function insert($string = '', $inserts = array())
    {
        foreach ($inserts as $key => $val)
        {
            $string = str_replace(':' . $key, $val, $string);
        }

        return $string;
    }

    /**
     * Generates a UUID
     * @return string
     */
    public static function uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Generates a random string
     * @param int $length
     * @return string
     */
    public static function random($length = 5)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $randString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randString;
    }

}

/**
 * Helper classes to aid in the rendering of markup
 */
class CEVC_Helper
{

    /**
     * Returns the verbose version of a boolean value
     * @param boolean $bool The boolean value to be rendered verbose
     * @return string Yes or No (in the respective locale)
     */
    public function boolYesNo($bool = null)
    {
        if ($bool === null)
        {
            return '<span class="label">' . __('N/A') . '</span>';
        }

        if ($bool === true)
        {
            return '<span class="label label-success">' . __('Yes') . '</span>';
        }
        else
        {
            return '<span class="label label-important">' . __('No') . '</span>';
        }
    }

    /**
     * Utility method for rendering UserAgent strings
     * @param type $useragent
     * @param type $showTooltip
     * @return string
     */
    public function renderUserAgent($useragent = null, $showTooltip = true)
    {
        $phpUserAgent = new phpUserAgent($useragent);
        $browserData = $phpUserAgent->toArray();

        if (empty($browserData))
        {
            return $useragent;
        }
        else
        {
            $toolTip = '';

            if ($showTooltip)
            {
                $toolTip = ' data-original-title="' . $useragent . '" rel="popover" data-rel="tooltip"  style="display: inline-block; cursor: help;"';
            }

            $out = '<div' . $toolTip . '>';
            $out .= __('Browser') . ': ' . ucfirst($browserData['browser_name']) . ' (version: ' . $browserData['browser_version'] . ') | ';
            $out .= __('Operating System') . ': ' . ucfirst($browserData['operating_system']);
            $out .= '</div>';
            return $out;
        }
    }

    /**
     * Utility method for preparing the message text (smilies & auto-linking)
     * @param type $text
     * @return type
     */
    public function prepareMessageText($text = '')
    {
        if ($text == '')
        {
            return $text;
        }

        $smiliesMap = array(
            array('s' => array(' :-) ', ' :) '), 'r' => 'smiling'),
            array('s' => array(' :-( ', ' :( '), 'r' => 'frowning'),
            array('s' => array(' :-/ ', ' :/ '), 'r' => 'unsure'),
            array('s' => array(' ;-) ', ' ;) '), 'r' => 'winking'),
            array('s' => array(' :-D ', ' :D '), 'r' => 'grinning'),
            array('s' => array(' B-) ', ' B) '), 'r' => 'cool'),
            array('s' => array(' :-P ', ' :P '), 'r' => 'tongue_out'),
            array('s' => array(' :-| ', ' :| '), 'r' => 'speechless'),
            array('s' => array(' :-O ', ' :O '), 'r' => 'gasping'),
            array('s' => array(' X-( ', ' X( '), 'r' => 'angry'),
            array('s' => array(' O:-) ', ' O:) '), 'r' => 'angel'),
        );

        $text = ' ' . $text . ' ';

        foreach ($smiliesMap as $map)
        {
            foreach ($map['s'] as $search)
            {
                $smilieImg = ' <img src="' . CEVC::i()->url . '/img/chat/smilies/' . $map['r'] . '.png" alt="' . trim($search) . '" />';
                $text = str_replace($search, $smilieImg, $text);
            }
        }

        //   $text = $this->Text->autoLinkUrls($text, array('escape' => false, 'target' => '_blank'));
        //   $text = $this->Text->autoLinkEmails($text, array('escape' => false, 'target' => '_blank'));

        return trim($text);
    }

    /**
     * Utility method for rendering the Gravatar & visitorname
     * @param string $username
     * @param string $email
     * @param int $size The size of the Gravatar
     * @return string
     */
    public function renderVisitorname($username = '', $email = '', $size = 40)
    {
        $avatar = '<img src="http://www.gravatar.com/avatar/' . md5(strtolower($email)) . '?s=' . $size . '&d=' . urlencode(CEVC::i()->get_option('gravatar_default')) . '" width="' . $size . '" height="' . $size . '" alt="' . $username . '" class="avatar_image" />';
        return $avatar . ' <span class="visitorname">' . $username . '</span>';
    }

    /**
     * Utility method for rendering individual Gravatars
     * @param string $username
     * @param string $email
     * @param int $size The size of the Gravatar
     * @return string
     */
    public function renderGravatar($username = '', $email = '', $size = 40)
    {
        return '<img src="http://www.gravatar.com/avatar/' . md5(strtolower($email)) . '?s=' . $size . '&d=' . urlencode(CEVC::i()->get_option('gravatar_default')) . '" width="' . $size . '" height="' . $size . '" alt="' . $username . '" class="avatar_image" />';
    }

    /**
     * Truncates text after reaching the intended maximum length
     * @param type $inputString
     * @param type $maxLen
     * @return type
     */
    public function truncate($inputString, $maxLen, $suffix = '...')
    {
        $segments = preg_split('/([\s\n\r]+)/', $inputString, null, PREG_SPLIT_DELIM_CAPTURE);
        $numSegments = count($segments);

        $len = 0;
        $lastPart = 0;
        for (; $lastPart < $numSegments; ++$lastPart)
        {
            $len += strlen($segments[$lastPart]);
            if ($len > $maxLen)
            {
                break;
            }
        }

        $outputString = implode(array_slice($segments, 0, $lastPart));
        return $outputString . ( strlen($inputString) > strlen($outputString) ? $suffix : '' );
    }

    public function renderVisitorLanguages($langString = '')
    {
        $langCodes = explode(',', $langString);

        $langNames = CEVC_Arrays::getLanguageNameArrayFromCodes($langCodes);

        return implode(', ', $langNames);
    }

    public function renderFlash()
    {
        if (($message = CEVC::i()->Session->getFlash()) != '')
        {
            return '<div class="alert alert-error alert-block keepopen">' . $message . '</div>';
        }
    }

    public function adaptColourBrightness($originalColour, $percentage)
    {
        $originalColour = substr($originalColour, 1);
        $rgbColour = '';
        $percentage = $percentage / 100 * 255;

        if ($percentage < 0)
        {
            $percentage = abs($percentage);
            for ($x = 0; $x < 3; $x++)
            {
                $c = hexdec(substr($originalColour, (2 * $x), 2)) - $percentage;
                $c = ($c < 0) ? 0 : dechex($c);
                $rgbColour .= (strlen($c) < 2) ? '0' . $c : $c;
            }
        }
        else
        {
            for ($x = 0; $x < 3; $x++)
            {
                $c = hexdec(substr($originalColour, (2 * $x), 2)) + $percentage;
                $c = ($c > 255) ? 'ff' : dechex($c);
                $rgbColour .= (strlen($c) < 2) ? '0' . $c : $c;
            }
        }
        return '#' . $rgbColour;
    }

}

/**
 * Provides a range of system-wide information & utilities
 */
class CEVC_Arrays
{

    /**
     * Gets an array of language codes
     * @param type $codes
     * @return array
     */
    public static function getLanguageCodeNameArray($codes = array())
    {
        $out = array();
        foreach ($codes as $code)
        {
            if (isset(self::$languages[$code]))
            {
                $out[$code] = self::$languages[$code];
            }
            else
            {
                $out[$code] = $code;
            }
        }

        return $out;
    }

    /**
     * Gets a list of actual language names
     * @param type $codes
     * @return array
     */
    public static function getLanguageNameArrayFromCodes($codes = array())
    {
        $out = array();
        foreach ($codes as $code)
        {
            if (isset(self::$languages[$code]))
            {
                $out[] = self::$languages[$code];
            }
            else
            {
                $out[] = $code;
            }
        }

        return $out;
    }

    /**
     * Provides a list of human-readable language names
     * @var array
     */
    public static $languages = array(
        'aa' => 'Afar',
        'ab' => 'Abkhazian',
        'ae' => 'Avestan',
        'af' => 'Afrikaans',
        'ak' => 'Akan',
        'am' => 'Amharic',
        'an' => 'Aragonese',
        'ar' => 'Arabic',
        'as' => 'Assamese',
        'av' => 'Avaric',
        'ay' => 'Aymara',
        'az' => 'Azerbaijani',
        'ba' => 'Bashkir',
        'be' => 'Belarusian',
        'bg' => 'Bulgarian',
        'bh' => 'Bihari',
        'bi' => 'Bislama',
        'bm' => 'Bambara',
        'bn' => 'Bengali',
        'bo' => 'Tibetan',
        'br' => 'Breton',
        'bs' => 'Bosnian',
        'ca' => 'Catalan',
        'ce' => 'Chechen',
        'ch' => 'Chamorro',
        'co' => 'Corsican',
        'cr' => 'Cree',
        'cs' => 'Czech',
        'cu' => 'Church Slavic',
        'cv' => 'Chuvash',
        'cy' => 'Welsh',
        'da' => 'Danish',
        'de' => 'German',
        'dv' => 'Divehi',
        'dz' => 'Dzongkha',
        'ee' => 'Ewe',
        'el' => 'Greek',
        'en' => 'English',
        'eo' => 'Esperanto',
        'es' => 'Spanish',
        'et' => 'Estonian',
        'eu' => 'Basque',
        'fa' => 'Persian',
        'ff' => 'Fulah',
        'fi' => 'Finnish',
        'fj' => 'Fijian',
        'fo' => 'Faroese',
        'fr' => 'French',
        'fy' => 'Western Frisian',
        'ga' => 'Irish',
        'gd' => 'Scottish Gaelic',
        'gl' => 'Galician',
        'gn' => 'Guarani',
        'gu' => 'Gujarati',
        'gv' => 'Manx',
        'ha' => 'Hausa',
        'he' => 'Hebrew',
        'hi' => 'Hindi',
        'ho' => 'Hiri Motu',
        'hr' => 'Croatian',
        'ht' => 'Haitian',
        'hu' => 'Hungarian',
        'hy' => 'Armenian',
        'hz' => 'Herero',
        'ia' => 'Interlingua (International Auxiliary Language Association)',
        'id' => 'Indonesian',
        'ie' => 'Interlingue',
        'ig' => 'Igbo',
        'ii' => 'Sichuan Yi',
        'ik' => 'Inupiaq',
        'io' => 'Ido',
        'is' => 'Icelandic',
        'it' => 'Italian',
        'iu' => 'Inuktitut',
        'ja' => 'Japanese',
        'jv' => 'Javanese',
        'ka' => 'Georgian',
        'kg' => 'Kongo',
        'ki' => 'Kikuyu',
        'kj' => 'Kwanyama',
        'kk' => 'Kazakh',
        'kl' => 'Kalaallisut',
        'km' => 'Khmer',
        'kn' => 'Kannada',
        'ko' => 'Korean',
        'kr' => 'Kanuri',
        'ks' => 'Kashmiri',
        'ku' => 'Kurdish',
        'kv' => 'Komi',
        'kw' => 'Cornish',
        'ky' => 'Kirghiz',
        'la' => 'Latin',
        'lb' => 'Luxembourgish',
        'lg' => 'Ganda',
        'li' => 'Limburgish',
        'ln' => 'Lingala',
        'lo' => 'Lao',
        'lt' => 'Lithuanian',
        'lu' => 'Luba-Katanga',
        'lv' => 'Latvian',
        'mg' => 'Malagasy',
        'mh' => 'Marshallese',
        'mi' => 'Maori',
        'mk' => 'Macedonian',
        'ml' => 'Malayalam',
        'mn' => 'Mongolian',
        'mr' => 'Marathi',
        'ms' => 'Malay',
        'mt' => 'Maltese',
        'my' => 'Burmese',
        'na' => 'Nauru',
        'nb' => 'Norwegian Bokmal',
        'nd' => 'North Ndebele',
        'ne' => 'Nepali',
        'ng' => 'Ndonga',
        'nl' => 'Dutch',
        'nn' => 'Norwegian Nynorsk',
        'no' => 'Norwegian',
        'nr' => 'South Ndebele',
        'nv' => 'Navajo',
        'ny' => 'Chichewa',
        'oc' => 'Occitan',
        'oj' => 'Ojibwa',
        'om' => 'Oromo',
        'or' => 'Oriya',
        'os' => 'Ossetian',
        'pa' => 'Panjabi',
        'pi' => 'Pali',
        'pl' => 'Polish',
        'ps' => 'Pashto',
        'pt' => 'Portuguese',
        'qu' => 'Quechua',
        'rm' => 'Raeto-Romance',
        'rn' => 'Kirundi',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'rw' => 'Kinyarwanda',
        'sa' => 'Sanskrit',
        'sc' => 'Sardinian',
        'sd' => 'Sindhi',
        'se' => 'Northern Sami',
        'sg' => 'Sango',
        'si' => 'Sinhala',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'sm' => 'Samoan',
        'sn' => 'Shona',
        'so' => 'Somali',
        'sq' => 'Albanian',
        'sr' => 'Serbian',
        'ss' => 'Swati',
        'st' => 'Southern Sotho',
        'su' => 'Sundanese',
        'sv' => 'Swedish',
        'sw' => 'Swahili',
        'ta' => 'Tamil',
        'te' => 'Telugu',
        'tg' => 'Tajik',
        'th' => 'Thai',
        'ti' => 'Tigrinya',
        'tk' => 'Turkmen',
        'tl' => 'Tagalog',
        'tn' => 'Tswana',
        'to' => 'Tonga',
        'tr' => 'Turkish',
        'ts' => 'Tsonga',
        'tt' => 'Tatar',
        'tw' => 'Twi',
        'ty' => 'Tahitian',
        'ug' => 'Uighur',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'uz' => 'Uzbek',
        've' => 'Venda',
        'vi' => 'Vietnamese',
        'vo' => 'Volapuk',
        'wa' => 'Walloon',
        'wo' => 'Wolof',
        'xh' => 'Xhosa',
        'yi' => 'Yiddish',
        'yo' => 'Yoruba',
        'za' => 'Zhuang',
        'zh' => 'Chinese',
        'zu' => 'Zulu'
    );

}
