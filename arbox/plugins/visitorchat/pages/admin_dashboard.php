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

CEVC::i()->setJSVariablesAdmin();
?>

<script type="text/javascript" src="<?php echo CEVC::i()->url; ?>/vendor/css/bootstrap/js/bootstrap.min.js?ver=<?php echo CEVC::i()->version; ?>"></script>

<script type="text/javascript">

    (function($) {
        $(document).ready(function() {

        });
    })(jQuery);
</script>
<div class="wrap">



    <?php if (CEVC::i()->Session->read(CEVC_SessionKeys::ModeratorOnline) === true): ?>
        <a class="button-primary right vcBtnGoOnline" href="<?php echo admin_url('admin.php?action=cevc_wpadmin_go_online'); ?>"><img src="<?php echo CEVC::i()->url; ?>/img/admin/in_progress.gif" alt="" /> <?php echo __('Go Offline', 'visitorchat'); ?></a>
    <?php else: ?>
        <a class="button-primary right vcBtnGoOnline" href="<?php echo admin_url('admin.php?action=cevc_wpadmin_go_online'); ?>"><?php echo __('Go Online', 'visitorchat'); ?></a>
    <?php endif; ?>

    <div class="icon32 icon-dashboard"><br /></div>
    <h2 style="margin-bottom: 20px;"><?php echo __('VisitorChat Dashboard', 'visitorchat'); ?></h2>

    <?php if (CEVC::i()->Session->read(CEVC_SessionKeys::ModeratorOnline) !== true): ?>
        <div class="alert alert-error alert-block keepopen">
            <h4><?php echo __('Operator Status: Offline', 'visitorchat'); ?></h4>
            <?php echo __('You need to go online if you wish to use the chat interface.', 'visitorchat'); ?>
        </div>
    <?php else: ?>

        <style type="text/css">

            .discussion_container
            {
                max-height: 300px;
                overflow-y: scroll;

                margin-bottom: 20px;

                border: 1px solid #bebebe;
                background-color: #f5f5f5;
                padding: 20px;

                border-top-left-radius: 5px;
                border-bottom-left-radius: 5px;
                -webkit-border-top-left-radius: 5px;
                -webkit-border-bottom-left-radius: 5px;
                -moz-border-top-left-radius: 5px;
                -moz-border-bottom-left-radius: 5px;
            }
            .chat_tab-nav
            {
                margin-bottom: 0px;
            }
            div.tab-bordered 
            {
                border-color: #ddd;
                border-width: 0 1px 1px 1px;
                border-style: solid;
                padding: 20px;
            }

            .input_message 
            {
                height: 100px;
                max-width: 100%;
                min-width: 100%;
            }

            .notification_badge
            {
                margin: 0 0 0 5px;
                padding: 1px 5px;
            }

            .chatmessage
            {
                display: block;
                margin: 5px 0;
                border-top: 1px dashed #e3e3e3;
                clear: both;
            }
            .chatmessage p
            {
                color: #444;
                word-wrap: break-word;
                margin-top: 5px;
            }
            .message_time
            {
                float: right;
                margin: 3px;
                font-size: 80%;
                color: #ccc;
                padding-left: 12px;
            }
            .vc_submission_pending
            {
                background: transparent url(<?php echo CEVC::i()->url . '/img/chat/bullet_clock.png'; ?>) left center no-repeat;
            }
            .vc_submission_confirmed
            {
                background: transparent url(<?php echo CEVC::i()->url . '/img/chat/bullet_tick.png'; ?>) left center no-repeat;
            }
            .username
            {
                font-weight: bold;
            }
            .username:after
            {
                content: ":";
            }
            .vc_avatar
            {
                float: left;
                margin: 0 5px 5px 0;
                display: inline-block;
                width: 40px;
                height: 40px;
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }
            .vc_smilie
            {
                background-repeat: no-repeat;
                display: inline-block;
                width: 15px;
                height: 17px;
                text-indent: -9999px;   
                white-space: nowrap;
            }

            .btn_close_tab
            {
                margin: -10px -10px 0 5px;
            }

            .container_metadata td
            {
                word-wrap:break-word;
            }
            .container_metadata
            {
                width: 100%;
                display: inline-block;
                padding: 0px;
                font-size: 80%;
            }

            .vc_composing_container
            {
                display: none;
                width: 95%;
                margin: 0 auto;
                background: transparent url(<?php echo CEVC::i()->url . '/img/chat/bullet_pencil.png'; ?>) 6px center no-repeat;
                padding-left: 19px;
                font-style: italic;
            }

        </style>

        <div class="row-fluid">
            <div class="span12">

                <div class="tabbable admin_container"> 
                    <ul class="nav nav-tabs chat_tab-nav">
                        <li class="active tab_default"><a href="#tab_default" data-toggle="tab"><?php echo __('Default', 'visitorchat'); ?></a></li>
                    </ul>
                    <div class="tab-content tab_content_default tab-bordered">
                        <div class="tab-pane active" id="tab_default">
                            <p><?php echo __('You can select any active discussions from the tabs above.', 'visitorchat'); ?></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    <?php endif; ?>


</div>