<?php
    if (! defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }

#----------------------------------------------------
// restore custom fields edit box that acf plugin is hiding
// see https://www.wpbeginner.com/wp-tutorials/how-to-fix-custom-fields-not-showing-in-wordpress/
add_filter('acf/settings/remove_wp_meta_box', '__return_false');
