<?php
/*
Plugin Name: WP Easy Paypal Payment Accept
Version: v2.2.1
Plugin URI: http://www.tipsandtricks-hq.com/?page_id=120
Author: Ruhul Amin
Author URI: http://www.antique-hq.com/
Plugin Description: Easy to use Wordpress plugin to accept paypal payment for a service or product or donation in one click. Can be used in the sidebar, posts and pages.
*/

/*
    This program is free software; you can redistribute it
    under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

$wp_paypal_payment_version = 2.2.1;

// Some default options
add_option('wp_pp_payment_email', 'korin.iverson@gmail.com');
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

function Paypal_payment_accept()
{
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

    /* === Paypal form === */
    $output .= '
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_xclick" />
    ';
    $output .= "<input type=\"hidden\" name=\"business\" value=\"$paypal_email\" />";
    $output .= "<input type=\"hidden\" name=\"item_name\" value=\"$paypal_subject\" />";
    $output .= "<input type=\"hidden\" name=\"currency_code\" value=\"$payment_currency\" />";
    $output .= "<span style=\"font-size:10.0pt\"><strong> $paypal_subject</strong></span><br /><br />";
    $output .= '<select id="amount" name="amount" class="">';
    $output .= "<option value=\"$value1\">$itemName1</option>";
    if($value2 != 0)
    {
        $output .= "<option value=\"$value2\">$itemName2</option>";
    }
    if($value3 != 0)
    {
        $output .= "<option value=\"$value3\">$itemName3</option>";
    }
    if($value4 != 0)
    {
        $output .= "<option value=\"$value4\">$itemName4</option>";
    }
    if($value5 != 0)
    {
        $output .= "<option value=\"$value5\">$itemName5</option>";
    }
    if($value6 != 0)
    {
        $output .= "<option value=\"$value6\">$itemName6</option>";
    }

    $output .= '</select>';
    $output .= '
    <br /><br />
    <input type="hidden" name="on0" value="Your Email  Address" /><strong>Your Email  Address:</strong>
    <br /><br />
    <input type="text" name="os0" maxlength="60" />
        <br /><br />
        <input type="hidden" name="no_shipping" value="2" />
        <input type="hidden" name="no_note" value="1" />
        <input type="hidden" name="bn" value="IC_Sample" />
    ';
    $output .= "<input type=\"image\" src=\"$payment_button\" name=\"submit\" alt=\"Make payments with payPal - it's fast, free and secure!\" />";
    $output .= '</form>';
    /* = end of paypal form = */
    return $output;
}

function wp_ppp_process($content)
{
    if (strpos($content, "<!-- wp_paypal_payment -->") !== FALSE)
    {
        $content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
        $content = str_replace('<!-- wp_paypal_payment -->', Paypal_payment_accept(), $content);
    }
    return $content;
}


// Displays PayPal Payment Accept Options menu
function paypal_payment_add_option_pages() {
    if (function_exists('add_options_page')) {
        add_options_page('WP Paypal Payment Accept', 'WP PayPal Payment', 8, __FILE__, 'paypal_payment_options_page');
    }
}

