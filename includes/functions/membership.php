    <?php
#----------------------------------------------------
#force coupon usage for purchasing membership
function bcc_force_coupon_usage_when_purchasing_membership()
{
    // print "<pre>";
    // print "aaa";
    // print "----------Coupon:";
    $applied_coupons = WC()->cart->get_applied_coupons();
    // print_r ($applied_coupons);
    foreach (WC()->cart->get_cart() as $cart_item) {
        // print_r($cart_item);
        $product_id  = wc_get_product($cart_item['product_id']);
        $product = $cart_item['data'];
        $quantity = $cart_item['quantity'];
        $item_name = $product->get_title();
        $price = $product->get_price();
        $my_id = $product->get_id();
        $sku = $product->get_sku();
        // print join("\n:", array($item_name, $quantity,$price, $sku));
        $category_ids = $product->get_category_ids();
        // print_r($category_ids);
        // print "\n------";
        // print "myid: " . $my_id;
        // print_r($product_id);
        $cat_names = get_the_terms($my_id, 'product_cat');
        // print_r($cat_names);
        // foreach ($cat_names as $cat_name) {
        //     print $cat_name->slug . "\n";
        // }
        // print "\n------meta\n";
        // $formatted_meta_data = $item->get_formatted_meta_data();
        // $item_meta_data = $product->get_meta_data();
        $item_meta_data = $product->get_meta("required_coupon");
       //  echo '<pre>';
       //  echo "item_meta_data:<br/>";
       //  print_r($item_meta_data);
       //  echo "<br/>";
       //  print "\n------end meta\n";
       //  if (isset($item_meta_data)){
       //     print "isset<br/>";
       //     }
       // if (empty($item_meta_data)){
       //    print "empty<br/>";
       //    }
       //  if (is_null($item_meta_data)){
       //         print "is_null<br/>";
       //     }
       //  print "</pre>";


        $result = true;

//        if (isset($item_meta_data)) {
        if (!empty($item_meta_data)) {
            // print "Coupon is required";
            if (in_array($item_meta_data, $applied_coupons)) {
                $msg =  "we have applied the correct coupon";
                // print $msg;
                wc_print_notice($msg, "success");
                $result = true;
            } else {
                print "item_meta_data: " . $item_meta_data;
                print "applied coupons:";
                print_r($applied_coupons);
                $msg =  "we have NOT applied the correct coupon";
                // print $msg;
                wc_print_notice($msg, "error");
                $result = false;
            }
        }

        // if (empty($item_meta_data)){
        //     print "empty";
        // }
        // if (is_($item_meta_data)){
        //     print "is_";
        // }
    }


    print "</pre>";
    return $result;
}

// add_action('woocommerce_review_order_before_submit', 'bcc_force_coupon_usage_when_purchasing_membership', 10, 0);
#----------------------------------------------------
// Replacing the Place order button when total volume exceed 68 m3
add_filter('woocommerce_order_button_html', 'replace_order_button_html', 10, 2);
function replace_order_button_html($order_button)
{
    // Only when total volume is up to 68

    if (bcc_force_coupon_usage_when_purchasing_membership()) {
        return $order_button;
    }

    $order_button_text = __("Return to Shopping Cart", "woocommerce");

    $style = ' style="color:#fff;background-color:#999;"';
    return '<a class="button alt"'.$style.' name="woocommerce_checkout_place_order" id="place_order" href="/cart">' . esc_html($order_button_text) . '</a>';
}
#----------------------------------------------------
add_action('woocommerce_before_calculate_totals', 'custom_cart_items_prices', 10, 1);
function custom_cart_items_prices($cart)
{
    if (is_admin() && ! defined('DOING_AJAX')) {
        return;
    }

    if (did_action('woocommerce_before_calculate_totals') >= 2) {
        return;
    }

    if (!is_cart()) {
        return;
    }

    // Loop through cart items
    foreach ($cart->get_cart() as $cart_item) {

        // Get an instance of the WC_Product object
        $product = $cart_item['data'];
        // print "<pre>";
        // print_r($product);
        // print "</pre>";

        $sku = method_exists($product, 'get_sku') ? $product->get_sku() : $product->post->sku;
        print "SKU: $sku";

        if (preg_match('/^membership-/', $sku)) {
            // Get the product name (Added Woocommerce 3+ compatibility)
            $original_name = method_exists($product, 'get_name') ? $product->get_name() : $product->post->post_title;

            // SET THE NEW NAME
            $new_name = $original_name . ': <div class="coupon-required">Coupon required to order this item</div>';

            // Set the new name (WooCommerce versions 2.5.x to 3+)
            if (method_exists($product, 'set_name')) {
                $product->set_name($new_name);
            } else {
                $product->post->post_title = $new_name;
            }
        }
    }
}
#----------------------------------------------------
// froom: https://gist.github.com/cartpauj/0f4dbbe189b5315262a6d2bb0a0499ad

