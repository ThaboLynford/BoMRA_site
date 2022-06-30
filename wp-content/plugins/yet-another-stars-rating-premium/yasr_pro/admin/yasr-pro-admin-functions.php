<?php
/*

Copyright 2020 Dario Curvino (email : d.curvino@tiscali.it)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly


/**
 * This function print the right box for premium version
 * If platinum license is used, then print Skype and slack support
 *
 * @param $position
 */
function yasr_pro_settings_panel_support($position) {

    if ($position && $position === "bottom") {
        $yasr_metabox_class = "yasr-donatedivbottom";
    }  else {
        $yasr_metabox_class = "yasr-donatedivdx";
    }

    $url = admin_url(). 'admin.php?page=yasr_settings_page-contact';

    $div = "<div class='$yasr_metabox_class' id='yasr-ask-five-stars' style='display:none;'>";

    $text = '<div class="yasr-donate-title">
                <span class="dashicons dashicons-unlock"></span>'
        . __('You\'re using YASR Pro!', 'yet-another-stars-rating') .
        '</div>';
    $text .= '<div class="yasr-donate-single-resource">
                <span class="dashicons dashicons-editor-help" style="color: #ccc"></span>
                    <a href="'.$url.'">'
        . __('Help', 'yet-another-stars-rating') .
        '</a>
               </div>';

    if(yasr_fs()->is_plan('yasr_platinum') ) {

        $text .= '<div class="yasr-donate-single-resource">
                <span class="dashicons dashicons-format-chat" style="color: #ccc"></span>
                    <a target="blank" href="skype:live:support_58062">'
            . __('Skype support', 'yet-another-stars-rating') .
            '</a>
               </div>';

        $text .= '<div class="yasr-donate-single-resource">
                <span class="dashicons dashicons-format-chat" style="color: #ccc"></span>
                    <a target="blank" href="https://wordpress.slack.com/messages/D2BUTQNDP">'
            . __('Slack support', 'yet-another-stars-rating') .
            '</a>
               </div>';

    }

    $div_and_text = $div . $text . '</div>';

    echo $div_and_text;

}