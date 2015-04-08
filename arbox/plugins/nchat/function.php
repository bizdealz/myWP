<?php  
    /* 
    Plugin Name: nChat
    Description: WordPress live chat plugin
    Author: nCrafts
    Author URI: http://nCrafts.net/
    Plugin URI: http://codecanyon.net/item/nchat-wordpress-live-chat-plugin/7717641
    Version: 1.01
    */
    error_reporting(0);

    if (!isset($_SESSION))
    {
      session_start();
    }

    global $wpdb, $table_chats, $table_operators, $table_widget;
    $table_chats = $wpdb->prefix . "nchat_chats";
    $table_operators = $wpdb->prefix . "nchat_operators";
    $table_widget = $wpdb->prefix . "nchat_widget";

    add_action('wp_ajax_nchat_startChat', 'nchat_startChat');
    add_action('wp_ajax_nopriv_nchat_startChat', 'nchat_startChat');
    add_action('wp_ajax_nchat_sendContact', 'nchat_sendContact');
    add_action('wp_ajax_nopriv_nchat_sendContact', 'nchat_sendContact');
    add_action('wp_ajax_nchat_endChat', 'nchat_endChat');
    add_action('wp_ajax_nopriv_nchat_endChat', 'nchat_endChat');
    add_action('wp_ajax_nchat_updateChat', 'nchat_updateChat');
    add_action('wp_ajax_nopriv_nchat_updateChat', 'nchat_updateChat');
    add_action('wp_ajax_nchat_updateChats', 'nchat_updateChats');
    add_action('wp_ajax_nopriv_nchat_updateChats', 'nchat_updateChats');
    add_action('wp_ajax_nchat_changeState', 'nchat_changeState');
    add_action('wp_ajax_nopriv_nchat_changeState', 'nchat_changeState');
    add_action('wp_ajax_nchat_sendTranscript', 'nchat_sendTranscript');
    add_action('wp_ajax_nopriv_nchat_sendTranscript', 'nchat_sendTranscript');
    
    add_action('wp_ajax_nchat_updateConfig', 'nchat_updateConfig');
    add_action('wp_ajax_nopriv_nchat_updateConfig', 'nchat_updateConfig');
    add_action('wp_ajax_nchat_saveWidget', 'nchat_saveWidget');
    add_action('wp_ajax_nopriv_nchat_saveWidget', 'nchat_saveWidget');

    add_action('wp_ajax_nchat_verifyLicense', 'nchat_verifyLicense');
    add_action('wp_ajax_nopriv_nchat_verifyLicense', 'nchat_verifyLicense');

    add_action('init', 'nchat_language_init');
    add_action('init', 'nchat_registerOperator');

    function nchat_language_init()
    {
      load_plugin_textdomain('nchat', false, dirname(plugin_basename(__FILE__)));
    }

    function nchat_time_ago($secs){
      $bit = array(
        ' year'        => $secs / 31556926 % 12,
        ' week'        => $secs / 604800 % 52,
        ' day'        => $secs / 86400 % 7,
        ' hr'        => $secs / 3600 % 24,
        ' min'    => $secs / 60 % 60,
        ' sec'    => $secs % 60
        );


      foreach($bit as $k => $v)
      {
        if($v > 1)$ret[] = $v . $k;
        if($v == 1)$ret[] = $v . $k;
        if (isset($ret)&&count($ret)==2){break;}
      }
      if (isset($ret))
      {
        if (count($ret)>1)
        {
          array_splice($ret, count($ret)-1, 0, 'and');
        }
        $ret[] = 'ago';
        return join(' ', $ret);
      }
      return '';
    }    

    function nchat_registerOperator()
    {
      if (!is_user_logged_in()) {return false;}
      global $wpdb, $table_chats, $table_operators, $current_user;
      if ( !isset($current_user->ID) || $current_user->ID==0 )
      {
        die();
      }
      $op = $wpdb->get_row("SELECT * FROM $table_operators WHERE id='$current_user->ID'", ARRAY_A);
      if ($op==NULL)
      {
        $image = get_avatar( $current_user->ID, 150 );
        preg_match("/src='(.*?)'/i", $image, $matches);
        $insert = $wpdb->insert( $table_operators, array('id'=>$current_user->ID,'state'=>1,'name'=>$current_user->display_name,'image'=> $matches[1]) );
      }
    }

    function nchat_saveWidget()
    {
      global $wpdb, $table_widget, $current_user;
      if (site_url()=='http://ncrafts.net/nchat' && $current_user->user_login=='demo') {die();}
      $status = $wpdb->get_row("SELECT * FROM $table_widget WHERE id='1'", ARRAY_A);
      if ($status==NULL)
      {
        $insert = $wpdb->insert( $table_widget, array('id'=>1) );        
      }

      $widget = $_POST['widget'];
      $html = $_POST['html'];

      $html = delete_all_between('<!--STARTDEL-->','<!--ENDDEL-->',$html);
      $update = $wpdb->update( $table_widget, array('widget'=>$widget, 'html'=>$html), array('id'=>1) );

      $file_widget = plugins_url( 'others/widget.txt', __FILE__ );
      file_put_contents('../wp-content/plugins/nchat/others/html.txt', $html);
      file_put_contents('../wp-content/plugins/nchat/others/widget.txt', $widget);      
      
      die();

    }

    function nchat_verifyLicense()
    {
      $key = addslashes($_GET['key']);
      if ($_SERVER['HTTP_HOST']=='localhost')
      {
        $curlPath = 'localhost/ncrafts.net/license/verify.php?put=true&domain='.$_SERVER['HTTP_HOST'].'&code='.$key;
      }
      else
      {
        $curlPath = 'http://ncrafts.net/license/verify.php?put=true&domain='.$_SERVER['HTTP_HOST'].'&code='.$key;
      }
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $curlPath
        ));
      $response = curl_exec($curl);
      curl_close($curl);
      $response = json_decode($response, 1);

      if (isset($response['success']))
      {
        update_option( 'nchat_license', $key, '', 'yes' );        
        $response = array('message'=>__('Verified. Your purchase code has been registered to ', 'nchat').$_SERVER['HTTP_HOST'].'.');
      }
      else if (isset($response['failed']))
      {
        $response = array('message'=>$response['failed']);
      }
      else
      {
        $response = array('message'=>__('Unknown error', 'nchat'));
      }
      echo json_encode($response);
      die();
    }

    function nchat_updateConfig()
    {
      global $wpdb, $table_chats, $table_operators, $current_user;
      if (site_url()=='http://ncrafts.net/nchat' && $current_user->user_login=='demo') {die();}
      foreach ($_GET as $key => $value)
      {
        var_dump($key);
        switch ($key)
        {
          case 'operators':
          foreach ($value as $key2 => $value2)
          {
            $update = $wpdb->update( $table_operators, $value, array('id'=>$current_user->ID) );
          }
          break;
          
          default:
            # code...
          break;
        }
      }
      die();
    }

    register_activation_hook( __FILE__, 'nchat_activate' );
    function nchat_activate()
    {
      global $wpdb, $table_chats, $table_operators, $table_widget;
      $sql = "CREATE TABLE $table_chats ( id varchar(255) NOT NULL UNIQUE PRIMARY KEY, status varchar(255) NOT NULL, operator int NULL,name varchar(255) NULL, email varchar(255) NULL, other text NULL, chat mediumtext NULL, created timestamp, last_o int(11), last_u int(11) );";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);      

      $sql = "CREATE TABLE $table_widget ( id varchar(255) NOT NULL UNIQUE PRIMARY KEY, widget mediumtext NULL, html mediumtext NULL );";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

      dbDelta($sql);
      $sql = "CREATE TABLE $table_operators (id int(11) NOT NULL UNIQUE PRIMARY KEY, state int(11) NOT NULL, image text, name varchar(255), bio text, last int(11) NULL);";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      $status = $wpdb->get_row("SELECT * FROM $table_widget WHERE id='1'", ARRAY_A);
      if ($status==NULL)
      {
        $html = file_get_contents('../wp-content/plugins/nchat/others/html.txt');
        $widget = file_get_contents('../wp-content/plugins/nchat/others/widget.txt');
        $wpdb->insert( $table_widget, array('id'=>1,'widget'=>$widget,'html'=>$html) );
      }

    }

    add_action( 'admin_menu', 'nchat_menu' );
    function nchat_menu()
    {
      global $wpdb, $table_widget, $current_user;
      /* Get Chat Transcript Settings */
      $widget = $wpdb->get_row("SELECT widget, html FROM $table_widget WHERE id='1'", ARRAY_A);
      $config = json_decode(stripslashes($widget['widget']),1);

      if (site_url()=='http://ncrafts.net/nchat' && $current_user->user_login=='demo')
      {
        $page = add_menu_page( 'nChat - Live Chat Plugin', 'nChat', 'read', 'nchat-admin', 'nchat_admin_page', plugins_url('nchat/images/wp-icon.png' ), '30.26' );
        add_action( 'admin_enqueue_scripts', 'nchat_admin_assets' );
      }
      else
      {
        if ( ( isset($config['operators'][2]) && $config['operators'][2]=='true' ) || (current_user_can( 'install_plugins' )) )
        {
          $page = add_menu_page( 'nChat - Live Chat Plugin', 'nChat', 'publish_posts', 'nchat-admin', 'nchat_admin_page', plugins_url('nchat/images/wp-icon.png' ), '30.26' );
          add_action( 'admin_enqueue_scripts', 'nchat_admin_assets' );        
        }
      }
    }


    function remove_profile_menu()
    {
      global $current_user;  
      if(!current_user_can('update_core') && site_url()=='http://ncrafts.net/nchat' && $current_user->user_login=='demo')
      {
        remove_submenu_page('users.php', 'profile.php');
        remove_menu_page('profile.php');
      }
    }
    add_action('admin_init', 'remove_profile_menu');    

    function nchat_admin_assets($hook) {
      global $fc_page, $current_user;
      get_currentuserinfo();
      if ( $hook =='toplevel_page_nchat-admin' )
      {
        wp_enqueue_script('nchat-angular-js', plugins_url( 'js/angular.min.js', __FILE__ ));
        wp_enqueue_script('nchat-json-js', plugins_url( 'js/toJSON.js', __FILE__ ));

        wp_enqueue_script('nchat-admin-js', plugins_url( 'js/nchat-admin.js', __FILE__ ), array( 'wp-color-picker' ));
        wp_localize_script('nchat-admin-js', 'variables', array( 'ajaxurl' => admin_url('admin-ajax.php') ));
        wp_localize_script('nchat-admin-js', 'operator', array( 'name' => $current_user->display_name ));

        wp_enqueue_style('nchat-widget-css', plugins_url( 'css/nchat-widget.css', __FILE__ ));
        wp_enqueue_style('nchat-admin-css', plugins_url( 'css/nchat-admin.css', __FILE__ ));

        wp_enqueue_style( 'wp-color-picker' );
      }
    }    

    function nchat_admin_page()
    {
      $url = plugins_url();
      $to_include = 'views/nchat_admin_page.php';
      require($to_include);
    }

    function nchat_startChat()
    {
      global $wpdb, $table_chats;
      require_once('others/browser.php');
      $browser = new Browser();

      $other['agent'] = $browser->getBrowser().' '.$browser->getVersion().', '.$browser->getPlatform();
      $other['ip'] = $_SERVER['REMOTE_ADDR'];

      if ( !isset($_GET['name']) || strlen($_GET['name'])<3 )
      {
        $error['name'] = 'Invalid';
      }
      if ( !isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL) )
      {
        $error['email'] = 'Invalid';
      }

      if ($error)
      {
        $error['failed'] = 'true';
        echo json_encode($error);
        die();
      }

      $name = addslashes($_GET['name']);
      $email = addslashes($_GET['email']);

      $uniq_id = substr(md5(uniqid(rand(), true)),0,9);
      setcookie("nchat_id", $uniq_id, strtotime( '+1 year' ), '/');
      $_COOKIE['nchat_id'] = $uniq_id;
      $insert = $wpdb->insert( $table_chats, array('id'=>$uniq_id,'name'=> $name, 'email'=> $email, 'other'=>json_encode($other),'status'=>'live','created'=>date("Y-m-d H:i:s", time())) );
      if ($insert)
      {
        echo json_encode(array('success'=>'true', 'key'=>$uniq_id));
      }
      die();
    }

    function nchat_sendContact()
    {
      global $wpdb, $table_widget;

      if ( !isset($_GET['name']) || strlen($_GET['name'])<3 )
      {
        $error['name'] = 'Invalid';
      }
      if ( !isset($_GET['comments']) || strlen($_GET['comments'])<3 )
      {
        $error['comments'] = 'Invalid';
      }
      if ( !isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL) )
      {
        $error['email'] = 'Invalid';
      }

      if ($error)
      {
        $error['failed'] = 'true';
        echo json_encode($error);
        die();
      }

      $name = addslashes($_GET['name']);
      $email = addslashes($_GET['email']);
      $comments = addslashes($_GET['comments']);

      /* Get Chat Transcript Settings */
      $widget = $wpdb->get_row("SELECT widget, html FROM $table_widget WHERE id='1'", ARRAY_A);
      $config = json_decode(stripslashes($widget['widget']),1);      

      /* Prepare Email*/
      $to = $config['contact_email'];
      $headers = "From: ".$config['contact_from']."\r\n";
      $headers.= "Reply-to: $email\r\n";
      $headers.= "MIME-Version: 1.0\r\n";
      $headers.= "X-Mailer: PHP". phpversion() ."\r\n";
      $subject = $config['contact_subject'];
      $message = $config['contact_body'];
      $message = str_replace('[Comments]', $comments, $message);

      if (wp_mail( $to, $subject, $message, $headers, $attachments ))
      {
        echo json_encode(array('success'=>$config['contact_success']));
      }
      else
      {
        echo json_encode(array('failed'=>'true'));
      }
      die();
    }    

    function nchat_endChat()
    {
      global $wpdb, $table_chats;
      $key = addslashes($_GET['key']);
      $updated = $wpdb->update( $table_chats, array('status'=>'offline'), array('id'=>$key) );
      if ($updated)
      {
        echo json_encode(array('success'=>'true'));
      }
      else
      {
        echo json_encode(array('failed'=>'true'));
      }
      die();
    }

    function nchat_changeState()
    {
      global $wpdb, $table_operators, $current_user;
      $status = $wpdb->get_row("SELECT state FROM $table_operators WHERE id='$current_user->ID'", ARRAY_A);
      if ($status['state']==0)
      {
        $update = $wpdb->update( $table_operators, array('state'=>1), array('id'=>$current_user->ID) );
        echo json_encode(array('online'=>'true'));
      }
      else
      {
        $update = $wpdb->update( $table_operators, array('state'=>0), array('id'=>$current_user->ID) );
        echo json_encode(array('offline'=>'true'));
      }
      die();
    }

    function nchat_sendTranscript()
    {
      global $wpdb, $table_chats, $table_widget;
      $key = addslashes($_GET['key']);
      $email = $wpdb->get_row("SELECT email FROM $table_chats WHERE id='$key'", ARRAY_A);
      $email = $email['email'];


      /* Get Chat Transcript Settings */
      $widget = $wpdb->get_row("SELECT widget, html FROM $table_widget WHERE id='1'", ARRAY_A);
      $config = json_decode(stripslashes($widget['widget']),1);


      /* Prepare Chat Transcript */
      $chat = $wpdb->get_row( "SELECT chat FROM $table_chats WHERE id = '$key'", 'ARRAY_A' );

      foreach ( json_decode($chat['chat'], 1) as $key => $value)
      {
        $time = date("F j, Y, g:i:s a", substr($key, 0, -4));
        $message = str_replace(array("\r\n", "\n", "\r"), '', $value['message']);
        $transcript .= "[$time] $value[by]: $message\n\r";
      }      


      /* Prepare Email*/
      $to = $email;
      $headers = 'From: '.$config['transcript_email']. "\r\n";
      $headers.= "MIME-Version: 1.0\r\n";
      $headers.= "X-Mailer: PHP". phpversion() ."\r\n";

      $subject = $config['transcript_subject'];
      $message = $config['transcript_body']."\n\r".$transcript;

      if (wp_mail( $to, $subject, $message, $headers, $attachments ))
      {
        echo json_encode(array('success'=>__('sent', 'nchat')));
      }
      else
      {
        echo json_encode(array('failed'=>'true'));
      }
      die();
    }

    function nchat_updateChat()
    {
      global $wpdb, $table_chats, $table_operators, $current_user;
      $current_time = intval(microtime(true)*10000);
      $_GET['focus'] = isset($_GET['focus']) ? addslashes($_GET['focus']) : '';
      $typing = array();

      if ( isset($_GET['page']) && $_GET['page']=='admin' )
      {
        $isAdmin = true;
      }
      else
      {
        $isAdmin = false;
      }
      get_currentuserinfo();


      if ($isAdmin && $_GET['focus']!='')
      {
        $wpdb->update( $table_chats, array('last_o'=>time()), array('id'=>$_GET['focus']) );
      }
      else if ($_GET['focus']!='')
      {
        $wpdb->update( $table_chats, array('last_u'=>time()), array('id'=>$_GET['focus']) );
      }

      if ($isAdmin)
      {
        foreach ($_GET['chats'] as $chatNos => $thisChat)
        {
          $thisChat['key'] = addslashes($thisChat['key']);
          $typingTemp = $wpdb->get_row("SELECT last_u FROM $table_chats WHERE id='$thisChat[key]'", ARRAY_A);
          if ( (time()-$typingTemp['last_u']) < 2 && (time()-$typingTemp['last_u']) >= 0 )
          {
            $typing[] = $thisChat['key'];
          }                  
        }
      }
      else
      {
        $key = addslashes($_GET['chats']['0']['key']);
        $typingTemp = $wpdb->get_row("SELECT last_o FROM $table_chats WHERE id='$key'", ARRAY_A);
        if ( (time()-$typingTemp['last_o']) < 2 && (time()-$typingTemp['last_o']) >= 0 )
        {
          $typing[] = $key;
        }
      }

      if ( isset($_GET['chats']) )
      {
        foreach ($_GET['chats'] as $chatNos => $thisChat)
        {
          $key = addslashes($thisChat['key']);
          $message = isset($thisChat['message']) ? $thisChat['message'] : '';
          $position = addslashes($thisChat['position']);
          $timeNow = $current_time;

          if ($isAdmin)
          {
            $co = $wpdb->get_row("SELECT operator FROM $table_chats WHERE id='$key'", ARRAY_A);
            if ( ($co['operator']==NULL || $co['operator']==0) && isset($thisChat['message']) )
            {
              $update = $wpdb->update( $table_chats, array('operator'=>$current_user->ID), array('id'=>$key) );
            }
          }
          else
          {
            $co = $wpdb->get_row("SELECT operator FROM $table_chats WHERE id='$key'", ARRAY_A);
            if ($co['operator']!=NULL)
            {
              $operator = $wpdb->get_row("SELECT * FROM $table_operators WHERE id='$co[operator]'", ARRAY_A);
              if ( isset($operator) )
              {
                $operator['name'] = $operator['name'];
                $operator['bio'] = $operator['bio']==null ? '' : $operator['bio'];
                $operator['image'] = $operator['image'];              
              }      
            }
          }

          $chatRow = $wpdb->get_row("SELECT name, email, chat, status FROM $table_chats WHERE id='$key'", ARRAY_A);
          $by = $chatRow['name'];

          if ( $isAdmin )
          {
            $operator = $wpdb->get_row("SELECT * FROM $table_operators WHERE id='$current_user->ID'", ARRAY_A);              
            $by = $operator['name'];
          }

          if (trim($message)!='' && $chatRow['status']!='offline')
          {
            if ( is_array(json_decode($chatRow['chat'], 1)) )
            {
              $chat = json_decode($chatRow['chat'], 1);
              $chat[$timeNow] = array('by'=>$by, 'who'=> $isAdmin ? 'admin' : 'user','message'=>stripslashes($message));
            }
            else
            {
              $chat = array();
              $chat[$timeNow] = array('by'=>$by, 'who'=> $isAdmin ? 'admin' : 'user','message'=>stripslashes($message));        
            }
            $update = $wpdb->update( $table_chats, array('chat'=>json_encode($chat)), array('id'=>$key) );
          }
          else
          {
            if ( is_array(json_decode($chatRow['chat'], 1)) )
            {
              $chat = json_decode($chatRow['chat'], 1);
            }
          }

          /* Fetch New Rows */
          $newRows[$chatNos]['p'] = $current_time;
          $newRows[$chatNos]['key'] = $key;
          $newRows[$chatNos]['lines'] = array();
          if ( isset($chat) )
          {
            foreach ($chat as $key => $chatLine)
            {
              if ($key > $position)
              {
                $newRows[$chatNos]['lines'][$key] = $chatLine;
              }
            }            
          }
        }
      }


      if ( $isAdmin==false )
      {
        $current_key = addslashes($_GET['chats']['0']['key']);
        $user_email = $wpdb->get_row("SELECT email FROM $table_chats WHERE id='$current_key'", ARRAY_A);
        if (count($typing)>0){$response['ty'] = $typing;}
        if (count($newRows)>0){$response['ch'] = $newRows;}
        if (isset($operator) && $_GET['needOperator']=='true'){$response['op'] = $operator;}
        if ($chatRow['status']=='offline'){$response['cs']=__('chat ended', 'nchat');}
        if ($chatRow['status']=='offline'){$response['cs2']=__('send transcript to ', 'nchat').$user_email['email'];}
        echo json_encode($response);
        die();
      }

      if ( isset($_GET['page']) && $_GET['page']=='admin' )
      {

        if ( isset($_GET['status']) && $_GET['status']=='online' )
        {
          $update = $wpdb->update( $table_operators, array('last'=>time()), array('id'=>$current_user->ID) );
        }

        $allChats = $wpdb->get_results("SELECT id, name, email, chat, created, other FROM $table_chats WHERE status = 'live'", ARRAY_A);
        foreach ($allChats as $key => $value)
        {
          $allRows[$key]['position'] = $current_time;
          $allRows[$key]['key'] = $value['id'];
          $allRows[$key]['created'] = $value['created'];
          $allRows[$key]['name'] = $value['name'];
          $allRows[$key]['img'] = md5( strtolower( trim( $value['email'] ) ) );
          $allRows[$key]['email'] = $value['email'];
          $allRows[$key]['lines'] = json_decode($value['chat']);
          $allRows[$key]['other'] = json_decode($value['other']);
        }

        if ( isset($newRows) )
        {
          foreach ($allRows as $key => $value)
          {
            foreach ($newRows as $key2 => $value2)
            {
              if ($value['key']==$value2['key'])
              {
                $allRows[$key]['lines'] = $value2['lines'];
              }
            }
          }
        }
        $allChatsOffline = $wpdb->get_results("SELECT id FROM $table_chats WHERE status = 'offline'", ARRAY_A);        

        echo json_encode(array('success'=>'true', 'chats'=>$allRows, 'offline'=>$allChatsOffline, 'typing'=>$typing));
        die();
      }

    }



    add_shortcode( 'nchat', 'add_nchat' );
    function add_nchat( $atts, $content = null )
    {

      extract( shortcode_atts( array(
        'auto' => '',
        ), $atts ) );

      global $wpdb, $table_chats, $table_widget, $table_operators;

      /* Check if operators are online */
      $allOperators = $wpdb->get_results("SELECT id, last FROM $table_operators", ARRAY_A);

      $online = 'operator-offline';
      foreach ($allOperators as $key => $value)
      {
        if (time()-$value['last']<6)
        {
          $online = 'operator-online';
          break;
        }
      }

      wp_enqueue_script('jquery');

      wp_enqueue_script('nchat-main-js', plugins_url( 'js/nchat-main.js', __FILE__ ));
      wp_localize_script('nchat-main-js', 'variables', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ));

      wp_enqueue_style('nchat-main-css', plugins_url( 'css/nchat-widget.css', __FILE__ ));

      $key = '';
      $nchat_live = false;
      if ( isset($_COOKIE['nchat_id']) )
      {
        $key = addslashes($_COOKIE['nchat_id']);
        $chatRow = $wpdb->get_row("SELECT chat FROM $table_chats WHERE id='$key' AND status='live'", ARRAY_A);
        if ( isset($chatRow) )
        {
          $nchat_live = true;
        }
      }

      $widget = $wpdb->get_row("SELECT widget, html FROM $table_widget WHERE id='1'", ARRAY_A);
      $config = json_decode(stripslashes($widget['widget']),1);

      $hideWidget = $config['hideWidget']==true ? 'hide-offline' : 'show-offline';
      echo "<style>".$config['customCSS']."</style>";

      ?>

      <div id='nchat-cover-cover' data-key='<?php echo $key; ?>' class='<?php echo $nchat_live ? 'chat-online' : 'chat-offline'; ?> <?php echo $_COOKIE['nchatWidget']=='on' ? 'chat-show ' : 'chat-hide '; echo "$online $hideWidget"; ?>' data-auto='<?php echo $auto; ?>'>     
        <?php 
        echo stripslashes($widget['html']);
        ?>
      </div>


      <?php

    }

    function delete_all_between($beginning, $end, $string)
    {

      $loop = false;
      while ($loop==false)
      {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if (!$beginningPos || !$endPos)
        {
          return $string;
          $loop = true;
        }
        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);
        $string = str_replace($textToDelete, '', $string);
        $loop = false;
      }
      return $string;
    }


    add_action( 'widgets_init', create_function('', 'return register_widget("nchat_widget");') );
    class nchat_widget extends WP_Widget {

      function __construct()
      {
        parent::__construct(
          'nchat_widget',
          __('nChat', 'text_domain'),
          array( 'description' => __( 'Add a live chat widget to the site', 'text_domain' ), )
          );
      }

      public function form( $instance )
      {
        if ( isset( $instance[ 'auto' ] ) ) {
          $auto = $instance[ 'auto' ];
        }
        else {
          $auto = __( '', 'text_domain' );
        }
        ?>
        <p style='font-size: 14px'>
          <?php _e('Auto initiate chat in ', 'nchat'); ?>
          <input style='box-shadow: none; text-align: center; width: 36px' id="<?php echo $this->get_field_id( 'auto' ); ?>" name="<?php echo $this->get_field_name( 'auto' ); ?>" type="text" value="<?php echo esc_attr( $auto ); ?>">
          <?php _e(' (seconds)', 'nchat'); ?>
          <span style='font-size: 12px; display: block; color: #777'>(triggered only if an operator is online)</span>
        </p>
        <?php 
      }

      public function update( $new_instance, $old_instance )
      {
        $instance = array();
        $instance['auto'] = ( ! empty( $new_instance['auto'] ) ) ? strip_tags( $new_instance['auto'] ) : '';
        return $instance;
      }

      public function widget( $args, $instance )
      {
        add_filter('widget_text', 'do_shortcode');
        if ( isset($instance['auto']) && is_int($instance['auto']) && $instance['auto']>=0 )
        {
          echo do_shortcode("[nchat auto='".$instance['auto']."'][/nchat]");
        }
        else
        {
          echo do_shortcode('[nchat][/nchat]');
        }
      }      

    }

    ?>