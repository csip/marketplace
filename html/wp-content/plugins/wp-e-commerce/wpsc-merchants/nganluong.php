<?php
$nzshpcrt_gateways[$num]['name'] = 'Tích hợp thanh toán ngân lượng';
$nzshpcrt_gateways[$num]['admin_name'] = 'Nganluong_Pro';
$nzshpcrt_gateways[$num]['internalname'] = 'nganluong_pro';
$nzshpcrt_gateways[$num]['function'] = 'gateway_nganluong';
$nzshpcrt_gateways[$num]['form'] = "form_nganluong";
$nzshpcrt_gateways[$num]['submit_function'] = "submit_nganluong";
$nzshpcrt_gateways[$num]['payment_type'] = "nganluong";

// Hack by multiplying subtotal to USD exchange rate until we can fix for it to be automatic

function cart_total() {
	global $wpsc_cart;  
	$total = $wpsc_cart->calculate_subtotal();
	$total += $wpsc_cart->calculate_total_shipping();
	$total -= $wpsc_cart->coupons_amount;
	if(wpsc_tax_isincluded() == false){
		$total += $wpsc_cart->calculate_total_tax();
	}
	return $total*21243;
}
function gateway_nganluong($seperator, $sessionid) {
	global $wpdb;
	$purchase_log = $wpdb->get_row("SELECT * FROM `".WPSC_TABLE_PURCHASE_LOGS."` WHERE `sessionid`= ".$sessionid." LIMIT 1",ARRAY_A) ;
header("Location:"."https://www.nganluong.vn/button_payment.php?receiver=".get_option('payment_receiver')."&price=". cart_total()."&product_name=".$purchase_log['id']."&return_url=".get_option('payment_return'));
  exit();	
}

function submit_nganluong() {
  return true;
}

function form_nganluong() {  
	$output = "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	// $output = "	</td>\n\r";
	// $output = "	<td>\n\r";
	
	$output .= "<strong>".__('Enter receiver address', 'wpsc').":</strong><br />\n\r";
	$output .= "<input name='wpsc_options[payment_receiver]' size=40 value=".get_option('payment_receiver')."><br />\n\r";
	$output .= "<strong>".__('Enter return url', 'wpsc').":</strong><br />\n\r";
	$output .= "<input name='wpsc_options[payment_return]' size=40 value=".get_option('payment_return')."><br />\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
  return $output;
}