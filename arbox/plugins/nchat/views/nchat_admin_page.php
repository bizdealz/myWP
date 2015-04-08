<?php

global $current_user, $wpdb, $table_operators, $table_chats, $table_widget;

if (site_url()=='http://ncrafts.net/nchat' && $current_user->user_login=='demo')
{
  echo '<div class="nchat-notice">This is preview mode. Your changes will not be saved.</div>';
}

/* Authenticate */
$verified = true;
if (!get_option( 'nchat_license' ))
{
  echo '<div class="nchat-notice">'.__('Purchase Key Does not Exist. Add one using Chat Settings -> Purchase Code', 'nchat').'</div>';
  $verified = false;  
}
else
{
  if ($_SERVER['HTTP_HOST']=='localhost')
  {
    $curlPath = 'localhost/ncrafts.net/license/verify.php?get=true&domain='.$_SERVER['HTTP_HOST'].'&code='.get_option( 'nchat_license' );
  }
  else
  {
    $curlPath = 'http://ncrafts.net/license/verify.php?get=true&domain='.$_SERVER['HTTP_HOST'].'&code='.get_option( 'nchat_license' );
  }
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $curlPath
    ));
  $response = curl_exec($curl);
  curl_close($curl);
  $response = json_decode($response, 1);

  if (isset($response['failed']))
  {
    echo '<div class="nchat-notice">'.$response['failed'].'</div>';
    $verified = false;
  }
}

$super = current_user_can( 'install_plugins' ) ? true : false;
$super = site_url()=='http://ncrafts.net/nchat' && $current_user->user_login=='demo' ? true : $super;

$operator = $wpdb->get_row("SELECT * FROM $table_operators WHERE id='$current_user->ID'", ARRAY_A);

if ($super)
{
$chats = $wpdb->get_results("SELECT * FROM $table_chats WHERE operator>0 ORDER BY created DESC LIMIT 200", ARRAY_A);
}
else
{
$chats = $wpdb->get_results("SELECT * FROM $table_chats WHERE operator='$current_user->ID' ORDER BY created DESC LIMIT 200", ARRAY_A);  
}

$widget = $wpdb->get_row("SELECT * FROM $table_widget WHERE id='1'", ARRAY_A);

$jane_image = plugins_url('nchat').'/images/jane.png';

$powered = __('chat powered by', 'nchat');
$powered = $verified == false ? "<a href='http://codecanyon.net/item/nchat-wordpress-live-chat-plugin/7717641?ref=ncrafts' target='_blank' id='powered-by' style='display: block !important; visibility: visible !important; opacity: 1 !important'>$powered<span> nChat</span></a>" : "";
$pop = plugins_url('nchat').'/others/pop.mp3';

$bg_images['Cross'] = 'crossword.png';
$bg_images['Debut'] = 'debut.png';
$bg_images['Fibre'] = 'fibre.png';
$bg_images['Wood'] = 'wood.png';
$bg_images['Feather'] = 'feathers.png';

$form_raw = <<<EOT

