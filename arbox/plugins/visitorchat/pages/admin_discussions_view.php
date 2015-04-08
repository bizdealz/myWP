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

$discussion = CEVC::i()->Discussion->findById(CEVC::i()->Request->query('view'));
?>

<div class="wrap">
    <?php echo CEVC::i()->Helper->renderFlash(); ?>

    <?php if (isset($discussion['id'])): ?>

        <div class="icon32 icon-comments"><br /></div>
        <h2 style="margin-bottom: 20px;"><?php echo CEVC_String::insert(__('Discussion with :username', 'visitorchat'), array('username' => $discussion['username'])); ?></h2>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder ">
                <div class="stuffbox">
                    <h3><?php echo __('Discussion Data', 'visitorchat'); ?></h3>
                    <div class="inside">
                        <table>
                            <tbody>
                                <tr>
                                    <th>
                                        <?php echo __('Avatar', 'visitorchat'); ?>
                                    </th>
                                    <td>
                                        <?php echo CEVC::i()->Helper->renderGravatar($discussion['username'], $discussion['email'], 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <?php echo __('Username', 'visitorchat'); ?>
                                    </th>
                                    <td>
                                        <?php echo $discussion['username']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <?php echo __('Email', 'visitorchat'); ?>
                                    </th>
                                    <td>
                                        <?php echo '<a href="mailto:' . $discussion['email'] . '">' . $discussion['email'] . '</a>' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <?php echo __('Referer', 'visitorchat'); ?>
                                    </th>
                                    <td>
                                        <?php echo '<a href="' . $discussion['referer'] . '">' . $discussion['referer'] . '</a>' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <?php echo __('Visitor Languages', 'visitorchat'); ?>
                                    </th>
                                    <td>
                                        <?php echo CEVC::i()->Helper->renderVisitorLanguages($discussion['visitor_languages']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <?php echo __('User Agent', 'visitorchat'); ?>
                                    </th>
                                    <td>
                                        <?php echo CEVC::i()->Helper->renderUserAgent($discussion['user_agent']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <?php echo __('Remote Address', 'visitorchat'); ?>
                                    </th>
                                    <td>
                                        <?php echo CEVC::i()->isDemo ? 'BLANKED IN DEMO' : $discussion['remote_address']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <?php echo __('Created', 'visitorchat'); ?>
                                    </th>
                                    <td>
                                        <?php echo $discussion['created']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <?php echo __('Modified', 'visitorchat'); ?>
                                    </th>
                                    <td>
                                        <?php echo $discussion['modified']; ?>
                                    </td>
                                </tr>
                                <?php if (current_user_can('manage_options')): ?>
                                    <tr>
                                        <th>
                                            <?php echo __('Actions', 'visitorchat'); ?>
                                        </th>
                                        <td>
                                            <form method="post" action="<?php echo admin_url('admin.php?action=cevc_wpadmin_discussion_delete'); ?>">
                                                <input name="delete_id" type="hidden" value="<?php echo $discussion['id']; ?>" />
                                                <input type="submit" value="<?php echo __('Delete', 'visitorchat') ?>" class="btn btn-mini btn-delete" onclick="javascript:if (!confirm('<?php echo esc_js(__('Are you sure you wish to delete this record?', 'visitorchat')); ?>'))
                                                            return false;" />
                                            </form>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div id="poststuff">
            <div id="post-body" class="metabox-holder ">
                <div class="stuffbox">
                    <h3><?php echo __('Messages', 'visitorchat'); ?></h3>
                    <div class="inside">
                        <?php
                        $messages = CEVC::i()->Discussion->findAllMessagesById(CEVC::i()->Request->query('view'));
                        ?>

                        <?php if (!empty($messages)): ?>
                            <table class="" style="width: 100%">
                                <tr>
                                    <th><?php echo __('User', 'visitorchat'); ?></th>
                                    <th><?php echo __('Message', 'visitorchat'); ?></th>
                                    <th><?php echo __('Current Page', 'visitorchat'); ?></th>
                                    <th><?php echo __('Created', 'visitorchat'); ?></th>
                                </tr>
                                <?php
                                foreach ($messages as $message):
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $username = $email = '';
                                            if (isset($message['User']['username']))
                                            {
                                                $username = $message['User']['username'];
                                                $email = $message['User']['email'];
                                            }
                                            else
                                            {
                                                $username = $discussion['username'];
                                                $email = $discussion['email'];
                                            }
                                            echo CEVC::i()->Helper->renderVisitorname($username, $email, 20);
                                            ?>
                                        </td>
                                        <td><?php echo nl2br(CEVC::i()->Helper->prepareMessageText($message['message'])); ?></td>
                                        <td><?php echo '<a href="' . $message['current_page'] . '" target="_blank">' . substr($message['current_page'], 0, 20) . ($message['current_page'] !== null ? '...' : '') . '</a>'; ?></td>
                                        <td><?php echo $message['created']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>


    <?php else: ?>
        <p><?php echo __('No record found...', 'visitorchat'); ?></p>
    <?php endif; ?>

</div>
