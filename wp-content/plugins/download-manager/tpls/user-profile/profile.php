<?php if(!defined('ABSPATH')) die();

$uid = $user->ID;
$store = get_user_meta($uid, '__wpdm_public_profile', true);
if(!is_array($store)) $store = array();
$store['logo'] = isset($store['logo'])?$store['logo']:get_avatar_url($uid);
$store['title'] = isset($store['title']) && $store['title'] != '' ? $store['title'] : $user->display_name;
$store['intro'] = isset($store['intro']) && $store['intro'] != '' ? $store['intro'] : '';
$store['description'] = isset($store['description']) && $store['description'] != '' ? $store['description'] : '';
$store['banner'] = isset($store['banner']) && $store['banner'] != '' ? $store['banner'] : '';
$store['txtcolor'] = isset($store['txtcolor']) && $store['txtcolor'] != '' ? $store['txtcolor'] : '#333333';
$myfavs = maybe_unserialize(get_user_meta($uid, '__wpdm_favs', true));
$ps = isset($_GET['ps']) && $_GET['ps'] != ''?"&s=".esc_attr($_GET['ps']):'';
$pgd = isset($_GET['pg']) && $_GET['pg'] != ''?"&paged=".esc_attr($_GET['pg']):'';
$q = new WP_Query("post_type=wpdmpro&post_status=publish&posts_per_page={$items_per_page}{$pgd}&author=".$user->ID.$ps); ;

?>



<div class="w3eden user-dashboard">
    <div class="row">
        <div class="col-md-3">

            <div id="logo-block">
                <img class="shop-logo" id="shop-logo" src="<?php echo isset($store['logo']) && $store['logo'] != '' ? $store['logo'] : get_avatar_url( $current_user->user_email, array('size' => 512) ); ?>"/>
            </div>
            <div id="tabs">
                <h2 class="m-0" id="profile-title"><?php echo $store['title']; ?></h2>
                <?php echo $store['intro'] ? "<div class='mt-2 mb-2'>{$store['intro']}</div>":""; ?>
                <div class="text-small mb-3"><?php echo $store['description']; ?></div>

                <div class="list-group text-small" role="tablist">
                    <?php if($q->post_count > 0){ ?>
                        <a  class="list-group-item active" href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php _e( "My Packages" , "download-manager" ); ?></a>
                    <?php } ?>
                    <a  class="list-group-item" href="#favourites" aria-controls="favourites" role="tab" data-toggle="tab"><?php _e( "Favourites" , "download-manager" ); ?></a>
                </div>

            </div>


        </div>
        <div class="col-md-9" id="wpdm-dashboard-contents">

            <div class="tab-content">
                <div role="tabcard" class="tab-pane active" id="home">
                    <div class="row">
                        <?php

                        while ($q->have_posts()){ $q->the_post();
                            global $post;
                            if(wpdm_user_has_access(get_the_ID())){
                                ?>

                                <div class="col-md-<?php echo $cols; ?>">
                                    <?php echo \WPDM\Package::fetchTemplate($template, (array)$post); ?>
                                </div>

                                <?php
                            }}
                        ?>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php

                            global $wp_rewrite;
                            wpdm_query_var('pg') > 1 ? $current = wpdm_query_var('pg') : $current = 1;

                            $pagination = array(
                                'base' => @add_query_arg('pg','%#%'),
                                'format' => '',
                                'total' => $q->max_num_pages,
                                'current' => $current,
                                'show_all' => false,
                                'type' => 'list',
                                'prev_next'    => True,
                                'prev_text' => '<i class="icon icon-angle-left"></i> Previous',
                                'next_text' => 'Next <i class="icon icon-angle-right"></i>',
                            );


                            if( !empty($q->query_vars['ps']) )
                                $pagination['add_args'] = array('s'=>wpdm_query_var('ps'));

                            echo '<div class="text-center">' . str_replace('<ul class=\'page-numbers\'>','<ul class="pagination pagination-centered page-numbers">', paginate_links($pagination)) . '</div>';

                            ?>
                        </div>
                    </div>
                </div>
                <div role="tabcard" class="tab-pane" id="activities">...</div>
                <div role="tabcard" class="tab-pane" id="favourites">

                    <div class="row">
                        <?php if(is_array($myfavs)) foreach ($myfavs as $fav){

                            if(wpdm_user_has_access($fav)){

                                ?>
                                <div class="col-md-<?php echo $cols; ?>"><?php echo \WPDM\Package::fetchTemplate($template, array('ID' => $fav)); ?></div>
                            <?php }} ?>
                    </div>

                </div>

            </div>


        </div>





    </div>
</div>