<div class='form-classes' ng-class='[WidgetData.message_styling, WidgetData.hideWidget, WidgetData.hideOperator]'>
  <audio id="pop-sound" style='display: none' src="$pop" preload="auto"></audio>
  <div id='nchat-trigger' compile='WidgetData.triggerText' ng-style='{backgroundColor: WidgetData.mainColor}'>

  </div>
  <div id='nchat-wrap'>
    <div>
      <form id='chat-signup' ng-style='{backgroundImage: +"url("+WidgetData.theme+")"}'>
        <span compile='WidgetData.form_html'></span>
        <a class='nchat-min' title='Minimize' ng-style='{color: WidgetData.mainColor}'>-</a>        
        <span class='ip-cover'><input type='text' placeholder='{{WidgetData.signup_place_name}}' name='name' class='field-name'></span>
        <span class='ip-cover'><input type='text' placeholder='{{WidgetData.signup_place_email}}' name='email' class='field-email'></span>
        <button type='submit' id='chat-signup-submit'>{{WidgetData.signup_place_submit}}</button>
      </form>

      <form id='chat-contact' ng-style='{backgroundImage: "url("+WidgetData.theme+")"}'>
        <a class='nchat-min' title='Minimize' ng-style='{color: WidgetData.mainColor}'>-</a>        
        <div id='hide-if-success'>
          <span compile='WidgetData.contact_html'></span>
          <span class='ip-cover'><input type='text' placeholder='{{WidgetData.contact_place_name}}' name='name' class='field-name'></span>
          <span class='ip-cover'><input type='text' placeholder='{{WidgetData.contact_place_email}}' name='email' class='field-email'></span>
          <span class='ip-cover'><textarea placeholder='{{WidgetData.contact_place_comments}}' name='comments' class='field-comments'></textarea></span>
          <button type='submit' id='chat-contact-submit' data-temp='{{WidgetData.contact_place_submit}}'>{{WidgetData.contact_place_submit}}</button>
        </div>
        <div class='success-response' compile='WidgetData.contact_success'></div>
      </form>

    </div>

    <div id='chat-cover'>
      <a class='nchat-min' title='Minimize' ng-style='{color: WidgetData.mainColor}'>-</a>        
      <a class='nchat-x' title='End Chat'>×</a>
      <div id='operator-profile' ng-style='{backgroundImage: +"url("+WidgetData.theme+")",color: WidgetData.mainColor}'>
        <!--STARTDEL-->
        <img src="$jane_image"><div id="operator-name">Jane<div id="operator-bio">your friendly neighbour, and doctor</div></div>
        <!--ENDDEL-->        
      </div>
      <div id='chat-wrap'>
        <div id='chat-area' ng-style='{color: WidgetData.mainColor}'>
          <!--STARTDEL-->        
          <div class="chat-line-cover chat-line-cover-admin"><p class="chat-admin" id="chat-13982623105469"><span>Jane</span><span class="txt">How can I help you today?</span></p><span class="chat-time">04:12</span></div>
          <div class="chat-line-cover chat-line-cover-user"><p class="chat-user" id="chat-13982625007264"><span>John</span><span class="txt">I am having trouble logging in</span></p><span class="chat-time">04:15</span>
          </div>
          <div class="chat-line-cover chat-line-cover-admin"><p class="chat-admin" id="chat-13982623105469"><span>Jane</span><span class="txt">What error message do you see?</span></p><span class="chat-time">04:16</span></div>
          <!--ENDDEL-->                        
        </div>
      </div>
      <form id='chat-box-cover'>
        <div id='is_typing'><div class="nchat-loader"><div class="dot dot1"></div><div class="dot dot2"></div><div class="dot dot3"></div></div></div>      
        <textarea id='chat-box' maxlength='1000' placeholder='{{WidgetData.chat_box}}'></textarea>
      </form>
    </div>
    $powered    
  </div>
</div>

EOT;

?>
<script>
  window.nChatWidget = "<?php echo $widget['widget']; ?>";
  window.isSuper = "<?php echo $super==true ? 'true' : 'false'; ?>";
</script>