function mepr_must_fill_out_coupon_code($errors)
{
    // print "<pre>";
    // print_r($_POST);
    // print "</pre>";

    #get all the published coupons
    $membership_product_id = $_POST['mepr_product_id'];
    $my_coupon_code = $_POST['mepr_member_code'];

    // WP_Query argumentsn
    $args = array(
    'post_type'              => array( 'memberpresscoupon' ),
    'post_status'            => array( 'publish' ),
    );

    // The Query
    $query = new WP_Query($args);

    // The Loop
    $found= false;
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            // $found = FALSE;
            $title = get_the_title();
            // print $title;
            if ($title != $my_coupon_code) {
                //this is not the coupon we want, skip to next one
                continue;
            }
            //for this coupon, get a list of memberships it applies to
            $memberships_list = get_post_meta(get_the_ID(), '_mepr_coupons_valid_products');
            // print_r($memberships_list);

            // for each membership type that this coupon is good for
            foreach ($memberships_list as $index => $array) {
                // print_r($array);
                if (in_array($membership_product_id, $array)) {
                    // print "found $membership_product_id";
                    $found = true;
                    // if ($found) {
                    //     print "Found:aaa yes";
                    // } else {
                    //     print "Found:aaa no";
                    // }
                    break;
                }
            }

            // do something
        }
    } else {
        $errors[] = "There are no current active Member codes right now.";
        // no posts found
    }
    // if ($found) {
    //     print "Found: yes";
    // } else {
    //     print "Found: no";
    // }
    if (!$found) {
        $errors[] = "Member code does not match required value.";
    }

    // Restore original Post Data
    wp_reset_postdata();

    return $errors;
}
add_filter('mepr-validate-signup', 'mepr_must_fill_out_coupon_code', 11, 1);

#----------------------------------------------------
// based on https://codex.wordpress.org/Function_Reference/wp_loginout
 add_filter('wp_nav_menu_bootstrap-menu02a_items', 'wpsites_loginout_menu_link');

function wpsites_loginout_menu_link($menu)
{
    $my_dropdown = '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-user-menu" class="menu-useful-links menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children dropdown menu-item-user-menu nav-item loginout-menu"><a title="Useful Links" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle nav-link" id="menu-item-dropdown-user-menu"><i class="fa fa-user-o" aria-hidden="true"></i></a>';

    $my_dropdown .= '<ul class="dropdown-menu" aria-labelledby="menu-item-dropdown-user-menu" role="menu">';

    if (is_user_logged_in()) {
        $item = '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-account-menu" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-account-menu nav-item account-menu"><a title="My Account" class="dropdown-item" href="/account">My Account</a></li>';
        $my_dropdown .= $item;
    }

    $loginout = wp_loginout($_SERVER['REQUEST_URI'], false);
    $item = '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-loginout-menu" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-loginout-menu nav-item account-menu loginout-menu-item">' . $loginout . '</l1>';
    $my_dropdown .= $item;

    $my_dropdown .= '</ul>';
    $my_dropdown .= '</li>';

    $menu .= $my_dropdown;
    return $menu;
}
#----------------------------------------------------
// based on https://codex.wordpress.org/Plugin_API/Filter_Reference/login_url
add_filter('login_url', 'my_login_page', 10, 3);
function my_login_page($login_url, $redirect, $force_reauth)
{
    $login_page = home_url('/login/');
    $login_url = add_query_arg('redirect_to', $redirect, $login_page);
    return $login_url;
}
#----------------------------------------------------
// based on https://core.trac.wordpress.org/browser/tags/5.2.1/src/wp-includes/general-template.php#L0
add_filter('loginout', 'bcc_wp_loginout', 10, 3);

