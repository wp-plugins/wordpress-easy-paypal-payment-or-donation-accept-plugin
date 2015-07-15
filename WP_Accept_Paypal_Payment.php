<?php
/*
  Plugin Name: WP Easy Paypal Payment Accept
  Version: v4.7
  Plugin URI: https://www.tipsandtricks-hq.com/wordpress-easy-paypal-payment-or-donation-accept-plugin-120
  Author: Tips and Tricks HQ
  Author URI: https://www.tipsandtricks-hq.com/
  Description: Easy to use Wordpress plugin to accept paypal payment for a service or product or donation in one click. Can be used in the sidebar, posts and pages.
  License: GPL2
 */

define('WP_PAYPAL_PAYMENT_ACCEPT_PLUGIN_VERSION', '4.7');
define('WP_PAYPAL_PAYMENT_ACCEPT_PLUGIN_URL', plugins_url('', __FILE__));

include_once('shortcode_view.php');
include_once('wpapp_admin_menu.php');
include_once('wpapp_paypal_utility.php');

function wp_pp_plugin_install() {
    // Some default options
    add_option('wp_pp_payment_email', get_bloginfo('admin_email'));
    add_option('paypal_payment_currency', 'USD');
    add_option('wp_pp_payment_subject', 'Plugin Service Payment');
    add_option('wp_pp_payment_item1', 'Basic Service - $10');
    add_option('wp_pp_payment_value1', '10');
    add_option('wp_pp_payment_item2', 'Gold Service - $20');
    add_option('wp_pp_payment_value2', '20');
    add_option('wp_pp_payment_item3', 'Platinum Service - $30');
    add_option('wp_pp_payment_value3', '30');
    add_option('wp_paypal_widget_title_name', 'Paypal Payment');
    add_option('payment_button_type', 'https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif');
    add_option('wp_pp_show_other_amount', '-1');
    add_option('wp_pp_show_ref_box', '1');
    add_option('wp_pp_ref_title', 'Your Email Address');
    add_option('wp_pp_return_url', home_url());
}

register_activation_hook(__FILE__, 'wp_pp_plugin_install');

add_shortcode('wp_paypal_payment_box_for_any_amount', 'wpapp_buy_now_any_amt_handler');

function wpapp_buy_now_any_amt_handler($args) {
    $output = wppp_render_paypal_button_with_other_amt($args);
    return $output;
}

add_shortcode('wp_paypal_payment_box', 'wpapp_buy_now_button_shortcode');

