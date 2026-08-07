<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_my_account');

if (is_user_logged_in()) {
    do_action('woocommerce_account_navigation');
}
?>
<div class="woocommerce-MyAccount-content">
	<?php do_action('woocommerce_account_content'); ?>
</div>
<?php
do_action('woocommerce_after_my_account');
