<?php 

function wppp_render_paypal_button_form($args)
{	
	extract( shortcode_atts( array(
		'email' => 'your@paypal-email.com',
		'currency' => 'USD',
		'options' => 'Payment for Service 1:15.50|Payment for Service 2:30.00|Payment for Service 3:47.00',
		'return' => site_url(),
		'reference' => 'Your Email Address',
		'other_amount' => '',
		'country_code' => '',
		'payment_subject' => '',
		'button_image' => ''
	), $args));
	
	$options = explode( '|' , $options);
	$html_options = '';
	foreach( $options as $option ) {
		$option = explode( ':' , $option );
		$name = esc_attr( $option[0] );
		$price = esc_attr( $option[1] );
		$html_options .= "<option data-product_name='{$name}' value='{$price}'>{$name} - {$price}</option>";
	}
	
	$payment_button_img_src = get_option('payment_button_type');
	if(!empty($button_image)){
		$payment_button_img_src = $button_image;
	}
	
?>
<div class="wp_paypal_button_widget">
	<form name="_xclick" class="wp_accept_pp_button_form" action="https://www.paypal.com/cgi-bin/webscr" method="post">	
		<div class="wp_pp_button_selection_section">
		<select class="wp_paypal_button_options">
			<?php echo $html_options; ?>
		</select>
		</div>
		
		<?php 
		if(!empty($other_amount)){
			echo '<div class="wp_pp_button_other_amt_section">';
			echo 'Other Amount: <input type="text" name="other_amount" value="" size="4">';
			echo '</div>';
		}
		?>

		<div class="wp_pp_button_reference_section">
		<label for="wp_pp_button_reference"><?php echo $reference; ?></label>
		<br />
		<input type="hidden" name="on0" value="Reference" />
		<input type="text" name="os0" value="" class="wp_pp_button_reference" />
		</div>

		<?php 
		if(!empty($payment_subject)){
		?>
		<input type="hidden" name="on1" value="Payment Subject" />
		<input type="hidden" name="os1" value="<?php echo $payment_subject; ?>" />
		<?php } ?>
		
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="<?php echo $email; ?>">
		<input type="hidden" name="currency_code" value="<?php echo $currency; ?>">
		<input type="hidden" name="item_name" value="">
		<input type="hidden" name="amount" value="">
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<input type="hidden" name="email" value="" />
		<?php 
		if(!empty($country_code)){
			echo '<input type="hidden" name="lc" value="'.$country_code.'" />';
		}
		?>
		<div class="wp_pp_button_submit_btn">
			<input type="image" id="buy_now_button" src="<?php echo $payment_button_img_src; ?>" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
		</div>
	</form>	
</div>

<style>
.wp_pp_button_selection_section, .wp_pp_button_other_amt_section, .wp_pp_button_reference_section{
margin-bottom: 10px;
}
.wp_paypal_button_widget{
margin: 10px 0;
}
.wp_accept_pp_button_form input{
width: auto !important;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.wp_accept_pp_button_form').submit(function(e){	
		var form_obj = $(this);
		var options_name = form_obj.find('.wp_paypal_button_options :selected').attr('data-product_name');
		form_obj.find('input[name=item_name]').val(options_name);
		
		var options_val = form_obj.find('.wp_paypal_button_options').val();
		var other_amt = form_obj.find('input[name=other_amount]').val();
		if (!isNaN(other_amt) && other_amt.length > 0){
			options_val = other_amt;
		}
		form_obj.find('input[name=amount]').val(options_val);
		return;
	});
});
</script>
<?php 
}
