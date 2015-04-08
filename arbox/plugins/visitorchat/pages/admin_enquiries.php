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
?>

<div class="wrap">
    <?php echo CEVC::i()->Helper->renderFlash(); ?>

    <div class="icon32 icon-comments"><br /></div>
    <h2 style="margin-bottom: 20px;"><?php echo __('VisitorChat Enquiries', 'visitorchat'); ?></h2>

    <?php
    global $wpdb;
    $enquiriesTable = CEVC::i()->Enquiry->fullTableName;

    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
    $limit = 10;
    $offset = ( $pagenum - 1 ) * $limit;
    $entries = $wpdb->get_results($wpdb->prepare("SELECT * FROM $enquiriesTable ORDER BY created DESC LIMIT %d, %d", array($offset, $limit)), ARRAY_A);
    ?>
    <form method="post" action="<?php echo admin_url('admin.php?action=cevc_wpadmin_enquiry_delete'); ?>">
        <table class="widefat">
            <thead>
                <tr>
                    <?php if (current_user_can('manage_options')): ?>
                        <th scope="col" class="manage-column column-cb check-column" style="">
                            <input id="cb-select-all-1" type="checkbox">
                        </th>
                    <?php endif; ?>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Actions', 'visitorchat'); ?></th>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Read', 'visitorchat'); ?></th>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Username', 'visitorchat'); ?></th>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Email', 'visitorchat'); ?></th>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Message', 'visitorchat'); ?></th>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Created', 'visitorchat'); ?></th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <?php if (current_user_can('manage_options')): ?>
                        <th scope="col" class="manage-column column-cb check-column" style="">
                            <input id="cb-select-all-1" type="checkbox">
                        </th>
                    <?php endif; ?>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Actions', 'visitorchat'); ?></th>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Read', 'visitorchat'); ?></th>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Username', 'visitorchat'); ?></th>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Email', 'visitorchat'); ?></th>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Message', 'visitorchat'); ?></th>
                    <th scope="col" class="manage-column column-name" style=""><?php echo __('Created', 'visitorchat'); ?></th>
                </tr>
            </tfoot>

            <tbody>
                <?php
                if ($entries)
                {
                    ?>

                    <?php
                    $count = 1;
                    $class = '';
                    foreach ($entries as $entry)
                    {
                        $class = ( $count % 2 == 0 ) ? ' class="alternate"' : '';
                        ?>

                        <tr<?php echo $class; ?>>
                            <?php if (current_user_can('manage_options')): ?>
                                <th class="check-column">
                                    <input id="cb-select-1" type="checkbox" name="delete_id[]" value="<?php echo $entry['id']; ?>">
                                </th>
                            <?php endif; ?>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=visitorchat-enquiries&view=' . $entry['id']) ?>" class="button action">View</a>
                            </td>
                            <td><?php echo CEVC::i()->Helper->boolYesNo($entry['read'] == "1" ? true : false); ?></td>
                            <td><?php echo CEVC::i()->Helper->renderVisitorname($entry['username'], $entry['email'], 16); ?></td>
                            <td><?php echo $entry['email']; ?></td>
                            <td><?php echo CEVC::i()->Helper->prepareMessageText(CEVC::i()->Helper->truncate($entry['message'], 50, ' [...]')); ?></td>
                            <td><?php echo $entry['created']; ?></td>
                        </tr>

                        <?php
                        $count++;
                    }
                    ?>

                    <?php
                }
                else
                {
                    ?>
                    <tr>
                        <td colspan="6"><?php echo __('No enquiries found...', 'visitorchat'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php if (current_user_can('manage_options')): ?>
            <input type="submit" value="<?php echo __('Delete Selected', 'visitorchat') ?>" class="btn btn-mini btn-delete" onclick="javascript:if (!confirm('<?php echo esc_js(__('Are you sure you wish to delete the selected records?', 'visitorchat')); ?>'))
                            return false;" />     
               <?php endif; ?>
    </form>
    <?php
    $total = $wpdb->get_var("SELECT COUNT(`id`) FROM $enquiriesTable");
    $num_of_pages = ceil($total / $limit);
    $page_links = paginate_links(array(
        'base' => add_query_arg('pagenum', '%#%'),
        'format' => '',
        'prev_text' => __('&laquo;', 'aag'),
        'next_text' => __('&raquo;', 'aag'),
        'total' => $num_of_pages,
        'current' => $pagenum
    ));

    if ($page_links)
    {
        echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
    }
    ?>

</div>