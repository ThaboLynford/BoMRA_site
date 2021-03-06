<div class="panel panel-default dashboard-panel">
    <div class="panel-heading">Download History</div>
    <table class="table">
        <thead>
        <tr>
            <th><?php _e( "Package Name" , "download-manager" ); ?></th>
            <th><?php _e( "Download Time" , "download-manager" ); ?></th>
            <th><?php _e( "User/IP" , "download-manager" ); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        global $wp_rewrite, $wp_query, $wpdb;
        $items_per_page = 30;
        $start = isset($_GET['pgd'])?($_GET['pgd']-1)*$items_per_page:0;
        $pid = wpdm_query_var('pid', 'int');
        $pidcond = ($pid > 0)?" and s.pid = '{$pid}'":"";
        $res = $wpdb->get_results("select p.post_title,s.* from {$wpdb->prefix}posts p, {$wpdb->prefix}ahm_download_stats s where s.pid = p.ID {$pidcond} order by `timestamp` desc limit $start, $items_per_page");
        foreach($res as $stat){
            ?>
            <tr>
                <td><a href="<?php echo get_permalink($stat->pid); ?>"><?php echo $stat->post_title; ?></a> <?php if($pidcond === "") { ?>| <a href="edit.php?post_type=wpdmpro&page=wpdm-stats&type=history&pid=<?php echo $stat->pid; ?>"><i class="fa fa-chart-bar"></i></a><?php } ?></td>
                <td><?php echo date(get_option('date_format')." H:i",$stat->timestamp); ?></td>
                <td><?php echo $stat->uid>0?"<a target='_blank' href='user-edit.php?user_id={$stat->uid}'>".get_user_by('ID', $stat->uid)->display_name."</a>".((get_option('__wpdm_noip') == 0)?" (<a target='_blank' href='http://ip-api.com/#{$stat->ip}'>{$stat->ip}</a>)":""):((get_option('__wpdm_noip') == 0)?"<a target='_blank' href='http://ip-api.com/#{$stat->ip}'>{$stat->ip}</a>":""); ?></td>
            </tr>
            <?php
        }
        ?>

        </tbody>
    </table>
    <div class="panel-footer">
        <?php

        isset($_GET['pgd']) && $_GET['pgd'] > 1 ? $current = (int)$_GET['pgd'] : $current = 1;
        $pagination = array(
            'base' => @add_query_arg('pgd','%#%'),
            'format' => '',
            'total' => ceil($wpdb->get_var("select count(*) from {$wpdb->prefix}ahm_download_stats where 1 {$pidcond}")/$items_per_page),
            'current' => $current,
            'show_all' => false,
            'type' => 'list',
            'prev_next'    => True,
            'prev_text' => '<i class="icon icon-angle-left"></i> Previous',
            'next_text' => 'Next <i class="icon icon-angle-right"></i>',
        );

        //if( $wp_rewrite->using_permalinks() && !is_search())
        //    $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg('s',get_pagenum_link(1) ) ) . 'paged=%#%', 'paged');

        if( !empty($wp_query->query_vars['s']) )
            $pagination['add_args'] = array('s'=>get_query_var('s'));

        echo '<div class="text-center">' . str_replace('<ul class=\'page-numbers\'>','<ul class="pagination pagination-centered page-numbers" style="margin: 0">', paginate_links($pagination)) . '</div>';
        ?>
    </div>
</div>