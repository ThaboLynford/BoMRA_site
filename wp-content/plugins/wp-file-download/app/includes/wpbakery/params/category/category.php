<?php
defined('ABSPATH') || die();

/**
 * Wpfd category field param.
 *
 * @param string|array|mixed $settings Setting params
 * @param string|array|mixed $value    Field value
 *
 * @return string - html string.
 */
function vc_wpfd_category_form_field($settings, $value)
{
    $value  = htmlspecialchars($value);
    $result = '<div id="wpfd-wpbakery-choose-category-section" class="wpfd-wpbakery-choose-category-section">';
    $result .= '<a href="#wpfdwpbakerymodal" class="button wpfdwpbakerycategorylaunch" id="wpfdwpbakerycategorylaunch" title="WP File Download">';
    $result .= '<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 28 28" style="width: 20px; height: 20px; vertical-align: sub"><title>ICON REQ NEW </title><path class="cls-1" d="M24.63,18.84l-2,1.89V15.2a.6.6,0,0,0-1.2,0v5.53l-2-1.89a.6.6,0,0,0-.84,0,.53.53,0,0,0,0,.8l3,2.86a.57.57,0,0,0,.21.13h0a.54.54,0,0,0,.22,0h0a.54.54,0,0,0,.22,0h0a.62.62,0,0,0,.19-.12h0l3-2.87a.55.55,0,0,0,0-.8A.61.61,0,0,0,24.63,18.84Zm.67-15H2.74a.57.57,0,0,0-.56.56V8.23a.56.56,0,0,0,.56.56H25.3a.56.56,0,0,0,.56-.56V4.39A.57.57,0,0,0,25.3,3.83Zm-.56,3.84H3.3V5H24.74Zm-5.51,3.88H2.74a.56.56,0,0,0-.56.56v3.83a.56.56,0,0,0,.56.56H19.23a.56.56,0,0,0,.56-.56V12.11A.56.56,0,0,0,19.23,11.55Zm-.56,3.83H3.3V12.67H18.67Zm-1.75,3.88H2.74a.56.56,0,0,0-.56.56v3.84a.57.57,0,0,0,.56.56H16.92a.57.57,0,0,0,.56-.56V19.82A.56.56,0,0,0,16.92,19.26Zm-.56,3.84H3.3V20.38H16.36Z"/></svg>';
    $result .= '<span class="title"> '. esc_html__('WP File Download', 'wpfd') .'</span>';
    $result .= '</a>';
    $result .= '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpfd_category-field vc_param-name-' . $settings['param_name'] . ' ' . $settings['type'] . '" type="hidden" value="' . $value . '"/>';
    $result .= '</div>';

    return $result;
}