function bcc_wp_loginout($content)
{
    if (! is_user_logged_in()) {
        $content = str_replace("<a ", '<a title="Sign In" class="dropdown-item"', $content);
    } else {
        $content = str_replace("<a ", '<a title="Sign Out" class="dropdown-item"', $content);
    }
    return  $content;
}
#----------------------------------------------------
#----------------------------------------------------
#----------------------------------------------------
# baed on https://wordpress.stackexchange.com/questions/307362/how-to-set-up-user-email-verification-after-signup
add_action('user_register', 'my_registration', 10, 2);
function my_registration($user_id)
{
    if (is_user_logged_in()) {
        // we are already logged in, so getting a second or third membership
        // no need to validate
        return;
    } else {
        // get user data
        $user_info = get_userdata($user_id);
        // create md5 code to verify later
        $code = md5(time());
        // make it into a code to send it to user via email
        $string = array('id'=>$user_id, 'code'=>$code);
        // create the activation code and activation status
        update_user_meta($user_id, 'account_activated', 0);
        update_user_meta($user_id, 'activation_code', $code);
        // create the url
        $code= base64_encode(serialize($string));
        // print "<pre>";
        // print $code;
        // print "</pre>";

        $url = get_home_url(). '/login/?act=' . $code;
        // basically we will edit here to make this nicer
        $body = '<p>Please click the following link to confirm your email address for your CaplanSchool.com account: </p><p> <a href="'.$url.'">'.$url.'</a></p>';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $subject = "CaplanSchool.com email confirmation link for activation";
        // send an email out to user
        wp_mail($user_info->user_email, __($subject, 'text-domain'), $body, $headers);
    }
}

#-----------------------------
add_action('after_setup_theme', 'verify_user_code');
function verify_user_code()
{
    // print "<pre>";
    // print "aaa";
    // print_r($_GET);
    // print "</pre>";

    if (isset($_GET['act'])) {
        $data = unserialize(base64_decode($_GET['act']));

        $code = get_user_meta($data['id'], 'activation_code', true);
        // print "<pre>";
        // print_r($data);
        // print $code;
        // print "</pre>";
        // verify whether the code given is the same as ours
        if ($code == $data['code']) {
            // print "<pre>";
            // print "ccc";
            // print "</pre>";

            // update the user meta
            update_user_meta($data['id'], 'account_activated', 1);
        }
    }
}
#--------------------------------------
// based on https://wordpress.stackexchange.com/questions/307378/check-for-user-meta-data-at-login
function isUserActivated($username){

    // First need to get the user object
    $user = get_user_by('login', $username);
    if(!$user) {
        $user = get_user_by('email', $username);
        if(!$user) {
            return $username;
        }
    }

    $userStatus = get_user_meta($user->ID, 'account_activated', true);


    //for testing $userStatus = 1;
    if($userStatus == 0){
        $login_page  = home_url('login/?redirect_to=/');
        wp_redirect($login_page . "?login=failed_email_not_confirmed");
        exit;

    }

}

add_action('wp_authenticate', 'isUserActivated');
#--------------------------------------

// based on https://smallenvelop.com/add-custom-message-wordpress-login-page/
//* Add custom message to WordPress login page

function smallenvelop_login_message($message)
{
    if (empty($message)) {
        return "<p><strong>Welcome to SmallEnvelop. Please login to continue</strong></p>";
    } else {
        return $message;
    }
}

// add_filter( 'login_message', 'smallenvelop_login_message' );
#https://gist.github.com/cartpauj/088e47c55718582753b864881f03d33d
#-----------------------------------
function mepr_disable_auto_login($auto_login, $membership_id, $mepr_user)
{
    return false;
}
add_filter('mepr-auto-login', 'mepr_disable_auto_login', 3, 3);

#-----------------------------------
#based on https://wordpress.stackexchange.com/questions/247729/how-to-restrict-user-login-whenever-if-a-user-puts-on-hold-by-editing-wp-login-a
add_filter('authenticate', 'myplugin_authenticate_account_activated', 21);#-----------------------------------
function myplugin_authenticate_account_activated($user){


    // username and password are correct
    if ($user instanceof WP_User) {
        $account_activated = get_user_meta($user->ID, 'account_activated', true);
        if ($account_activated == 0) {
            return new WP_Error('denied', "Please check your email for an account activation link before logging in.");
            // return new WP_Error('denied', 'not activated');
        }
    }

    return $user;
}

#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
#-----------------------------------
