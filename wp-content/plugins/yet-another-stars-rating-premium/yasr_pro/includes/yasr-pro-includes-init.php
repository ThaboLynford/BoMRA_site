<?php
/*

Copyright 2014 Dario Curvino (email : d.curvino@tiscali.it)

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

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'You\'re not allowed to see this page' );
} // Exit if accessed directly


/**
 * Callback function for the spl_autoload_register above.
 * Load classes from yasr_pro/includes/classess and yasr_pro/includes/editor
 *
 * @param $class
 */

function yasr_pro_autoload_includes_classes($class) {
    /**
     * If the class being requested does not start with 'Yasr' prefix,
     * it's not in Yasr Project
     */
    if (0 !== strpos($class, 'Yasr')) {
        return;
    }
    $include_classes = YASR_PRO_ABSOLUTE_PATH_INCLUDES . '/classes/' . $class . '.php';

    // check if file exists, just to be sure
    if (file_exists($include_classes)) {
        require($include_classes);
    }

    $include_shortcode_classes = YASR_PRO_ABSOLUTE_PATH_INCLUDES . '/shortcodes/classes/' . $class . '.php';

    // check if file exists, just to be sure
    if (file_exists($include_shortcode_classes)) {
        require($include_shortcode_classes);
    }

}

//AutoLoad Yasr Shortcode Classes, only when a object is created
spl_autoload_register('yasr_pro_autoload_includes_classes');

// require functions
require 'yasr-pro-includes-functions.php';
require 'rest/yasr-pro-rest.php';

//Load admin action and filters
$load_includes = new YasrProLoadIncludes();
$load_includes->init();