<a class='links-top' href='http://ncrafts.net' target='_blank'>nCrafts</a>
<a class='links-top' href='<?php echo plugins_url('nchat').'/documentation.html'; ?>' target='_blank'>nChat Documentation</a>
<br><br>
<audio id="pop-sound" style='display: none' src="<?php echo plugins_url('nchat').'/others/pop.mp3'; ?>" preload="auto"></audio>
<input id='operator-status' value='online' type='hidden'>
<div ng-app='nChat'>
  <div style='margin-top: 30px; position: relative' ng-controller='nChatController'>
    <nav class='nav-jquery nav-main-tabs'>
      <li class='active'><?php echo _e('Chat Console', 'nchat'); ?></li>
      <li><?php echo _e('Operator Profile', 'nchat'); ?></li>
      <?php if ($super) { ?>
      <li><?php echo _e('Widget Styling', 'nchat'); ?></li>
      <li><?php echo _e('Chat Settings', 'nchat'); ?></li>
      <li><?php echo _e('Chat Operators', 'nchat'); ?></li>
      <?php } ?>
      <li><?php echo _e('Chat Logs', 'nchat'); ?></li>
    </nav>
    
    <div class='tabs main-tabs'>
      <span id='no-chat'>no chat selected</span>
      <div class='tab active' id='first-tab' style='position: relative'>
        <div class='end-action' id='end-chat'>
          <?php echo _e('End This Chat', 'nchat'); ?>
        </div>
        <div class='end-action <?php echo $operator['state']==1 ? 'online' : 'offline'; ?>' style='right: 145px; width: 70px' id='go-offline' data-online='<?php echo _e('Go Offline', 'nchat'); ?>' data-offline='<?php echo _e('Go Online', 'nchat'); ?>'>
          <?php echo $operator['state']==1 ? _e('Go Offline', 'nchat') : _e('Go Online', 'nchat'); ?>
        </div>
        <div class='chat-cover-cover'>
          <nav class='chat-users nav-jquery'>
          </nav>
          <div class='tabs chat-tabs'>
          </div>
        </div>
      </div>

      <div class='tab'>
        <div id='user-profile'>
          <div id='avatar'>
            <img src='<?php echo $operator['image']; ?>'>
          </div>
          <div id='user-info'>
            <form id='user-info-form' class='nchat-user-data'>
              <span><?php echo _e('your name', 'nchat'); ?></span>
              <input type='text' value='<?php echo $operator['name']; ?>' name='operators[name]'>
              <br><span><?php echo _e('your bio', 'nchat'); ?></span>
              <textarea rows='3' name='operators[bio]'><?php echo $operator['bio']; ?></textarea>
              <br><span><?php echo _e('your display image url', 'nchat'); ?></span>
              <textarea rows='3' style='resize: none' name='operators[image]'><?php echo $operator['image']; ?></textarea>
            </form>
          </div>
        </div>
      </div>

      <?php if ($super) { ?>      

      <div class='tab' style='min-height: 1000px'>
        <div class='widget-style-cover'>
          <div class='html-cover'>
            <h1 class='heading'><span>Welcome Message <div style='color: green'>(operator online)</div></span></h1>
            <div class='options-cover'>
              <div class='options-label'>HTML Content</div>                  
              <textarea class='one' rows='4' ng-model='WidgetData.form_html' ng-blur='saveWidget()'>
              </textarea>
            </div>

            <div class='options-cover' style='top: 215px; height: 40px'>
              <div class='options-label'>Name Placeholder</div>
              <input class='one' ng-model='WidgetData.signup_place_name' ng-blur='saveWidget()'>
            </div>
            <div class='options-cover' style='top: 275px; height: 40px'>
              <div class='options-label'>Email Placeholder</div>
              <input class='one' ng-model='WidgetData.signup_place_email' ng-blur='saveWidget()'>
            </div>
            <div class='options-cover' style='top: 335px; height: 40px'>
              <div class='options-label'>Submit Placeholder</div>
              <input class='one' ng-model='WidgetData.signup_place_submit' ng-blur='saveWidget()'>
            </div>

            <!-- START -->
            <div id='nchat-cover-cover' class=' chat-offline chat-show operator-online'>
              <?php echo $form_raw; ?>
            </div>
            <!-- End -->


          </div>    
        </div>


        <div class='widget-style-cover' style='min-height: 500px'>
          <div class='html-cover'>
            <h1 class='heading'><span>Welcome Message <div style='color: red'>(operator offline)</div></span></h1>

            <div class='options-cover'>
              <div class='options-label'>HTML Content</div>
              <textarea class='one' rows='4' ng-model='WidgetData.contact_html' ng-blur='saveWidget()'>
              </textarea>
            </div>

            <div class='options-cover' style='top: 230px; height: 40px'>
              <div class='options-label'>Name Placeholder</div>
              <input class='one' ng-model='WidgetData.contact_place_name' ng-blur='saveWidget()'>
            </div>
            <div class='options-cover' style='top: 290px; height: 40px'>
              <div class='options-label'>Email Placeholder</div>
              <input class='one' ng-model='WidgetData.contact_place_email' ng-blur='saveWidget()'>
            </div>

            <div class='options-cover' style='top: 350px; height: 40px'>
              <div class='options-label'>Comments Placeholder</div>
              <input class='one' ng-model='WidgetData.contact_place_comments' ng-blur='saveWidget()'>
            </div>            

            <div class='options-cover' style='top: 410px; height: 40px'>
              <div class='options-label'>Submit Placeholder</div>
              <input class='one' ng-model='WidgetData.contact_place_submit' ng-blur='saveWidget()'>
            </div>                                   


            <!-- START -->
            <div id='nchat-cover-cover' class='tosave chat-offline chat-show operator-offline'>
              <?php
              echo $form_raw;
              ?>
            </div>
            <!-- End -->

          </div>    
        </div>  


        <div class='widget-style-cover'>
          <h1 class='heading'><span>Chat Console</span></h1>
          <div class='options-cover border' style='top: 175px; height: 240px; width: 350px'>
            <div class='option-line'>
              <label class='no-select'>
                <input ng-blur='saveWidget()' ng-model='WidgetData.hideOperator' ng-true-value='hide-operator' type='checkbox' class='general'> <?php _e('Hide Operator Profile', 'nchat'); ?>
              </label>
            </div>

            <div class='option-line'>
              <p>Primary Color </p>
              <div style='float: right'>
                <input type="text" ng-blur='saveWidget()' class="colorpicker" data-default-color="{{WidgetData.mainColor}}" ng-model='WidgetData.mainColor'/>
              </div>
            </div>

            <div class='option-line'>            
              <p><?php _e('Message Style', 'nchat'); ?> </p>
              <div style='float: right'>
                <label><input ng-blur='saveWidget()' type='radio' value='default' ng-model='WidgetData.message_styling' name='message_styling' class='general'> Default</label>&nbsp;&nbsp;
                <label><input ng-blur='saveWidget()' type='radio' value='vertical' ng-model='WidgetData.message_styling' name='message_styling' class='general'> Vertical</label>
              </div>
            </div>

            <div class='option-line' style='height: 70px'>            
              <p><?php _e('Background Theme', 'nchat'); ?> </p>
              <div style='float: right; width: 172px'>
                <?php

                foreach ($bg_images as $key => $value)
                {
                  ?>
                  <label class='no-radio'>
                    <input ng-blur='saveWidget()' type='radio' value='<?php echo plugins_url('nchat').'/images/'.$value; ?>' ng-model='WidgetData.theme' name='theme'>
                    <img src='<?php echo plugins_url('nchat').'/images/'.$value; ?>'>
                    <span class='sub_label'><?php echo $key; ?></span>
                  </label>
                  <?php
                }
                ?>
                <label class='no-radio' style='margin-bottom: 14px'>
                  <input ng-blur='saveWidget()' type='radio' value='' ng-model='WidgetData.theme' name='theme'>
                  None
                </label>                
              </div>
            </div>           

          </div>

          <div class='options-cover' style='top: 474px; height: 40px'>
            <div class='options-label'>Chat Box</div>
            <input class='one' ng-model='WidgetData.chat_box' ng-blur='saveWidget()'>
          </div>           

          <div style='position: absolute; left: 75px; top: 100px'>
            <div id='nchat-trigger' compile='WidgetData.triggerText' ng-style='{backgroundColor: WidgetData.mainColor}' style='position: static'>

            </div>
            <div class='options-cover' style='left: auto; height: 40px; right: -320px; top: 0px;'>
              <div class='options-label'>Text / HTML</div>
              <input type='text' class='one' ng-model='WidgetData.triggerText' ng-blur='saveWidget()'>
            </div>            
          </div>


          <!-- START -->
          <div id='nchat-cover-cover' class='chat-online chat-show operator-online' style='margin-top: 175px'>
            <?php
            echo $form_raw;
            ?>    
          </div>
          <!-- END -->


        </div>       

      </div>

      <div class='tab settings-tab'>

        <div class='options-panel'>
          <h2><?php _e('License', 'nchat'); ?></h2>        
          <div class='right'>
            <form id='nchat-pk'>
              <label class='sub-label'>
                <span><?php _e('Purchase Code', 'nchat'); ?></span>            
                <input type='text' class='general' id='nchat-pk-input' value='<?php echo get_option( 'nchat_license' ); ?>'>
              </label>
              <button class='general' type='submit'><?php _e('Verify', 'nchat'); ?></button>
              <div class='response'></div>
              <p><br><a style='font-size: 12px' target='_blank' href='http://ncrafts.net/blog/2014/05/where-to-find-the-purchase-code-of-items/'><?php _e('Where to find the Purchase Key?', 'nchat'); ?></a></p>
            </form>
          </div>

        </div>
        <div class='options-panel'>

          <h2><?php _e('General', 'nchat'); ?></h2>
          <div class='right'><label><input ng-change='saveWidget()' type='checkbox' class='general' ng-model='WidgetData.hideWidget'> <?php _e('Hide widget when no operator is online', 'nchat'); ?></label></div>

        </div>

        <div class='options-panel'>

          <div style='display: inline-block; width: 45%'>
            <h2><?php _e('Email Notifications', 'nchat'); ?></h2>
            <p><?php _e('for messages sent when the operators are offline', 'nchat'); ?></p>

            <div class='right'><label class='sub-label'><span><?php _e('Send Email From', 'nchat'); ?></span><input class='general' ng-model='WidgetData.contact_from' ng-blur='saveWidget()' type='text'></label></div>

            <div class='right'><label class='sub-label'><span><?php _e('Send Email To', 'nchat'); ?></span><input class='general' ng-model='WidgetData.contact_email' ng-blur='saveWidget()' type='text'></label></div>

            <div class='right'><label class='sub-label'><span><?php _e('Email Subject', 'nchat'); ?></span><input class='general' ng-model='WidgetData.contact_subject' ng-blur='saveWidget()' type='text'></label></div>

            <div class='right'><label class='sub-label'><span><?php _e('Email Body', 'nchat'); ?></span><textarea class='general' rows='4' ng-model='WidgetData.contact_body' ng-blur='saveWidget()'></textarea></label></div>

            <div class='right'> <label class='sub-label'><span><?php _e('Success Message', 'nchat'); ?></span><textarea class='general' rows='4' ng-model='WidgetData.contact_success' ng-blur='saveWidget()'></textarea></label></div>
          </div>

          <div style='display: inline-block; width: 45%'>
            <h2><?php _e('Chat Transcript Emails', 'nchat'); ?></h2>
            <p><?php _e('for emails sent when the user chooses to receive transcript', 'nchat'); ?></p>
            <div class='right'><label class='sub-label'><span><?php _e('From Address', 'nchat'); ?></span><input class='general' ng-model='WidgetData.transcript_email' ng-blur='saveWidget()' type='text'></label></div>

            <div class='right'><label class='sub-label'><span><?php _e('Email Subject', 'nchat'); ?></span><input class='general' ng-model='WidgetData.transcript_subject' ng-blur='saveWidget()' type='text'></label></div>

            <div class='right'><label class='sub-label'><span><?php _e('Email Body', 'nchat'); ?></span><textarea class='general' rows='4' ng-model='WidgetData.transcript_body' ng-blur='saveWidget()'></textarea></label></div>

          </div>

        </div>

        <div class='options-panel'>            
          <h2><?php _e('Custom CSS', 'nchat'); ?></h2>
          <textarea ng-blur='saveWidget()' class='general' rows='4' style='font-family: Monospace, Arial; width: 350px' ng-model='WidgetData.customCSS'></textarea>
        </div>         
      </div>


      <div class='tab'>
      <br>
      <h2 style='margin-left: 35px'>The Following Users Can Act as Operators</h2>
      <ol style='list-style: none; margin-left: 35px'>
        <?php
        $all_users = get_users();
        foreach ($all_users as $user)
        {
          if (user_can( $user->ID, 'install_plugins' )) continue;
          echo "<li><label><input ng-blur='saveWidget()' type='checkbox' id='user_operator_".$user->ID."' name='allowed_op' ng-model='WidgetData.operators[".$user->ID."]' ng-true-value='true' ng-false-value='false'>".$user->user_login." (".$user->user_email.")"."</label></li>";
        }
        ?>
        </ol>
      </div>
      <?php } ?>      
      <div class='tab'>
        <table cellpadding="0" cellspacing="0" id="chat-logs-table">

          <thead>
            <tr>
              <th style='text-align: left'>Name</th>
              <th style='text-align: left'>Email</th>
              <th style='text-align: left'>Lines</th>
              <th style='text-align: left'>Started</th>
              <th style='text-align: center'>Download</th>
            </tr>
          </thead>

          <?php
          foreach ($chats as $key => $value)
          {
            $lines = count(json_decode($value['chat'], 1));
            $export = plugins_url('nchat').'/php/export.php?id='.$value['id'];
            $date = nchat_time_ago(time()-strtotime($value['created']));
            ?><tr>
            <td style='width: 20%'><?php echo $value['name']; ?></td>
            <td style='width: 25%'><?php echo $value['email']; ?></td>
            <td style='width: 10%'><?php echo $lines; ?></td>
            <td style='width: 30%'><?php echo $value['created']."<span class='ago'>($date)</span>"; ?></td>
            <td style='width: 15%; text-align: center'><a href='<?php echo $export; ?>' target='_blank'>Download</a></td>
          </tr>
          <?php
        }
        ?>

      </table>
    </div> 
  </div>    

</div>
</div>