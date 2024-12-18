<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


// refactor to include files here

require_once dirname(__FILE__) . '/includes/functions/index.php';

// require_once dirname(__FILE__) . '/includes/functions/nulling.php';
// require_once dirname(__FILE__) . '/includes/functions/membership.php';
// require_once dirname(__FILE__) . '/includes/functions/woocommerce.php';

// require_once dirname(__FILE__) . '/includes/functions/dequeue.php';
// require_once dirname(__FILE__) . '/includes/functions/enqueue_base.php';

require_once dirname(__FILE__) . '/includes/shortcodes/index.php';

require_once dirname(__FILE__) . '/includes/deprecated/index.php';

require_once dirname(__FILE__) . '/includes/acf-overrides/index.php';
require_once dirname(__FILE__) . '/includes/theme-setup/index.php';
require_once dirname(__FILE__) . '/includes/woocommerce-overrides/index.php';
require_once dirname(__FILE__) . '/includes/woocommerce-bootstrap-overrides/index.php';

#----------------------------------------------------
add_filter( 'graphql_jwt_auth_secret_key', function() {
return 'VKEwyW%PDE|J*C]}BF,k<H#Z80 vjukmya.q^NWY!O8-(iXbe&hYL9?VQ|^U%@.>';
});
#----------------------------------------------------

#add navigation menu location for testing nextjs
register_nav_menus(
	array(
		'nextjs01' => __( 'NextJS Test Menu 01', 'understrap' ),
		)
	);
