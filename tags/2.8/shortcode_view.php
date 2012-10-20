<div id="wp_paypal_button_cont">

	<select id="wp_paypal_button_options">
		<?php echo $html_options; ?>
	</select>

	<br />

	<label for="customer_email_address"><?php echo $reference; ?></label> <br />
	<p><input type="text" id="customer_email_address" /></p>

	<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="<?php echo $email; ?>">
		<input type="hidden" name="currency_code" value="<?php echo $currency; ?>">
		<input type="hidden" name="item_name" value="">
		<input type="hidden" name="amount" value="">
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<input type="hidden" name="email" value="" />
	</form>
	<input type="image" id="buy_now_button" src="<?php echo $payment_button_img_src; ?>" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</div>

<style>
#wp_paypal_button_options{
	margin-bottom: 15px;
}
</style>

<script type="text/javascript">
jQuery('#wp_paypal_button_cont #buy_now_button').click(function(e){
	e.preventDefault();
	var price = jQuery('#wp_paypal_button_options').val();
	var name = jQuery('#wp_paypal_button_options :selected').attr('data-product_name');
	var email = jQuery('#customer_email_address').val();
	
	jQuery('input[name=email]').val(email);
	jQuery('input[name=amount]').val(price);
	jQuery('input[name=item_name]').val(name);

	jQuery('#wp_paypal_button_cont form').submit();
});
</script>