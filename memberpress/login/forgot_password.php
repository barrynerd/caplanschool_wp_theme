<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<div class="mp_wrapper mepr_forgot_password_form">

  <form name="mepr_forgot_password_form" id="mepr_forgot_password_form" action="" method="post">
    <?php /* nonce not necessary on this form seeing as the user isn't logged in yet */ ?>
    <div class="mp-form-row mepr_forgot_password_input">
      <!-- <label><?php _ex('Enter Your Username or Email Address', 'ui', 'memberpress'); ?></label> -->
      <input type="text" name="mepr_user_or_email" id="mepr_user_or_email" placeholder="<?php _ex('Enter Your Email Address', 'ui', 'memberpress'); ?>" value="<?php echo isset($mepr_user_or_email)?esc_html($mepr_user_or_email):''; ?>" />
    </div>
    <?php MeprHooks::do_action('mepr-forgot-password-form'); ?>
    <div class="mp-spacer">&nbsp;</div>
    <div class="submit">
      <input type="submit" name="wp-submit" id="wp-submit" class="button-primary mepr-share-button btn btn-primary col-12 share-button" value="<?php _ex('Request Password Reset', 'ui', 'memberpress'); ?>" />
      <input type="hidden" name="mepr_process_forgot_password_form" value="true" />
    </div>
  </form>
</div>