function wpapp_buy_now_button_shortcode($args) {
    ob_start();
    wppp_render_paypal_button_form($args);
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

function Paypal_payment_accept() {
    $paypal_email = get_option('wp_pp_payment_email');
    $payment_currency = get_option('paypal_payment_currency');
    $paypal_subject = get_option('wp_pp_payment_subject');

    $itemName1 = get_option('wp_pp_payment_item1');
    $value1 = get_option('wp_pp_payment_value1');
    $itemName2 = get_option('wp_pp_payment_item2');
    $value2 = get_option('wp_pp_payment_value2');
    $itemName3 = get_option('wp_pp_payment_item3');
    $value3 = get_option('wp_pp_payment_value3');
    $itemName4 = get_option('wp_pp_payment_item4');
    $value4 = get_option('wp_pp_payment_value4');
    $itemName5 = get_option('wp_pp_payment_item5');
    $value5 = get_option('wp_pp_payment_value5');
    $itemName6 = get_option('wp_pp_payment_item6');
    $value6 = get_option('wp_pp_payment_value6');
    $payment_button = get_option('payment_button_type');
    $wp_pp_show_other_amount = get_option('wp_pp_show_other_amount');
    $wp_pp_show_ref_box = get_option('wp_pp_show_ref_box');
    $wp_pp_ref_title = get_option('wp_pp_ref_title');
    $wp_pp_return_url = get_option('wp_pp_return_url');

    /* === Paypal form === */
    $output = '';
    $output .= '<div id="accept_paypal_payment_form">';
    $output .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="wp_accept_pp_button_form_classic">';
    $output .= '<input type="hidden" name="cmd" value="_xclick" />';
    $output .= "<input type=\"hidden\" name=\"business\" value=\"$paypal_email\" />";
    $output .= "<input type=\"hidden\" name=\"item_name\" value=\"$paypal_subject\" />";
    $output .= "<input type=\"hidden\" name=\"currency_code\" value=\"$payment_currency\" />";
    $output .= "<span style=\"font-size:10.0pt\"><strong> $paypal_subject</strong></span><br /><br />";
    $output .= '<select id="amount" name="amount" class="">';
    $output .= "<option value=\"$value1\">$itemName1</option>";
    if ($value2 != 0) {
        $output .= "<option value=\"$value2\">$itemName2</option>";
    }
    if ($value3 != 0) {
        $output .= "<option value=\"$value3\">$itemName3</option>";
    }
    if ($value4 != 0) {
        $output .= "<option value=\"$value4\">$itemName4</option>";
    }
    if ($value5 != 0) {
        $output .= "<option value=\"$value5\">$itemName5</option>";
    }
    if ($value6 != 0) {
        $output .= "<option value=\"$value6\">$itemName6</option>";
    }

    $output .= '</select>';

    // Show other amount text box
    if ($wp_pp_show_other_amount == '1') {
        $output .= '<br /><br /><strong>Other Amount:</strong>';
        $output .= '<br /><br /><input type="number" min="1" step="any" name="other_amount" title="Other Amount" value="" style="max-width:60px;" />';
    }

    // Show the reference text box
    if ($wp_pp_show_ref_box == '1') {
        $output .= "<br /><br /><strong> $wp_pp_ref_title :</strong>";
        $output .= '<input type="hidden" name="on0" value="'.apply_filters('wp_pp_button_reference_name','Reference').'" />';
        $output .= '<br /><input type="text" name="os0" maxlength="60" value="'.apply_filters('wp_pp_button_reference_value','').'" class="wp_pp_button_reference" />';
    }

    $output .= '<br /><br />
        <input type="hidden" name="no_shipping" value="0" />
        <input type="hidden" name="no_note" value="1" />
        <input type="hidden" name="bn" value="TipsandTricks_SP" />';
    
    if (!empty($wp_pp_return_url)) {
        $output .= '<input type="hidden" name="return" value="' . $wp_pp_return_url . '" />';
    } else {
        $output .='<input type="hidden" name="return" value="' . home_url() . '" />';
    }

    $output .= "<input type=\"image\" src=\"$payment_button\" name=\"submit\" alt=\"Make payments with payPal - it's fast, free and secure!\" />";
    $output .= '</form>';
    $output .= '</div>';
    $output .= <<<EOT
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.wp_accept_pp_button_form_classic').submit(function(e){
        var form_obj = $(this);
        var other_amt = form_obj.find('input[name=other_amount]').val();
        if (!isNaN(other_amt) && other_amt.length > 0){
            options_val = other_amt;
            //insert the amount field in the form with the custom amount
            $('<input>').attr({
                type: 'hidden',
                id: 'amount',
                name: 'amount',
                value: options_val
            }).appendTo(form_obj);
        }		
        return;
    });
});
</script>
EOT;
    /* = end of paypal form = */
    return $output;
}

function wp_ppp_process($content) {
    if (strpos($content, "<!-- wp_paypal_payment -->") !== FALSE) {
        $content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
        $content = str_replace('<!-- wp_paypal_payment -->', Paypal_payment_accept(), $content);
    }
    return $content;
}

function show_wp_paypal_payment_widget($args) {
    extract($args);

    $wp_paypal_payment_widget_title_name_value = get_option('wp_paypal_widget_title_name');
    echo $before_widget;
    echo $before_title . $wp_paypal_payment_widget_title_name_value . $after_title;
    echo Paypal_payment_accept();
    echo $after_widget;
}

function wp_paypal_payment_widget_control() {
    ?>
    <p>
    <? _e("Set the Plugin Settings from the Settings menu"); ?>
    </p>
    <?php
}

function wp_paypal_payment_init() {
    wp_register_style('wpapp-styles', WP_PAYPAL_PAYMENT_ACCEPT_PLUGIN_URL . '/wpapp-styles.css');
    wp_enqueue_style('wpapp-styles');

    //Widget code
    $widget_options = array('classname' => 'widget_wp_paypal_payment', 'description' => __("Display WP Paypal Payment."));
    wp_register_sidebar_widget('wp_paypal_payment_widgets', __('WP Paypal Payment'), 'show_wp_paypal_payment_widget', $widget_options);
    wp_register_widget_control('wp_paypal_payment_widgets', __('WP Paypal Payment'), 'wp_paypal_payment_widget_control');
    
    //Listen for IPN and validate it
    if (isset($_REQUEST['wpapp_paypal_ipn']) && $_REQUEST['wpapp_paypal_ipn'] == "process") {
        wpapp_validate_paypl_ipn();
        exit;
    }
}

function wpapp_shortcode_plugin_enqueue_jquery() {
    wp_enqueue_script('jquery');
}

add_filter('the_content', 'wp_ppp_process');
add_shortcode('wp_paypal_payment', 'Paypal_payment_accept');
if (!is_admin()) {
    add_filter('widget_text', 'do_shortcode');
}

add_action('init', 'wpapp_shortcode_plugin_enqueue_jquery');
add_action('init', 'wp_paypal_payment_init');