function paypal_payment_options_page() {

    global $wp_paypal_payment_version;

    if (isset($_POST['info_update']))
    {
        echo '<div id="message" class="updated fade"><p><strong>';

      update_option('wp_paypal_widget_title_name', (string)$_POST["wp_paypal_widget_title_name"]);
        update_option('wp_pp_payment_email', (string)$_POST["wp_pp_payment_email"]);
        update_option('paypal_payment_currency', (string)$_POST["paypal_payment_currency"]);
        update_option('wp_pp_payment_subject', (string)$_POST["wp_pp_payment_subject"]);
        update_option('wp_pp_payment_item1', (string)$_POST["wp_pp_payment_item1"]);
        update_option('wp_pp_payment_value1', (int)$_POST["wp_pp_payment_value1"]);
        update_option('wp_pp_payment_item2', (string)$_POST["wp_pp_payment_item2"]);
        update_option('wp_pp_payment_value2', (int)$_POST["wp_pp_payment_value2"]);
        update_option('wp_pp_payment_item3', (string)$_POST["wp_pp_payment_item3"]);
        update_option('wp_pp_payment_value3', (int)$_POST["wp_pp_payment_value3"]);
        update_option('wp_pp_payment_item4', (string)$_POST["wp_pp_payment_item4"]);
        update_option('wp_pp_payment_value4', (int)$_POST["wp_pp_payment_value4"]);
        update_option('wp_pp_payment_item5', (string)$_POST["wp_pp_payment_item5"]);
        update_option('wp_pp_payment_value5', (int)$_POST["wp_pp_payment_value5"]);
        update_option('wp_pp_payment_item6', (string)$_POST["wp_pp_payment_item6"]);
        update_option('wp_pp_payment_value6', (int)$_POST["wp_pp_payment_value6"]);
        update_option('payment_button_type', (string)$_POST["payment_button_type"]);
        echo 'Options Updated!';
        echo '</strong></p></div>';
    }

    $paypal_payment_currency = stripslashes(get_option('paypal_payment_currency'));
    $payment_button_type = stripslashes(get_option('payment_button_type'));

    ?>

    <div class=wrap>

    <h2>Accept Paypal Payment Settings v <?php echo $wp_paypal_payment_version; ?></h2>

    <p>For information and updates, please visit:<br />
    <a href="http://www.tipsandtricks-hq.com/?p=120">http://www.tipsandtricks-hq.com/</a></p>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <input type="hidden" name="info_update" id="info_update" value="true" />

    <fieldset class="options">
    <legend>Usage:</legend>

    <p>There are three ways you can use this plugin:</p>
    <ol>
    <li>1. Add the trigger text <strong>&lt;!-- wp_paypal_payment --&gt;</strong> to a post or page</li>
    <li>2. Call the function from a template file: <strong>&lt;?php echo Paypal_payment_accept(); ?&gt;</strong></li>
    <li>3. Use the <strong>WP Paypal Payment</strong> Widget from the Widgets menu</li>
    </ol>

    </fieldset>

    <fieldset class="options">
    <strong><legend>WP Paypal Payment or Donation Accept Plugin Options</legend></strong><br />

    <strong>WP Paypal Payment Widget Title :</strong>
        <input name="wp_paypal_widget_title_name" type="text" size="30" value="<?php echo get_option('wp_paypal_widget_title_name'); ?>"/>
        <br /><i>This will be the title of the Widget on the Sidebar if you use it.</i>
    <br /><br />

    <table width="100%" border="0" cellspacing="0" cellpadding="6">

    <tr valign="top"><td width="25%" align="right">
    <strong>Paypal Email address:</strong>
    </td><td align="left">
    <input name="wp_pp_payment_email" type="text" size="35" value="<?php echo get_option('wp_pp_payment_email'); ?>"/>
    <br /><i>This is the Paypal Email address where the payments will go</i><br /><br />
    </td></tr>

    <tr valign="top"><td width="25%" align="right">
    <strong>Choose Payment Currency : </strong>
    </td><td align="left">
    <select id="paypal_payment_currency" name="paypal_payment_currency">
    <?php _e('<option value="USD"') ?><?php if ($paypal_payment_currency == "USD") echo " selected " ?><?php _e('>US Dollar</option>') ?>
    <?php _e('<option value="GBP"') ?><?php if ($paypal_payment_currency == "GBP") echo " selected " ?><?php _e('>Pound Sterling</option>') ?>
    <?php _e('<option value="EUR"') ?><?php if ($paypal_payment_currency == "EUR") echo " selected " ?><?php _e('>Euro</option>') ?>
    <?php _e('<option value="AUD"') ?><?php if ($paypal_payment_currency == "AUD") echo " selected " ?><?php _e('>Australian Dollar</option>') ?>
    <?php _e('<option value="CAD"') ?><?php if ($paypal_payment_currency == "CAD") echo " selected " ?><?php _e('>Canadian Dollar</option>') ?>
    <?php _e('<option value="NZD"') ?><?php if ($paypal_payment_currency == "NZD") echo " selected " ?><?php _e('>New Zealand Dollar</option>') ?>
    <?php _e('<option value="HKD"') ?><?php if ($paypal_payment_currency == "HKD") echo " selected " ?><?php _e('>Hong Kong Dollar</option>') ?>
    </select>
    <br /><i>This is the currency for your visitors to make Payments or Donations in.</i><br /><br />
    </td></tr>

    <tr valign="top"><td width="25%" align="right">
    <strong>Payment Subject :</strong>
    </td><td align="left">
    <input name="wp_pp_payment_subject" type="text" size="35" value="<?php echo get_option('wp_pp_payment_subject'); ?>"/>
    <br /><i>Enter the Product or service name or the reason for the payment here. The visitors will see this text</i><br /><br />
    </td></tr>

    <tr valign="top"><td width="25%" align="right">
    <strong>Payment Option 1 :</strong>
    </td><td align="left">
    <input name="wp_pp_payment_item1" type="text" size="25" value="<?php echo get_option('wp_pp_payment_item1'); ?>"/>
    <strong>Price :</strong>
    <input name="wp_pp_payment_value1" type="text" size="10" value="<?php echo get_option('wp_pp_payment_value1'); ?>"/>
    <br />
    </td></tr>

    <tr valign="top"><td width="25%" align="right">
    <strong>Payment Option 2 :</strong>
    </td><td align="left">
    <input name="wp_pp_payment_item2" type="text" size="25" value="<?php echo get_option('wp_pp_payment_item2'); ?>"/>
    <strong>Price :</strong>
    <input name="wp_pp_payment_value2" type="text" size="10" value="<?php echo get_option('wp_pp_payment_value2'); ?>"/>
    <br />
    </td></tr>

    <tr valign="top"><td width="25%" align="right">
    <strong>Payment Option 3 :</strong>
    </td><td align="left">
    <input name="wp_pp_payment_item3" type="text" size="25" value="<?php echo get_option('wp_pp_payment_item3'); ?>"/>
    <strong>Price :</strong>
    <input name="wp_pp_payment_value3" type="text" size="10" value="<?php echo get_option('wp_pp_payment_value3'); ?>"/>
    <br />
    </td></tr>

    <tr valign="top"><td width="25%" align="right">
    <strong>Payment Option 4 :</strong>
    </td><td align="left">
    <input name="wp_pp_payment_item4" type="text" size="25" value="<?php echo get_option('wp_pp_payment_item4'); ?>"/>
    <strong>Price :</strong>
    <input name="wp_pp_payment_value4" type="text" size="10" value="<?php echo get_option('wp_pp_payment_value4'); ?>"/>
    <br />
    </td></tr>

    <tr valign="top"><td width="25%" align="right">
    <strong>Payment Option 5 :</strong>
    </td><td align="left">
    <input name="wp_pp_payment_item5" type="text" size="25" value="<?php echo get_option('wp_pp_payment_item5'); ?>"/>
    <strong>Price :</strong>
    <input name="wp_pp_payment_value5" type="text" size="10" value="<?php echo get_option('wp_pp_payment_value5'); ?>"/>
    <br />
    </td></tr>

    <tr valign="top"><td width="25%" align="right">
    <strong>Payment Option 6 :</strong>
    </td><td align="left">
    <input name="wp_pp_payment_item6" type="text" size="25" value="<?php echo get_option('wp_pp_payment_item6'); ?>"/>
    <strong>Price :</strong>
    <input name="wp_pp_payment_value6" type="text" size="10" value="<?php echo get_option('wp_pp_payment_value6'); ?>"/>
    <br /><i>Enter the name of the service or product and the price. eg. Enter "Basic service - $10" in the Payment Option text box and "10.00" in the price text box to accept a payment of $10 for "Basic service". Leave the Payment Option and Price fields empty if u don't want to use that option. For example, if you have 3 price options then fill in the top 3 and leave the rest empty.</i>
    </td></tr>

    </table>

    <br /><br />
<strong>Choose a Submit Button Type :</strong>
<br /><i>This is the button the visitors will click on to make Payments or Donations.</i><br />
<table style="width:50%; border-spacing:0; padding:0; text-align:center;">
<tr>
<td>
<?php _e('<input type="radio" name="payment_button_type" value="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif"') ?>
<?php if ($payment_button_type == "https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif") echo " checked " ?>
<?php _e('/>') ?>
</td>
<td>
<?php _e('<input type="radio" name="payment_button_type" value="https://www.paypal.com/en_US/i/btn/x-click-but11.gif"') ?>
<?php if ($payment_button_type == "https://www.paypal.com/en_US/i/btn/x-click-but11.gif") echo " checked " ?>
<?php _e('/>') ?>
</td>
</tr>
<tr>
<td><img border="0" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" alt="" /></td>
<td><img border="0" src="https://www.paypal.com/en_US/i/btn/x-click-but11.gif" alt="" /></td>
</tr>
</table>

    </fieldset>

    <div class="submit">
        <input type="submit" name="info_update" value="<?php _e('Update options'); ?> &raquo;" />
    </div>

    </form>
    </div><?php
}

function show_wp_paypal_payment_widget()
{
    $wp_paypal_payment_widget_title_name_value = get_option('wp_paypal_widget_title_name');
    echo '<h2>';
    echo $wp_paypal_payment_widget_title_name_value;
    echo '</h2><br />';
    echo Paypal_payment_accept();
}

function wp_paypal_payment_widget_control()
{
    ?>
    <p>
    <? _e("Set the Plugin Settings from the Settings menu"); ?>
    </p>
    <?php

}
function widget_wp_paypal_payment_init()
{
    $widget_options = array('classname' => 'widget_wp_paypal_payment', 'description' => __( "Display WP Paypal Payment.") );
    wp_register_sidebar_widget('wp_paypal_payment_widgets', __('WP Paypal Payment'), 'show_wp_paypal_payment_widget', $widget_options);
    wp_register_widget_control('wp_paypal_payment_widgets', __('WP Paypal Payment'), 'wp_paypal_payment_widget_control' );
}

add_filter('the_content', 'wp_ppp_process');

add_action('init', 'widget_wp_paypal_payment_init');

// Insert the paypal_payment_add_option_pages in the 'admin_menu'
add_action('admin_menu', 'paypal_payment_add_option_pages');

?>
